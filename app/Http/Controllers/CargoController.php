<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cargo;
use Illuminate\Http\Response;

/**
 * Class CargoController
 * @package App\Http\Controllers
 */
class CargoController extends Controller
{
    /**
     * Display the cargos index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $cargoId = 0;
        $columns = [
            'id',
            'area_id',
            'área',
            'nombre',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.cargo', compact('cargoId', 'columns', 'data'));
    }

    /**
     * Get the cargos data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $cargos = Cargo::all();
                $data = $this->transformCargos($cargos);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transform the cargos data.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $cargos
     * @return \Illuminate\Support\Collection
     */
    private function transformCargos($cargos)
    {
        return $cargos->map(function ($cargo) {
            return [
                'id' => $cargo->id,
                'area_id' => optional($cargo->area)->id,
                'área' => optional($cargo->area)->gerencia . ' - ' . optional($cargo->area)->sub_gerencia,
                'nombre' => $cargo->nombre,
                'creado en' => optional($cargo->created_at)->toDateTimeString(),
                'actualizado en' => optional($cargo->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Store a new cargo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_area' => 'required',
            'i_cargo' => 'required',
        ]);

        // Create a new cargo
        $cargo = Cargo::create([
            'area_id' => $validatedData['i_area'],
            'nombre' => $validatedData['i_cargo'],
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Registro realizado con éxito');
    }

    /**
     * Update an existing cargo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_area' => 'required',
            'e_cargo' => 'required|max:255',
        ]);

        // Find the cargo by ID
        $cargo = Cargo::findOrFail($id);
        if ($cargo) {

            // Update the cargo data
            $cargo->update([
                'area_id' => $validatedData['e_area'],
                'nombre' => $validatedData['e_cargo'],
            ]);

            // Redirect to cargos index with success message
            return redirect(route('cargos'))->with('success', 'Actualización realizada con éxito');
        }

        // Redirect back with error message
        return redirect()->back()->with('errors', 'Error al actualizar');
    }

    /**
     * Delete a cargo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Find the cargo by ID
        $cargo = Cargo::findOrFail($id);
        if ($cargo) {
            // Delete the cargo
            $cargo->delete();
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Eliminación realizada con éxito');
    }
}
