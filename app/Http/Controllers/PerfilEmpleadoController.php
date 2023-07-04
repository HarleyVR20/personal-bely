<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilEmpleado;
use Illuminate\Http\Response;

class PerfilEmpleadoController extends Controller
{
    /**
     * Display the perfil empleado index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $perfilEmpleadoId = 0;
        $columns = [
            'id',
            'empleado_id',
            'empleado',
            'profesión',
            'cuenta bancaria',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.perfil_empleado', compact('perfilEmpleadoId', 'columns', 'data'));
    }

    /**
     * Get the perfil empleado data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $perfil_empleados = PerfilEmpleado::all();
                $data = $this->transformPerfil($perfil_empleados);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transform the perfil empleado data.
     *
     * @param \Illuminate\Database\Eloquent\Collection $perfil_empleados
     * @return \Illuminate\Support\Collection
     */
    private function transformPerfil($perfil_empleados)
    {
        return $perfil_empleados->map(function ($perfil_empleado) {
            return [
                'id' => $perfil_empleado->id,
                'empleado_id'  => $perfil_empleado->empleado->id,
                'empleado' => $perfil_empleado->empleado->nombre . ' ' . $perfil_empleado->empleado->apellidos,
                'profesión' => $perfil_empleado->profesion,
                'cuenta bancaria' => $perfil_empleado->cuenta_bancaria,
                'creado en' => optional($perfil_empleado->created_at)->toDateTimeString(),
                'actualizado en' => optional($perfil_empleado->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Store a new perfil empleado.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_selectEmpleado' => 'required',
            'i_profesion' => 'required',
            'i_cuenta_bancaria' => 'required|numeric|digits_between:10,20',
        ]);

        $perfil_empleado = PerfilEmpleado::create([
            'empleado_id' => $validatedData['i_selectEmpleado'],
            'profesion' => $validatedData['i_profesion'],
            'cuenta_bancaria' => $validatedData['i_cuenta_bancaria'],
        ]);
        return redirect()->back()->with('success', 'Registro realizado con éxito');
    }

    /**
     * Update an existing perfil empleado.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_selectEmpleado' => 'required',
            'e_profesion' => 'required',
            'e_cuenta_bancaria' => 'required|numeric|digits_between:10,20',
        ]);

        $perfil_empleado = PerfilEmpleado::findOrFail($id);

        $perfil_empleado->update([
            'empleado_id' => $validatedData['e_selectEmpleado'],
            'profesion' => $validatedData['e_profesion'],
            'cuenta_bancaria' => $validatedData['e_cuenta_bancaria'],
        ]);
        return redirect()->back()->with('success', 'Actualización realizado con éxito');
    }

    /**
     * Delete a perfil empleado.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $perfil_empleado = PerfilEmpleado::findOrFail($id);
        $perfil_empleado->delete();
        return redirect()->back()->with('success', 'PerfilEmpleado eliminada con éxito');
    }
}
