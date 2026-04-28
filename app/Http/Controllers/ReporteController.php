<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Pasajero;
use App\Models\Viaje;
use App\Models\Socio;
use App\Models\CentroCosto;

class ReporteController extends Controller
{
    public function sincronizar()
    {
        $socios = Socio::all();
        return view('sincronizar', compact('socios'));
    }

    public function importarCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
            'socio_id' => 'required|exists:socios,id'
        ]);

        $file = $request->file('csv_file');
        $csvData = file_get_contents($file);
        $rows = array_map(function($v) { return str_getcsv($v, ';'); }, explode("\n", $csvData));
        $header = array_shift($rows);

        $socioId = $request->socio_id;
        $loadedCount = 0;
        $skippedCount = 0;

        foreach ($rows as $row) {
            if (count($row) < 80) continue;

            $empresaUuid = $row[86]; // passenger custom id o similar
            $empresaNombre = $row[85]; // passenger company
            
            $empresa = Empresa::updateOrCreate(
                ['uuid' => $empresaUuid],
                ['nombre' => $empresaNombre, 'socio_id' => $socioId]
            );

            $cc = CentroCosto::updateOrCreate(
                ['empresa_id' => $empresa->id, 'nombre' => $row[62]], // cost center name
                ['numero' => $row[63]] // cost center id
            );

            $pasajero = Pasajero::updateOrCreate(
                ['telefono' => $row[16]], // passenger phone
                ['nombre_completo' => $row[12], 'centro_costo_id' => $cc->id]
            );

            // Verificar si el viaje ya existe por UUID para evitar duplicados
            $viajeUuid = $row[81];
            if (Viaje::where('uuid', $viajeUuid)->exists()) {
                $skippedCount++;
                continue;
            }

            Viaje::create([
                'uuid' => $viajeUuid,
                'pasajero_id' => $pasajero->id,
                'centro_costo_id' => $cc->id,
                'empresa_id' => $empresa->id,
                'origen' => $row[23],
                'destino' => $row[35],
                'fecha_inicio' => $this->parseFecha($row[8]),
                'monto' => floatval(str_replace(',', '.', $row[55])),
                'distancia' => $row[57],
                'estado' => $row[54],
            ]);
            $loadedCount++;
        }

        return redirect()->back()->with('success', "Proceso finalizado. Cargados: $loadedCount. Duplicados omitidos: $skippedCount.");
    }

    private function parseFecha($fechaStr) {
        if (empty($fechaStr)) return null;
        // Example: 15/04/2026 15:56:44 -0300
        $parts = explode(' ', $fechaStr);
        if (count($parts) >= 2) {
            $date = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $parts[0] . ' ' . $parts[1]);
            return $date->format('Y-m-d H:i:s');
        }
        return null;
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $view = $request->get('view', 'apps'); // apps, fleets, companies, details
        $l1_id = $request->get('l1_id');
        $l2_id = $request->get('l2_id');
        $empresa_id = $request->get('empresa_id');

        $data = [
            'level' => $view,
            'items' => [],
            'parent' => null,
            'stats' => []
        ];

        // Lógica de navegación jerárquica
        if ($user->isSuperAdmin()) {
            if ($view === 'apps') {
                $request->session()->forget('room_id');
                $request->session()->forget('empresa_room_id');
                $data['items'] = Socio::where('nivel', 1)->get();
            } elseif ($view === 'fleets' && $l1_id) {
                $request->session()->forget('room_id');
                $request->session()->forget('empresa_room_id');
                $data['items'] = Socio::where('parent_id', $l1_id)->get();
                $data['parent'] = Socio::find($l1_id);
            } elseif ($view === 'companies' && $l2_id) {
                $request->session()->put('room_id', $l2_id);
                $request->session()->forget('empresa_room_id');
                $data['items'] = Empresa::where('socio_id', $l2_id)->get();
                $data['parent'] = Socio::find($l2_id);
            } elseif ($view === 'details' && $empresa_id) {
                $empresa = Empresa::findOrFail($empresa_id);
                $request->session()->put('room_id', $empresa->socio_id);
                $request->session()->put('empresa_room_id', $empresa_id);
                return $this->getEmpresaDetails($empresa_id);
            } else {
                $request->session()->forget('room_id');
                $request->session()->forget('empresa_room_id');
                $data['items'] = Socio::where('nivel', 1)->get();
                $data['level'] = 'apps';
            }
        } elseif ($user->isOwner()) {
            $mySocio = $user->socio;
            if ($view === 'fleets' || $view === 'apps') {
                $request->session()->forget('room_id');
                $request->session()->forget('empresa_room_id');
                $data['items'] = Socio::where('parent_id', $mySocio->id)->get();
                $data['level'] = 'fleets';
                $data['parent'] = $mySocio;
            } elseif ($view === 'companies' && $l2_id) {
                $request->session()->put('room_id', $l2_id);
                $request->session()->forget('empresa_room_id');
                $data['items'] = Empresa::where('socio_id', $l2_id)->get();
                $data['parent'] = Socio::find($l2_id);
            } elseif ($view === 'details' && $empresa_id) {
                $empresa = Empresa::findOrFail($empresa_id);
                $request->session()->put('room_id', $empresa->socio_id);
                $request->session()->put('empresa_room_id', $empresa_id);
                return $this->getEmpresaDetails($empresa_id);
            }
        } elseif ($user->isSocio()) {
            $mySocio = $user->socio;
            $request->session()->put('room_id', $mySocio->id);
            if ($view === 'details' && $empresa_id) {
                $request->session()->put('empresa_room_id', $empresa_id);
                return $this->getEmpresaDetails($empresa_id);
            } else {
                $request->session()->forget('empresa_room_id');
                $data['items'] = Empresa::where('socio_id', $mySocio->id)->get();
                $data['level'] = 'companies';
                $data['parent'] = $mySocio;
            }
        } elseif ($user->isEmpresa()) {
            $request->session()->put('room_id', $user->empresa->socio_id);
            $request->session()->put('empresa_room_id', $user->empresa_id);
            return $this->getEmpresaDetails($user->empresa_id);
        }

        return view('dashboard_v2', $data);
    }

    private function getEmpresaDetails($empresaId)
    {
        $empresa = Empresa::with(['viajes.pasajero', 'centrosCosto.viajes.pasajero', 'centrosCosto.pasajeros'])->findOrFail($empresaId);
        
        $centros = [];
        foreach ($empresa->centrosCosto as $cc) {
            $viajesCC = $empresa->viajes->where('centro_costo_id', $cc->id);
            $pasajeros = [];
            $viajesPorPasajero = $viajesCC->groupBy('pasajero_id');
            
            foreach ($viajesPorPasajero as $pId => $pViajes) {
                $pasajeroObj = $pViajes->first()->pasajero;
                if (!$pasajeroObj) continue;

                $pasajeros[] = [
                    'nombre' => $pasajeroObj->nombre_completo,
                    'viajes_count' => $pViajes->count(),
                    'monto_total' => (float) $pViajes->sum('monto'),
                    'km_total' => (float) $this->sumarDistancias($pViajes),
                    'lista_viajes' => $pViajes->map(fn($v) => [
                        'fecha' => $v->fecha_inicio ? \Carbon\Carbon::parse($v->fecha_inicio)->format('d/m/Y H:i') : 'N/A',
                        'origen' => $v->origen,
                        'destino' => $v->destino,
                        'monto' => (float) $v->monto,
                        'distancia' => $this->parseDistancia($v->distancia)
                    ])
                ];
            }

            $centros[] = [
                'model' => $cc,
                'viajes_count' => $viajesCC->count(),
                'monto_total' => (float) $viajesCC->sum('monto'),
                'km_total' => (float) $this->sumarDistancias($viajesCC),
                'pasajeros' => $pasajeros
            ];
        }

        return view('dashboard_details', [
            'level' => 'details',
            'empresa' => $empresa,
            'centros' => $centros,
            'monto_total' => (float) $empresa->viajes->sum('monto'),
            'km_total' => (float) $this->sumarDistancias($empresa->viajes)
        ]);
    }

    private function sumarDistancias($viajes)
    {
        $total = 0;
        foreach ($viajes as $v) {
            $total += $this->parseDistancia($v->distancia);
        }
        return $total;
    }

    private function parseDistancia($distancia)
    {
        $val = str_replace(',', '.', $distancia);
        $val = preg_replace('/[^0-9.]/', '', $val);
        return (float) $val;
    }
}
