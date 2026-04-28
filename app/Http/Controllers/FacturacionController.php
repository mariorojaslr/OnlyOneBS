<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CierreFacturacion;
use App\Models\Empresa;
use App\Models\Viaje;
use Carbon\Carbon;

class FacturacionController extends Controller
{
    private function getEmpresaIds()
    {
        $user = auth()->user();

        if (session('room_id')) {
            return Empresa::where('socio_id', session('room_id'))->pluck('id')->toArray();
        }

        if ($user->isSuperAdmin()) {
            return Empresa::pluck('id')->toArray();
        } elseif ($user->isOwner() || $user->isSocio()) {
            $mySocio = $user->socio;
            if ($mySocio && $mySocio->nivel == 1) {
                $socioIds = $mySocio->children()->pluck('id')->toArray();
                $socioIds[] = $mySocio->id;
                return Empresa::whereIn('socio_id', $socioIds)->pluck('id')->toArray();
            } else {
                return Empresa::where('socio_id', $mySocio->id)->pluck('id')->toArray();
            }
        } elseif ($user->isEmpresa()) {
            return [$user->empresa_id];
        }

        return [];
    }

    public function index()
    {
        $empresaIds = $this->getEmpresaIds();
        $cierres = CierreFacturacion::with('empresa')
            ->whereIn('empresa_id', $empresaIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('facturacion.index', compact('cierres'));
    }

    public function create(Request $request)
    {
        $empresaIds = $this->getEmpresaIds();
        $empresas = Empresa::whereIn('id', $empresaIds)->get();
        
        $empresaSeleccionada = null;
        $viajesPendientes = [];
        $totales = [
            'monto' => 0,
            'cantidad' => 0,
            'moneda' => 'ARS'
        ];

        if ($request->has('empresa_id')) {
            $empresaSeleccionada = Empresa::findOrFail($request->empresa_id);
            if (!in_array($empresaSeleccionada->id, $empresaIds)) {
                abort(403);
            }

            $viajesPendientes = Viaje::where('empresa_id', $empresaSeleccionada->id)
                                    ->whereNull('cierre_facturacion_id')
                                    ->get();
            
            $totales['monto'] = $viajesPendientes->sum('monto');
            $totales['cantidad'] = $viajesPendientes->count();
            $totales['moneda'] = $empresaSeleccionada->moneda;
        }

        return view('facturacion.create', compact('empresas', 'empresaSeleccionada', 'viajesPendientes', 'totales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $empresa = Empresa::findOrFail($request->empresa_id);
        
        // Obtenemos los viajes pendientes nuevamente por seguridad
        $viajesPendientes = Viaje::where('empresa_id', $empresa->id)
                                ->whereNull('cierre_facturacion_id')
                                ->get();

        if ($viajesPendientes->isEmpty()) {
            return back()->with('error', __('No hay viajes pendientes para facturar en esta empresa.'));
        }

        // Crear el Cierre
        $cierre = CierreFacturacion::create([
            'empresa_id' => $empresa->id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'monto_total' => $viajesPendientes->sum('monto'),
            'moneda' => $empresa->moneda,
            'cantidad_viajes' => $viajesPendientes->count(),
            'estado' => 'emitido'
        ]);

        // Archivar los viajes
        Viaje::whereIn('id', $viajesPendientes->pluck('id'))->update([
            'cierre_facturacion_id' => $cierre->id
        ]);

        return redirect()->route('facturacion.index')->with('success', __('Cierre de facturación emitido exitosamente. Los viajes han sido archivados.'));
    }

    public function show(CierreFacturacion $facturacion)
    {
        $empresaIds = $this->getEmpresaIds();
        if (!in_array($facturacion->empresa_id, $empresaIds)) {
            abort(403);
        }

        $facturacion->load(['empresa', 'viajes.pasajero', 'viajes.centroCosto']);
        
        return view('facturacion.show', compact('facturacion'));
    }
}
