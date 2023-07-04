<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Recorte;
use Illuminate\Http\Request;
use App\Models\Remuneracion;
use Carbon\Carbon;

use Illuminate\Http\Response;


class RemuneracionController extends Controller
{
    /**
     * Muestra la vista principal de remuneraciones.
     *
     * @return \Illuminate\View\View  Vista principal de remuneraciones.
     */
    public function index()
    {
        $remuneracionId = 0;
        $columns = [
            'id',
            'empleado_id',
            'Empleado',
            'tipos_recorte',
            'Contrato_id',
            'Contrato',
            'Concepto',
            'Monto total',
            'creado en',
            'actualizado en',
            'opciones'
        ];

        $data = [];
        return view('admin.remuneracion', compact('remuneracionId', 'columns', 'data'));
    }
    /**
     * Obtiene los datos de remuneraciones en formato JSON.
     *
     * @param \Illuminate\Http\Request $request  Objeto de solicitud HTTP.
     * @return \Illuminate\Http\JsonResponse  Datos de remuneraciones en formato JSON.
     */
    public function getData(Request $request)
    {
        try {
            // Verifica si la solicitud es una solicitud Ajax
            if ($request->ajax()) {
                // Obtiene todas las remuneraciones
                $remuneraciones = Remuneracion::all();

                // Transforma los datos de las remuneraciones utilizando el método transformRemuneraciones
                $data = $this->transformRemuneraciones($remuneraciones);

                // Retorna los datos de remuneraciones en formato JSON con el código de respuesta HTTP 200 (OK)
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                // Si la solicitud no es una solicitud Ajax, lanza una excepción indicando una solicitud no válida
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            // Captura cualquier excepción lanzada y retorna un mensaje de error en formato JSON con el código de respuesta HTTP 400 (BAD REQUEST)
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transforma los datos de las remuneraciones en el formato deseado.
     *
     * @param \Illuminate\Database\Eloquent\Collection $remuneraciones  Colección de remuneraciones.
     * @return array  Datos de remuneraciones transformados.
     */
    private function transformRemuneraciones($remuneraciones)
    {
        // Utiliza el método map() en la colección de remuneraciones para transformar cada elemento
        return $remuneraciones->map(function ($remuneracion) {

            // Obtiene los recortes asociados a la remuneración junto con sus tipos de recorte
            $recortes = $remuneracion->recortes()->with('tipo_recorte')->get();

            // Crea un array asociativo donde las claves son los IDs de los recortes y los valores son los tipos de recorte
            $tiposRecorte = $recortes->pluck('tipo_recorte.tipo', 'id')->all();

            // Crea un array asociativo donde las claves son los IDs de los recortes y los valores son los nombres y descripciones de los tipos de recorte
            $nombresTiposRecorte = $recortes->mapWithKeys(function ($recorte) {
                return [$recorte->id => [
                    'description' => $recorte->tipo_recorte->description,
                    'tipo' => $recorte->tipo_recorte->tipo,
                ]];
            })->all();

            // Retorna un array con los datos transformados de la remuneración
            return [
                'id' => $remuneracion->id,
                'empleado_id' => $remuneracion->empleado->id,
                'Empleado' => $remuneracion->empleado->nombre . ' ' . $remuneracion->empleado->apellidos,
                'tipos_recorte' => [
                    'nombres' => $nombresTiposRecorte,
                    'ids' => array_keys($nombresTiposRecorte),
                ],
                'Contrato_id' => $remuneracion->contrato->id,
                'Contrato' => $remuneracion->contrato->tipoContrato->tipo,
                'Concepto' => $remuneracion->concepto,
                'Monto total' => $remuneracion->monto_total,
                'creado en' => optional($remuneracion->created_at)->toDateTimeString(),
                'actualizado en' => optional($remuneracion->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Muestra los detalles del calendario para un empleado específico.
     *
     * @param \Illuminate\Http\Request $request  Objeto de solicitud HTTP.
     * @return \Illuminate\View\View  Vista de detalles del calendario.
     */
    public function showCalendarDetails(Request $request)
    {
        $empleado = $request['empleado'];
        $empleado = Empleado::findOrFail($empleado);

        if ($empleado) {
            // Obtener todos los perfiles del empleado
            $perfil = $empleado->perfilEmpleado;

            $contratos = $empleado->contratos;
            $contrato = 0;
            $fechaInicio = now();

            foreach ($contratos as $contrato) {
                $fechaVinculacion = Carbon::parse($contrato->fecha_vinculacion);
                $fechaRetiro = Carbon::parse($contrato->fecha_retiro);

                // Si el día actual se enceuntra
                if (now()->isBetween($fechaVinculacion, $fechaRetiro, true)) {
                    // Las fechas están entre el día actual (now())
                    // Realiza las acciones que necesites
                    // Calcular la fecha de inicio y fin del rango de fechas a mostrar
                    $fechaInicio = $fechaVinculacion->isAfter(now()->subMonth()) ? $fechaVinculacion : now()->startOfMonth();
                    $fechaInicio = $fechaInicio->isAfter(now()->startOfMonth()) ? $fechaInicio : now()->startOfMonth();
                }
                break;
            }

            $fechaFin = Carbon::now()->endOfMonth(); // Cambia esto por la fecha de fin deseada

            // Obtener los datos de asistencias y exoneraciones
            $asistencias = $this->generateAsistencias($empleado, $perfil, $fechaInicio, $fechaFin, $contrato);

            // Generar los eventos del calendario
            $events = $this->generateEvents($asistencias);

            return view('admin.horario', compact('events', 'empleado', 'perfil', 'contrato'));
        }
    }
    /**
     * Almacena una nueva remuneración en la base de datos.
     *
     * @param \Illuminate\Http\Request $request  Objeto de solicitud HTTP.
     * @return \Illuminate\Http\RedirectResponse  Redirecciona a la página anterior con un mensaje de éxito.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_selectEmpleado' => 'required',
            'i_selectTipoRecorte' => 'required',
            'i_selectContrato' => 'required',
            'i_concepto' => 'required',
        ]);


        // Obtener los datos del formulario
        $empleadoId = $request->input('i_selectEmpleado');
        $contratoId = $request->input('i_selectContrato');
        $concepto = $request->input('i_concepto');
        $tiposRecorteSeleccionados = $request->input('i_selectTipoRecorte');

        // Obtener las asistencias del empleado
        $empleado = Empleado::findOrFail($empleadoId);
        $perfil = $empleado->perfilEmpleado;
        $contratos = $empleado->contratos;
        $contrato = 0;
        $fechaInicio = now();

        foreach ($contratos as $contrato) {
            $fechaVinculacion = Carbon::parse($contrato->fecha_vinculacion);
            $fechaRetiro = Carbon::parse($contrato->fecha_retiro);

            // Si el día actual se enceuntra
            if (now()->isBetween($fechaVinculacion, $fechaRetiro, true)) {
                // Las fechas están entre el día actual (now())
                // Realiza las acciones que necesites
                $fechaInicio = $fechaVinculacion->isAfter(now()->subMonth()) ? $fechaVinculacion : now()->startOfMonth();
                $fechaInicio = $fechaInicio->isAfter(now()->startOfMonth()) ? $fechaInicio : now()->startOfMonth();
            }
            break;
        }

        $fechaFin = Carbon::now()->endOfMonth(); // Cambia esto por la fecha de fin deseada

        $asistencias = [];

        // Generar las asistencias del empleado
        $asistencias = $this->generateAsistencias($empleado, $perfil, $fechaInicio, $fechaFin, $contrato);

        if ($asistencias) {
            // Calcular el monto total de la remuneración
            $montoTotal = $this->calcularMontoTotal($asistencias, $tiposRecorteSeleccionados);

            // Crear el registro de remuneración
            $remuneracion = new Remuneracion();
            $remuneracion->empleado_id = $empleadoId;
            $remuneracion->contrato_id = $contratoId;
            $remuneracion->concepto = $concepto;
            $remuneracion->monto_total = $montoTotal;
            $remuneracion->save();

            // Guardar la relación muchos a muchos con los tipos de recorte seleccionados
            $remuneracion->recortes()->attach($tiposRecorteSeleccionados);

            return redirect()->back()->with('success', 'Remuneración creada con éxito');
        }
        return redirect()->back()->withErrors(['Ocurrió un problema en el calculo del monto total.']);
    }

    /**
     * Actualiza una remuneración existente en la base de datos.
     *
     * @param \Illuminate\Http\Request $request  Objeto de solicitud HTTP.
     * @param int $id  ID de la remuneración a actualizar.
     * @return \Illuminate\Http\RedirectResponse  Redirecciona a la página anterior con un mensaje de éxito.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_selectEmpleado' => 'required',
            'e_selectTipoRecorte' => 'required',
            'e_selectContrato' => 'required',
            'e_concepto' => 'required',
        ]);

        // Obtener los datos del formulario
        $empleadoId = $request->input('e_selectEmpleado');
        $contratoId = $request->input('e_selectContrato');
        $concepto = $request->input('e_concepto');
        $tiposRecorteSeleccionados = $request->input('e_selectTipoRecorte');

        // Obtener las asistencias del empleado
        $empleado = Empleado::findOrFail($empleadoId);
        $perfil = $empleado->perfilEmpleado;

        $contratos = $empleado->contratos;
        $contrato = 0;
        $fechaInicio = now();

        foreach ($contratos as $contrato) {
            $fechaVinculacion = Carbon::parse($contrato->fecha_vinculacion);
            $fechaRetiro = Carbon::parse($contrato->fecha_retiro);

            // Si el día actual se enceuntra
            if (now()->isBetween($fechaVinculacion, $fechaRetiro, true)) {
                // Las fechas están entre el día actual (now())
                // Realiza las acciones que necesites
                $fechaInicio = $fechaVinculacion->isAfter(now()->subMonth()) ? $fechaVinculacion : now()->startOfMonth();
                $fechaInicio = $fechaInicio->isAfter(now()->startOfMonth()) ? $fechaInicio : now()->startOfMonth();
            }
            break;
        }

        $fechaFin = Carbon::now()->endOfMonth(); // Cambia esto por la fecha de fin deseada
        $asistencias = [];

        // Generar las asistencias del empleado
        $asistencias = $this->generateAsistencias($empleado, $perfil, $fechaInicio, $fechaFin, $contrato);
        if ($asistencias) {
            // Calcular el monto total de la remuneración
            $montoTotal = $this->calcularMontoTotal($asistencias, $tiposRecorteSeleccionados);

            // Crear el registro de remuneración
            $remuneracion = Remuneracion::findOrFail($id);
            $remuneracion->empleado_id = $empleadoId;
            $remuneracion->contrato_id = $contratoId;
            $remuneracion->concepto = $concepto;
            $remuneracion->monto_total = $montoTotal;
            $remuneracion->save();

            // Guardar la relación muchos a muchos con los tipos de recorte seleccionados
            $remuneracion->recortes()->attach($tiposRecorteSeleccionados);
            $remuneracion->update();

            return redirect()->back()->with('success', 'Remuneracion actualizada con éxito');
        }
        return redirect()->back()->withErrors(['Ocurrió un problema en el calculo del monto total.']);
    }
    /**
     * Elimina una remuneración de la base de datos.
     *
     * @param int $id  ID de la remuneración a eliminar.
     * @return \Illuminate\Http\RedirectResponse  Redirecciona a la página anterior con un mensaje de éxito.
     */
    public function destroy($id)
    {
        $remuneracion = Remuneracion::findOrFail($id);
        $remuneracion->delete();

        return redirect()->back()
            ->with('success', 'Eliminación realizada con éxito');
    }

    /**
     * Genera las asistencias para un empleado en un rango de fechas.
     *
     * @param \App\Models\Empleado $empleado  Objeto de modelo de empleado.
     * @param \App\Models\Perfil $perfil  Objeto de modelo de perfil.
     * @param \DateTime $fechaInicio  Fecha de inicio del rango.
     * @param \DateTime $fechaFin  Fecha de fin del rango.
     * @return \Illuminate\Database\Eloquent\Collection  Colección de asistencias generadas.
     */
    private function generateAsistencias($empleado, $perfil, $fechaInicio, $fechaFin, $contrato)
    {
        $asistencias = [];

        // Obtener todas las asistencias del empleado
        $allAsistencias = $empleado->asistencias;

        // Obtener los días laborales del perfil del empleado
        $diasLaborales = json_decode($contrato->dias_laborales);
        $diaSemanaNumeros = ['Lunes' => 1, 'Martes' => 2, 'Miércoles' => 3, 'Jueves' => 4, 'Viernes' => 5, 'Sábado' => 6, 'Domingo' => 7];
        $diasLaboralesNumeros = array_map(function ($dia) use ($diaSemanaNumeros) {
            return $diaSemanaNumeros[$dia];
        }, $diasLaborales);

        // Obtener el rango de fechas entre la fecha de inicio y fin
        $fechas = $this->getFechasRango($fechaInicio, $fechaFin);

        // Obtener las exoneraciones del empleado
        $exoneraciones = $empleado->exoneraciones;

        // Iterar por cada fecha en el rango
        foreach ($fechas as $fecha) {
            // Verificar si el día corresponde a un día laboral según el perfil del empleado
            $diaSemana = $fecha->format('N');

            if (in_array($diaSemana, $diasLaboralesNumeros)) {
                // Verificar si hay una asistencia registrada para este día
                $asistenciaEnFecha = $allAsistencias->first(function ($asistencia) use ($fecha) {
                    return $asistencia->dia === $fecha->toDateString();
                });

                // Verificar si la asistencia está exonerada
                $inasistenciaExonerada = $exoneraciones->contains(function ($exoneracion) use ($fecha) {
                    $exoneracionFechaInicio = Carbon::parse($exoneracion->fecha_inicio);
                    $exoneracionFechaFin = Carbon::parse($exoneracion->fecha_fin);
                    return $fecha->between($exoneracionFechaInicio, $exoneracionFechaFin);
                });

                // Agregar la asistencia a la lista
                $asistencias[] = [
                    'empleado_id' => $empleado->id, // ID del empleado
                    'perfil' => $perfil, // Perfil del empleado
                    'contrato' => $contrato, // Perfil del empleado
                    'dia' => $fecha->format('Y-m-d'), // Fecha en formato "YYYY-MM-DD"
                    'asistio' => isset($asistenciaEnFecha) && $asistenciaEnFecha->estado == 1, // True si el empleado asistió, false en caso contrario
                    'exonerado' => $inasistenciaExonerada, // True si no está exonerado, false si está exonerado
                    'hora_entrada' => isset($asistenciaEnFecha) ? $asistenciaEnFecha->hora_entrada : null, // Hora de entrada registrada, o null si no hay registro
                    'hora_salida' => isset($asistenciaEnFecha) ? $asistenciaEnFecha->hora_salida : null, // Hora de salida registrada, o null si no hay registro
                ];
            }
        }

        return $asistencias;
    }

    /**
     * Obtiene todas las fechas dentro de un rango.
     *
     * @param \DateTime $fechaInicio  Fecha de inicio del rango.
     * @param \DateTime $fechaFin  Fecha de fin del rango.
     * @return array  Array de fechas dentro del rango.
     */
    private function getFechasRango($fechaInicio, $fechaFin)
    {
        $fechas = [];

        // Verifica si $fechaInicio es una instancia de Carbon (una clase de fecha y hora en Laravel)
        if ($fechaInicio instanceof Carbon) {
            $fecha = clone $fechaInicio;

            // Itera desde $fechaInicio hasta $fechaFin y agrega cada fecha al array $fechas
            while ($fecha <= $fechaFin) {
                $fechas[] = clone $fecha;
                $fecha->addDay(); // Agrega un día a la fecha actual en cada iteración
            }
        }

        return $fechas;
    }

    /**
     * Genera eventos basados en las asistencias proporcionadas.
     *
     * @param array $asistencias  Arreglo de asistencias.
     * @return array  Arreglo de eventos generados.
     */
    private function generateEvents($asistencias)
    {
        $events = [];

        foreach ($asistencias as $asistencia) {
            $fecha = Carbon::createFromFormat('Y-m-d', $asistencia['dia']);

            if ($asistencia['asistio']) {
                // Si asistió, crea un evento con la hora de entrada y salida
                $horaEntrada = Carbon::parse($asistencia['hora_entrada']);
                $horaSalida = Carbon::parse($asistencia['hora_salida']);
                $events[] = [
                    'title' => 'Asistió',
                    'start' => $fecha->format('Y-m-d') . 'T' . $horaEntrada->format('H:i'),
                    'end' => $fecha->format('Y-m-d') . 'T' . $horaSalida->format('H:i'),
                    'allDay' => false,
                    'backgroundColor' => '#00a65a',
                    'borderColor' => '#00a65a'
                ];
            } else {
                if ($asistencia['exonerado']) {
                    // Si no asistió pero está exonerado, crea un evento para todo el día
                    $events[] = [
                        'title' => 'Exonerado',
                        'start' => $fecha->format('Y-m-d'),
                        'end' => $fecha->format('Y-m-d'),
                        'allDay' => true,
                        'backgroundColor' => '#f56954',
                        'borderColor' => '#f56954'
                    ];
                } else {
                    // Si no asistió y no está exonerado, crea un evento para todo el día
                    $events[] = [
                        'title' => 'Faltó',
                        'start' => $fecha->format('Y-m-d'),
                        'end' => $fecha->format('Y-m-d'),
                        'allDay' => true,
                        'backgroundColor' => '#f56954',
                        'borderColor' => '#f56954'
                    ];
                }
            }
        }

        return $events;
    }

    /**
     * Calcula el monto total basado en las asistencias e inasistencias y los tipos de recorte seleccionados.
     *
     * @param array $asistencias                Arreglo de asistencias e inasistencias.
     * @param array $tiposRecorteSeleccionados  Arreglo de tipos de recorte seleccionados.
     * @return float                            Monto total calculado.
     */
    private function calcularMontoTotal($asistencias, $tiposRecorteSeleccionados)
    {
        // Obtener el salario base del perfil del empleado
        $salarioBase = $asistencias[0]['contrato']->salario_base;

        // Obtener los montos de recorte por tipo de recorte seleccionados
        $tiposRecorte = Recorte::whereIn('id', $tiposRecorteSeleccionados)
            ->with('tipo_recorte') // Load the relationship
            ->get();

        $nombresTiposRecorte = $tiposRecorte->mapWithKeys(function ($recorte) {
            return [$recorte->id => [
                'description' => $recorte->tipo_recorte->description,
                'tipo' => $recorte->tipo_recorte->tipo,
            ]];
        })->all();

        $tiposRecorte = $tiposRecorte->pluck('monto_recorte', 'id')->all();

        // Calcular el monto total en base a las asistencias e inasistencias
        $montoTotal = $salarioBase;

        $aplicarDescuentoInasistencia = true; // Variable para controlar si se debe aplicar el descuento por inasistencia
        $bonificacionAplicada = false; // Variable para controlar si se ha aplicado la bonificación
        $descuentoAplicado = false; // Variable para controlar si se ha aplicado el descuento por otro tipo

        foreach ($asistencias as $asistencia) {
            if (!$asistencia['asistio']) {
                // Verificar si la inasistencia está exonerada
                $inasistenciaExonerada = $asistencia['exonerado'];

                if (!$inasistenciaExonerada && $aplicarDescuentoInasistencia) {
                    // Aplicar los tipos de recorte seleccionados a la inasistencia
                    foreach ($tiposRecorteSeleccionados as $tipoRecorteId) {
                        // Verificar si es un descuento (tipo = 0) o una bonificación (tipo = 1)
                        $tipoRecorte = $nombresTiposRecorte[$tipoRecorteId]['tipo'];
                        $tipoRecorteDesc = $nombresTiposRecorte[$tipoRecorteId]['description'];

                        if ($tipoRecorte === 0 && stripos($tipoRecorteDesc, 'inasistencia') !== false) {
                            // Aplicar descuento por inasistencias al monto total
                            $montoTotal -= $tiposRecorte[$tipoRecorteId];
                            // printf('Se hizo un descuento por inasistencia de: ' . $tiposRecorte[$tipoRecorteId] . ' = ' . $montoTotal . ' ');
                        } elseif ($tipoRecorte === 0 && !$descuentoAplicado) {
                            // Aplicar descuento por cualquier otro tipo al monto total (solo una vez)
                            $montoTotal -= $tiposRecorte[$tipoRecorteId];
                            // printf('Se hizo un descuento por lo que sea de: ' . $tiposRecorte[$tipoRecorteId] . ' = ' . $montoTotal . ' ');
                            $descuentoAplicado = true;
                        }
                    }
                }
            } else {
                // Reactivar el descuento por inasistencia si el empleado asistió en algún momento
                $aplicarDescuentoInasistencia = true;
            }

            // Aplicar bonificación solo una vez
            if (!$bonificacionAplicada) {
                foreach ($tiposRecorteSeleccionados as $tipoRecorteId) {
                    // Verificar si es un descuento (tipo = 0) o una bonificación (tipo = 1)
                    $tipoRecorte = $nombresTiposRecorte[$tipoRecorteId]['tipo'];
                    if ($tipoRecorte === 1) {
                        // Aplicar bonificación al monto total
                        $montoTotal += $tiposRecorte[$tipoRecorteId];
                        // printf('Se hizo una bonif de: ' . $tiposRecorte[$tipoRecorteId] . ' = ' . $montoTotal . ' ');
                    }
                }

                $bonificacionAplicada = true;
            }
        }

        return $montoTotal;
    }
}
