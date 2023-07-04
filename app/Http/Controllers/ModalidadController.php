<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modalidad;
use Illuminate\Http\Response;

class ModalidadController extends Controller
{
    /**
     * Display the modalidad index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $modalidadId = 0;
        $columns = [
            'id',
            'nombre de modalidad',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];

        return view('admin.modalidad', compact('modalidadId', 'columns', 'data'));
    }

    /**
     * Get the modalidad data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $modalidades = Modalidad::all();
                $data = $this->transformData($modalidades);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transform the modalidad data.
     *
     * @param \Illuminate\Database\Eloquent\Collection $modalidades
     * @return \Illuminate\Support\Collection
     */
    private function transformData($modalidades)
    {
        return $modalidades->map(function ($modalidad) {
            return [
                'id' => $modalidad->id,
                'nombre de modalidad' => $modalidad->name_mod,
                'creado en' => optional($modalidad->created_at)->toDateTimeString(),
                'actualizado en' => optional($modalidad->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Search for modalidades based on the search term.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->input('modd');
        try {
            $modalidades = Modalidad::where('name_mod', 'like', '%' . $term . '%')->get();
            // Logic to search for data and return results in JSON format

            $data = [];
            foreach ($modalidades as $modalidad) {
                $data[] = [
                    'id' => $modalidad->id,
                    'text' => $modalidad->name_mod,
                ];
                // . ' ' . $modalidad->apellidos
            }

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a new modalidad.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_modalidad' => 'required',
        ]);

        $area = Modalidad::create([
            'name_mod' => $validatedData['i_modalidad'],
        ]);

        return redirect(route('modalidades'))
            ->with('success', 'Registro realizado con éxito');
    }

    /**
     * Update an existing modalidad.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_modalidad' => 'required|max:255',
        ]);

        $modalidad = Modalidad::findOrFail($id);
        $modalidad->update([
            'name_mod' => $validatedData['e_modalidad'],
        ]);

        return redirect(route('modalidades'))
            ->with('success', 'Actualización realizada con éxito');
    }

    /**
     * Delete a modalidad.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        $modalidad->delete();
        return redirect(route('modalidades'))
            ->with('success', 'Eliminación realizada con éxito');
    }
}
