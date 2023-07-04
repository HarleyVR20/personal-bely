<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Http\Response;

class EmpleadoController extends Controller
{
    /**
     * Display the employee index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $empleadoId = 0;
        $columns = [
            'id',
            'nombre',
            'apellidos',
            'dni',
            'fecha nacimiento',
            'domicilio fiscal',
            'número de celular',
            'correo',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];

        return view('admin.empleado', compact('empleadoId', 'columns', 'data'));
    }

    /**
     * Get the employee data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $empleados = Empleado::all();
                $data = $this->transformEmpleados($empleados);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transform the employee data.
     *
     * @param \Illuminate\Database\Eloquent\Collection $empleados
     * @return \Illuminate\Support\Collection
     */
    private function transformEmpleados($empleados)
    {
        return $empleados->map(function ($empleado) {
            return [
                'id' => $empleado->id,
                'nombre' => $empleado->nombre,
                'apellidos' => $empleado->apellidos,
                'dni' => $empleado->dni,
                'fecha nacimiento' => $empleado->fecha_nacimiento,
                'domicilio fiscal' => $empleado->domicilio_fiscal,
                'número de celular' => $empleado->telf,
                'correo' => $empleado->correo,
                'creado en' => optional($empleado->created_at)->toDateTimeString(),
                'actualizado en' => optional($empleado->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Search for employees.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->input('emp');
        try {
            $empleados = Empleado::where(function ($query) use ($term) {
                $query->where('nombre', 'like', '%' . $term . '%')
                    ->orWhere('apellidos', 'like', '%' . $term . '%');
            })->get();

            // Logic to search for data and return results in JSON format

            $data = [];
            foreach ($empleados as $empleado) {
                $data[] = [
                    'id' => $empleado->id,
                    'text' => $empleado->nombre . ' ' . $empleado->apellidos
                ];
            }
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a new employee.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_nombre' => 'required',
            'i_apellidos' => 'required',
            'i_dni' => 'required',
            'i_fnacimiento' => 'required',
            'i_domicilio' => 'required',
            'i_celular' => 'required',
            'i_correo' => 'required',
        ]);
        $fnacimiento = date("Y-m-d", strtotime($request->i_fnacimiento));
        $empleado = Empleado::create([
            'nombre' => $validatedData['i_nombre'],
            'apellidos' => $validatedData['i_apellidos'],
            'dni' => $validatedData['i_dni'],
            'fecha_nacimiento' => $fnacimiento,
            'domicilio_fiscal' => $validatedData['i_domicilio'],
            'telf' => $validatedData['i_celular'],
            'correo' => $validatedData['i_correo'],
        ]);
        return redirect(route('empleados'))->with('success', 'Registro realizado con éxito');
    }

    /**
     * Update an existing employee.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_nombre' => 'required',
            'e_apellidos' => 'required',
            'e_dni' => 'required',
            'e_fnacimiento' => 'required',
            'e_domicilio' => 'required',
            'e_celular' => 'required',
            'e_correo' => 'required',
        ]);
        $empleado = Empleado::findOrFail($id);
        if ($empleado) {
            $fnacimiento = date("Y-m-d", strtotime($request->i_fnacimiento));

            $empleado->update([
                'nombre' => $validatedData['e_nombre'],
                'apellidos' => $validatedData['e_apellidos'],
                'dni' => $validatedData['e_dni'],
                'fecha_nacimiento' => $fnacimiento,
                'domicilio_fiscal' => $validatedData['e_domicilio'],
                'telf' => $validatedData['e_celular'],
                'correo' => $validatedData['e_correo'],
            ]);

            return redirect(route('asistencias'))
                ->with('success', 'Actualización realizada con éxito');
        }
    }

    /**
     * Delete an employee.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the empleado by ID
        $empleado = Empleado::findOrFail($id);
        if ($empleado)
            $empleado->delete(); // Delete the empleado

        // Redirect back with success message
        return response()->json(['message' => 'Empleado eliminado con éxito']);
    }
}
