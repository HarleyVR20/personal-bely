<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MotivoExoneracion;
use Illuminate\Http\Response;

class MotivoExoneracionController extends Controller
{
    /**
     * Display the motivo exoneracion index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $motivoExoneracionId = 0;
        $columns = [
            'id',
            'descipción',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.motivo_exoneracion', compact('motivoExoneracionId', 'columns', 'data'));
    }

    /**
     * Get the motivo exoneracion data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $motivo_exoneraciones = MotivoExoneracion::all();
                $data = $this->transformMotivo($motivo_exoneraciones);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transform the motivo exoneracion data.
     *
     * @param \Illuminate\Database\Eloquent\Collection $motivo_exoneraciones
     * @return \Illuminate\Support\Collection
     */
    private function transformMotivo($motivo_exoneraciones)
    {
        return $motivo_exoneraciones->map(function ($motivo_exoneracion) {
            return [
                'id' => $motivo_exoneracion->id,
                'descripción' => $motivo_exoneracion->description,
                'creado en' => optional($motivo_exoneracion->created_at)->toDateTimeString(),
                'actualizado en' => optional($motivo_exoneracion->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Search for motivos based on the search term.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->input('q');
        try {
            $motivos = MotivoExoneracion::where('description', 'like', '%' . $term . '%')->get();
            // Logic to search for data and return results in JSON format
            $data = [];
            foreach ($motivos as $motivo) {
                $data[] = [
                    'id' => $motivo->id,
                    'text' => $motivo->description
                ];
            }
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a new motivo exoneracion.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_descripcion' => 'required',
        ]);

        $motivo_exoneracion = MotivoExoneracion::create([
            'description' => $validatedData['i_descripcion'],
        ]);

        return redirect(route('motivo-exoneraciones'))
            ->with('success', 'Registro realizado con éxito');
    }

    /**
     * Update an existing motivo exoneracion.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_descripcion' => 'required',
        ]);
        $motivo_exoneracion = MotivoExoneracion::findOrFail($id);

        $motivo_exoneracion->update([
            'description' => $validatedData['e_descripcion'],
        ]);
        return redirect(route('motivo-exoneraciones'))
            ->with('success', 'Actualización realizada con éxito');
    }

    /**
     * Delete a motivo exoneracion.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $motivo_exoneracion = MotivoExoneracion::findOrFail($id);
        $motivo_exoneracion->delete();

        return redirect(route('motivo-exoneraciones'))
            ->with('success', 'Eliminación realizada con éxito');
    }
}
