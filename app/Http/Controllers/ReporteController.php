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
        $socios = [];
        $selectedSocioId = null;
        $selectedEmpresaId = null;

        $query = Empresa::with(['viajes.pasajero', 'centrosCosto.viajes.pasajero', 'centrosCosto.pasajeros']);

        if ($user->isSuperAdmin()) {
            $socios = Socio::all();
            $selectedSocioId = $request->get('socio_id', 'all');
            
            if ($selectedSocioId !== 'all') {
                $query->where('socio_id', $selectedSocioId);
            }
        } elseif ($user->isOwner()) {
            $mySocio = $user->socio;
            
            if ($mySocio) {
                $provincialIds = $mySocio->children()->pluck('id')->toArray();
                $provincialIds[] = $mySocio->id;
                
                $selectedSocioId = $request->get('socio_id', 'all');
                
                if ($selectedSocioId === 'all') {
                    $query->whereIn('socio_id', $provincialIds);
                } else {
                    $query->where('socio_id', $selectedSocioId);
                }
                
                $socios = Socio::whereIn('id', $provincialIds)->get();
            } else {
                // Si es owner pero no tiene socio asignado, no devolvemos nada para evitar error
                $query->whereRaw('1 = 0');
                $socios = [];
            }
        } elseif ($user->isSocio()) {
            // Nivel 2: Solo ve su provincia
            $selectedSocioId = $user->socio->id;
            $query->where('socio_id', $selectedSocioId);
        } elseif ($user->isEmpresa()) {
            $selectedEmpresaId = $user->empresa_id;
            $query->where('id', $selectedEmpresaId);
        }

        $empresas = $query->get();

        $dataEmpresas = [];
        $graficoLabels = [];
        $graficoCostos = [];
        $graficoKm = [];

        foreach ($empresas as $empresa) {
            $totalViajes = $empresa->viajes->count();
            $totalMonto = $empresa->viajes->sum('monto');
            $totalKm = $this->sumarDistancias($empresa->viajes);
            
            $centros = [];
            foreach ($empresa->centrosCosto as $cc) {
                $viajesCC = $empresa->viajes->where('centro_costo_id', $cc->id);
                
                $pasajeros = [];
                $viajesPorPasajero = $viajesCC->groupBy('pasajero_id');
                
                foreach ($viajesPorPasajero as $pId => $pViajes) {
                    $pasajeroObj = $pViajes->first()->pasajero;
                    
                    if (!$pasajeroObj) continue;

                    $individualTrips = [];
                    foreach ($pViajes as $v) {
                        $individualTrips[] = [
                            'fecha' => $v->fecha_inicio ? \Carbon\Carbon::parse($v->fecha_inicio)->format('d/m/Y H:i') : 'N/A',
                            'origen' => $v->origen,
                            'destino' => $v->destino,
                            'monto' => (float) $v->monto,
                            'distancia' => $this->parseDistancia($v->distancia)
                        ];
                    }

                    $pasajeros[] = [
                        'id' => $pasajeroObj->id,
                        'nombre' => $pasajeroObj->nombre_completo,
                        'viajes_count' => $pViajes->count(),
                        'monto_total' => (float) $pViajes->sum('monto'),
                        'km_total' => (float) $this->sumarDistancias($pViajes),
                        'lista_viajes' => $individualTrips
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

            $currentMonto = (float) $empresa->viajes->sum('monto');
            $currentKm = (float) $this->sumarDistancias($empresa->viajes);

            $dataEmpresas[$empresa->id] = [
                'model' => $empresa,
                'monto_total' => $currentMonto,
                'km_total' => $currentKm,
                'centros' => $centros
            ];

            // Datos para gráficos
            $graficoLabels[] = $empresa->nombre;
            $graficoCostos[] = $currentMonto;
            $graficoKm[] = $currentKm;
        }

        return view('dashboard', [
            'datos' => [
                'empresas' => $dataEmpresas,
                'grafico_labels' => $graficoLabels,
                'grafico_costos' => $graficoCostos,
                'grafico_km' => $graficoKm,
            ],
            'socios' => $socios,
            'selectedSocioId' => $selectedSocioId,
            'userRole' => $user->role
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
