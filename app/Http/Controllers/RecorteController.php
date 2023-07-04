<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recorte;
use Illuminate\Http\Response;

class RecorteController extends Controller
{
    /**
     * Muestra la página de índice.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $recorteId = 0;
        $columns = [
            'id',
            'tipo_id',
            'Tipo',
            'Monto',
            'Observaciones',
            'Creado en',
            'Actualizado en',
            'Opciones'
        ];
        $data = [];
        return view('admin.recorte', compact('recorteId', 'columns', 'data'));
    }

    /**
     * Obtiene los datos para la página de índice.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $recortes = Recorte::all();
                $data = $this->transformRecortes($recortes);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transforma los datos de recortes.
     *
     * @param \Illuminate\Database\Eloquent\Collection $recortes
     * @return \Illuminate\Support\Collection
     */
    private function transformRecortes($recortes)
    {
        return $recortes->map(function ($recorte) {
            return [
                'id' => $recorte->id,
                'tipo_id' => optional($recorte->tipo_recorte)->id,
                'Tipo' => optional($recorte->tipo_recorte)->description, //. ' (' . optional($recorte->tipo_recorte)->id . ')'
                'Monto' => $recorte->monto_recorte,
                'Observaciones' => $recorte->observacion,
                'Creado en' => optional($recorte->created_at)->toDateTimeString(),
                'Actualizado en' => optional($recorte->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Almacena los datos de recorte.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_selectTipoRecorte' => 'required',
            'i_monto_recortado' => 'required',
            'i_observaciones' => 'required',
        ]);

        $recorte = Recorte::create([
            'tipo_recorte_id' => $request['i_selectTipoRecorte'],
            'monto_recorte' => $request['i_monto_recortado'],
            'observacion' => $request['i_observaciones'],
        ]);

        return redirect()->back()->with('success', 'Recorte creada con éxito');
    }

    /**
     * Actualiza los datos de recorte.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_selectTipoRecorte' => 'required',
            'e_monto_recortado' => 'required',
            'e_observaciones' => 'required',
        ]);

        $recorte = Recorte::findOrFail($id);
        $recorte->update([
            'tipo_recorte_id' => $request['e_selectTipoRecorte'],
            'monto_recorte' => $request['e_monto_recortado'],
            'observacion' => $request['e_observaciones'],
        ]);

        return redirect()->back()->with('success', 'Recorte actualizada con éxito');
    }

    /**
     * Elimina un recorte.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $recorte = Recorte::findOrFail($id);
        $recorte->delete();

        return redirect()->back()
            ->with('success', 'Eliminación realizada con éxito');
    }

    /**
     * Realiza una búsqueda de recortes.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->input('q');
        try {
            $motivos = Recorte::where(function ($query) use ($term) {
                $query->where('observacion', 'like', '%' . $term . '%')
                    ->orWhereHas('tipo_recorte', function ($query) use ($term) {
                        $query->where('description', 'like', '%' . $term . '%');
                    });
            })->get();

            // Lógica para buscar los datos y devolver los resultados en formato JSON
            $data = [];
            foreach ($motivos as $motivo) {
                $data[] = [
                    'id' => $motivo->id,
                    'text' => $motivo->tipo_recorte->description . ' ' . $motivo->monto_recorte
                ];
            }
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
