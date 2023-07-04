<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoRecorte;
use Illuminate\Http\Response;

class TipoRecorteController extends Controller
{
    /**
     * Muestra la vista principal para la gestión de tipos de recorte.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $tipoRecorteId = 0;
        $columns = [
            'id',
            'tipo',
            'descripcion',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.tipo_recorte', compact('tipoRecorteId', 'columns', 'data'));
    }

    /**
     * Obtiene los datos de los tipos de recorte en formato JSON.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud HTTP.
     * @return \Illuminate\Http\JsonResponse Datos de los tipos de recorte en formato JSON.
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $tipo_recortes = TipoRecorte::all();
                $data = $this->transformTipoRecorte($tipo_recortes);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transforma los datos de los tipos de recorte en el formato deseado.
     *
     * @param \Illuminate\Database\Eloquent\Collection $tipo_recortes Colección de tipos de recorte.
     * @return array Datos de tipos de recorte transformados.
     */
    private function transformTipoRecorte($tipo_recortes)
    {
        return $tipo_recortes->map(function ($tipo_recorte) {
            return [
                'id' => $tipo_recorte->id,
                'descripcion' => $tipo_recorte->description,
                'tipo' => $tipo_recorte->tipo === 0 ? 'Recorte' : 'Bonificación',
                'creado en' => optional($tipo_recorte->created_at)->toDateTimeString(),
                'actualizado en' => optional($tipo_recorte->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Realiza una búsqueda de tipos de recorte y devuelve los resultados en formato JSON.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud HTTP.
     * @return \Illuminate\Http\JsonResponse Resultados de búsqueda de tipos de recorte en formato JSON.
     */
    public function search(Request $request)
    {
        $term = $request->input('q');
        try {
            $motivos = TipoRecorte::where('description', 'like', '%' . $term . '%')->get();
            // Lógica para buscar los datos y devolver los resultados en formato JSON
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
     * Almacena un nuevo tipo de recorte en la base de datos.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirección después de almacenar el tipo de recorte.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_tipo' => 'required',
            'i_descripcion' => 'required',
        ]);

        $tipo_recorte = TipoRecorte::create([
            'description' => $validatedData['i_descripcion'],
            'tipo' => $validatedData['i_tipo'],
        ]);
        return redirect()->back()->with('success', 'Registro realizado con éxito');
    }

    /**
     * Actualiza un tipo de recorte existente en la base de datos.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud HTTP.
     * @param int $id ID del tipo de recorte a actualizar.
     * @return \Illuminate\Http\RedirectResponse Redirección después de actualizar el tipo de recorte.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_tipo' => 'required',
            'e_descripcion' => 'required',
        ]);

        $tipo_recorte = TipoRecorte::findOrFail($id);

        $tipo_recorte->update([
            'description' => $validatedData['e_descripcion'],
            'tipo' => $validatedData['e_tipo'],
        ]);
        return redirect()->back()
            ->with('success', 'Actualización realizada con éxito');
    }

    /**
     * Elimina un tipo de recorte de la base de datos.
     *
     * @param int $id ID del tipo de recorte a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirección después de eliminar el tipo de recorte.
     */
    public function destroy($id)
    {
        $tipo_recorte = TipoRecorte::findOrFail($id);
        $tipo_recorte->delete();

        return redirect()->back()->with('success', 'Eliminación realizada con éxito.');
    }
}
