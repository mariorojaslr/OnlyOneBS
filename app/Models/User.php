<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_OWNER = 'owner';
    const ROLE_ADMIN = 'admin';
    const ROLE_SOCIO = 'socio';
    const ROLE_ADMIN_VIEW = 'admin_view';
    const ROLE_EMPRESA = 'empresa';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'socio_id',
        'empresa_id',
        'phone',
        'telegram_id',
        'use_2fa',
        'locale'
    ];

    public function isSuperAdmin() { return $this->role === self::ROLE_SUPERADMIN; }
    public function isOwner() { return $this->role === self::ROLE_OWNER || ($this->role === self::ROLE_SOCIO && $this->socio?->nivel == 1); }
    public function isAdmin() { return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_OWNER, self::ROLE_ADMIN]); }
    public function isSocio() { return $this->role === self::ROLE_SOCIO && $this->socio?->nivel == 2; }
    public function isAdminView() { return $this->role === self::ROLE_ADMIN_VIEW; }
    public function isEmpresa() { return $this->role === self::ROLE_EMPRESA; }

    public function socio() { return $this->belongsTo(Socio::class); }
    public function empresa() { return $this->belongsTo(Empresa::class); }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
