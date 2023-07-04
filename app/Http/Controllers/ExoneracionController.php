<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exoneracion;
use Illuminate\Http\Response;

class ExoneracionController extends Controller
{
    /**
     * Display the exoneracion index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $exoneracionId = 0;
        $columns = [
            'id',
            'empleado_id',
            'empleado',
            'motivo_de_exoneración_id',
            'motivo de exoneración',
            'fecha inicio',
            'fecha final',
            'observación',
            'creado en',
            'actualizado en',
            'opciones'
        ];

        $data = [];
        return view('admin.exoneracion', compact('exoneracionId', 'columns', 'data'));
    }

    /**
     * Get the exoneracion data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $exoneraciones = Exoneracion::all();
                $data = $this->transformExoneraciones($exoneraciones);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transform the exoneracion data.
     *
     * @param \Illuminate\Database\Eloquent\Collection $exoneraciones
     * @return \Illuminate\Support\Collection
     */
    private function transformExoneraciones($exoneraciones)
    {
        return $exoneraciones->map(function ($exoneracion) {
            return [
                'id' => $exoneracion->id,
                'empleado_id' => optional($exoneracion->empleado)->id,
                'empleado' => optional($exoneracion->empleado)->nombre . ' ' .  optional($exoneracion->empleado)->apellidos,
                'motivo_de_exoneración_id' => optional($exoneracion->motivoExoneracion)->id,
                'motivo de exoneración' => optional($exoneracion->motivoExoneracion)->description,
                'fecha inicio' => $exoneracion->fecha_inicio,
                'fecha final' => $exoneracion->fecha_fin,
                'observación' => $exoneracion->observacion,
                'creado en' => optional($exoneracion->created_at)->toDateTimeString(),
                'actualizado en' => optional($exoneracion->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Store a new exoneracion.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_selectEmpleado' => 'required',
            'i_selectMotivoExioneracion' => 'required',
            'i_finicio' => 'required|date',
            'i_ffinal' => 'required|date|after_or_equal:i_finicio',
            'i_observacion' => 'required',
        ]);

        $fInicio = date("Y-m-d", strtotime($request->i_finicio));
        $fFin = date("Y-m-d", strtotime($request->i_ffinal));

        $exoneracion = Exoneracion::create([
            'empleado_id' => $validatedData['i_selectEmpleado'],
            'motivo_exoneracion_id' => $validatedData['i_selectMotivoExioneracion'],
            'fecha_inicio' => $fInicio,
            'fecha_fin' => $fFin,
            'observacion' => $validatedData['i_observacion'],
        ]);

        return redirect()->back()
            ->with('success', 'Registro realizado con éxito');
    }

    /**
     * Update an existing exoneracion.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_selectEmpleado' => 'required',
            'e_selectMotivoExioneracion' => 'required',
            'e_finicio' => 'required|date',
            'e_ffinal' => 'required|date|after_or_equal:e_finicio',
            'e_observacion' => 'required',
        ]);

        $exoneracion = Exoneracion::findOrFail($id);
        if ($exoneracion) {
            $fInicio = date("Y-m-d", strtotime($request->e_finicio));
            $fFin = date("Y-m-d", strtotime($request->e_ffinal));

            $exoneracion->update([
                'empleado_id' => $validatedData['e_selectEmpleado'],
                'motivo_exoneracion_id' => $validatedData['e_selectMotivoExioneracion'],
                'fecha_inicio' => $fInicio,
                'fecha_fin' => $fFin,
                'observacion' => $validatedData['e_observacion'],
            ]);

            return redirect()->back()
                ->with('success', 'Actualización realizada con éxito');
        }
        return redirect(route('exoneraciones'))->with('errors', 'Error al actualizar');
    }

    /**
     * Delete an exoneracion.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $exoneracion = Exoneracion::findOrFail($id);

        if ($exoneracion) {
            $exoneracion->delete();
        }

        return redirect()->back()
            ->with('success', 'Eliminación realizada con éxito');
    }
}
