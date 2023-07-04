<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrato;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ContratoController extends Controller
{
    /**
     * Display the contratos index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $contratoId = 0;
        $columns = [
            'id',
            'empleado_id',
            'empleado',
            'tipoContrato_id',
            'tipo de contrato',
            'modalidad_id',
            'modalidad',
            'fecha de vinculación',
            'fecha de retiro',
            'dias laborables',
            'horario de entrada',
            'horario de salida',
            'salario base',
            'marco legal',
            'observacion',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.contrato', compact('contratoId', 'columns', 'data'));
    }

    /**
     * Get the contratos data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $contratos = Contrato::all();
                $data = $this->transformContratos($contratos);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transform the contratos data.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $contratos
     * @return \Illuminate\Support\Collection
     */
    private function transformContratos($contratos)
    {
        return $contratos->map(function ($contrato) {
            $diasLaborables = json_decode($contrato->dias_laborales);
            $diasLaborablesString = implode(', ', $diasLaborables);
            return [
                'id' => $contrato->id,
                'empleado_id'  => optional($contrato->empleado)->id,
                'empleado' => optional($contrato->empleado)->nombre . ' ' . optional($contrato->empleado)->apellidos,
                'tipoContrato_id'  => optional($contrato->tipoContrato)->id,
                'tipo de contrato' => optional($contrato->tipoContrato)->tipo,
                'modalidad_id'   => optional($contrato->modalidad)->id,
                'modalidad' => optional($contrato->modalidad)->name_mod,
                'fecha de vinculación' => $contrato->fecha_vinculacion,
                'fecha de retiro' => $contrato->fecha_retiro,
                'dias laborables' => $diasLaborablesString,
                'horario de entrada' => $contrato->horario_entrada,
                'horario de salida' => $contrato->horario_salida,
                'salario base' => $contrato->salario_base,
                'marco legal' => $contrato->marco_legal,
                'observacion' => $contrato->observacion,
                'creado en' => optional($contrato->created_at)->toDateTimeString(),
                'actualizado en' => optional($contrato->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Search for contratos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->input('q');
        try {
            $contratos = Contrato::where(function ($query) use ($term) {
                $query->where('marco_legal', 'like', '%' . $term . '%')
                    ->orWhere('observacion', 'like', '%' . $term . '%');
            })->get();

            // Logic to search for data and return results in JSON format

            $data = [];
            foreach ($contratos as $contrato) {
                $data[] = [
                    'id' => $contrato->id,
                    'text' => $contrato->marco_legal . ' - ' . $contrato->observacion
                ];
                // . ' ' . $contrato->apellidos
            }
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a new contrato.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_selectEmpleado' => 'required',
            'i_selectTipoContrato' => 'required',
            'i_selectModalidad' => 'required',
            'i_fvinculacion' => 'required|date',
            'i_fretiro' => 'required|date|after_or_equal:i_fvinculacion',
            'i_dias_laborables' => 'required',
            'i_horario_entrada' => 'required',
            'i_horario_salida' => 'required|after:i_horario_entrada',
            'i_salario_base' => 'required|numeric|min:1',
            'i_marco_legal' => 'required',
            'i_observacion' => 'required',
        ]);

        $fVinculacion = date("y-m-d", strtotime($request->i_fvinculacion));
        $fRetiro = date("y-m-d", strtotime($request->i_fretiro));

        $contrato = Contrato::create([
            'empleado_id' => $validatedData['i_selectEmpleado'],
            'tipo_contrato_id' => $validatedData['i_selectTipoContrato'],
            'modalidad_id' => $validatedData['i_selectModalidad'],
            'fecha_vinculacion' => $fVinculacion,
            'fecha_retiro' => $fRetiro,
            'dias_laborales' => json_encode($validatedData['i_dias_laborables']),
            'horario_entrada' => $validatedData['i_horario_entrada'],
            'horario_salida' => $validatedData['i_horario_salida'],
            'salario_base' => $validatedData['i_salario_base'],
            'marco_legal' => $validatedData['i_marco_legal'],
            'observacion' => $validatedData['i_observacion'],
        ]);

        return redirect()->back()->with('success', 'Registro realizado con éxito');
    }

    /**
     * Update an existing contrato.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_selectEmpleado' => 'required',
            'e_selectTipoContrato' => 'required',
            'e_selectModalidad' => 'required',
            'e_fvinculacion' => 'required|date',
            'e_fretiro' => 'required|date|after_or_equal:i_fvinculacion',
            'e_dias_laborables' => 'required',
            'e_horario_entrada' => 'required',
            'e_horario_salida' => 'required|after:i_horario_entrada',
            'e_salario_base' => 'required|numeric|min:1',
            'e_marco_legal' => 'required',
            'e_observacion' => 'required',
        ]);

        $fVinculacion = date("y-m-d", strtotime($request->e_fvinculacion));
        $fRetiro = date("y-m-d", strtotime($request->e_fretiro));

        $contrato = Contrato::findOrFail($id);

        if ($contrato) {
            $contrato->update([
                'empleado_id' => $validatedData['e_selectEmpleado'],
                'tipo_contrato_id' => $validatedData['e_selectTipoContrato'],
                'modalidad_id' => $validatedData['e_selectModalidad'],
                'fecha_vinculacion' => $fVinculacion,
                'fecha_retiro' => $fRetiro,
                'dias_laborales' => json_encode($validatedData['e_dias_laborables']),
                'horario_entrada' => $validatedData['e_horario_entrada'],
                'horario_salida' => $validatedData['e_horario_salida'],
                'salario_base' => $validatedData['e_salario_base'],
                'marco_legal' => $validatedData['e_marco_legal'],
                'observacion' => $validatedData['e_observacion'],
            ]);
            return redirect()->back()->with('success', 'Actualización realizada con éxito');
        }
        return redirect()->back()->with('errors', 'Error al actualizar');
    }

    /**
     * Delete a contrato.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $contrato = Contrato::findOrFail($id);

        if ($contrato) {
            $contrato->delete();
        }

        return redirect()->back()->with('success', 'Eliminación realizada con éxito');
    }
}
