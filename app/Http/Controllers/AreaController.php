<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Http\Response;
use App\Imports\AreasImport;

/**
 * Clase AreaController
 * @package App\Http\Controllers
 */
class AreaController extends Controller
{
    /**
     * Mostrar la página de índice.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Establecer valores iniciales
        $areaId = 0;
        $columns = [
            'id',
            'gerencia',
            'sub area',
            'creado en',
            'actualizado en',
            'opciones'
        ];

        $data = [];

        // Renderizar la vista 'admin.area' con los datos necesarios
        return view('admin.area', compact('areaId', 'columns', 'data'));
    }

    /**
     * Obtener datos para DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                // Obtener todas las áreas
                $areas = Area::all();

                // Transformar las áreas en el formato necesario para DataTables
                $data = $this->transformAreas($areas);

                // Devolver los datos como una respuesta JSON exitosa
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Solicitud inválida.');
            }
        } catch (\Exception $e) {
            // Devolver un mensaje de error en caso de excepción
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transformar la colección de áreas.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $areas
     * @return \Illuminate\Support\Collection
     */
    private function transformAreas($areas)
    {
        return $areas->map(function ($area) {
            return [
                'id' => $area->id,
                'gerencia' => $area->gerencia,
                'sub area' => $area->sub_area,
                'creado en' => optional($area->created_at)->toDateTimeString(),
                'actualizado en' => optional($area->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Buscar áreas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->input('q');

        try {
            // Buscar áreas que coincidan con el término de búsqueda
            $areas = Area::where(function ($query) use ($term) {
                $query->where('gerencia', 'like', '%' . $term . '%')
                    ->orWhere('sub_area', 'like', '%' . $term . '%');
            })->get();

            $data = [];

            foreach ($areas as $area) {
                // Agregar los datos relevantes de las áreas encontradas
                $data[] = [
                    'id' => $area->id,
                    'text' => $area->gerencia ?? $area->sub_area,
                ];
            }

            // Devolver los datos como una respuesta JSON exitosa
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            // Devolver un mensaje de error en caso de excepción
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Almacenar un nuevo área.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'i_gerencia' => 'required|max:100',
            'i_sub_area' => 'required|max:100',
        ]);

        // Crear un nuevo registro de área
        $area = Area::create([
            'gerencia' => $validatedData['i_gerencia'],
            'sub_area' => $validatedData['i_sub_area']
        ]);

        // Redireccionar a la página 'areas' y mostrar un mensaje de éxito
        return redirect()->back()->with('success', 'Registro realizado con éxito');
    }

    /**
     * Actualizar un área existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'e_gerencia' => 'required|max:100',
            'e_sub_area' => 'required|max:100',
        ]);

        // Buscar el área correspondiente al ID proporcionado
        $area = Area::findOrFail($id);

        if ($area) {
            // Actualizar los campos de área
            $area->gerencia = $validatedData['e_gerencia'];
            $area->sub_area = $validatedData['e_sub_area'];
            $area->save();


            // Redireccionar a la página anterior y mostrar un mensaje de éxito
            return redirect()->back()
                ->with('success', 'Actualización realizada con éxito');
        }

        // Redireccionar a la página anterior y mostrar un mensaje de error
        return redirect()->back()
            ->with('errors', 'Error al actualizar');
    }

    /**
     * Eliminar un área.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Buscar el área correspondiente al ID proporcionado
        $area = Area::findOrFail($id);

        // Verificar si se encontró el área
        if ($area) {
            // Eliminar el área
            $area->delete();

            return redirect()->back()
                ->with('success', 'Eliminación realizada con éxito');
        }
        return redirect()->back()->with('errors', 'El campo "gerencia" está vacío. No se puede eliminar el área.');
        // Redireccionar a la página anterior y mostrar un mensaje de éxito
    }

    /**
     * Importar áreas desde un archivo Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        // Obtener la ruta temporal del archivo subido
        $filePath = $request->file('excel_file')->getRealPath();

        // Instanciar la clase de importación y llamar al método import
        $areasImport = new AreasImport();
        $imported = $areasImport->import($filePath);

        if ($imported) {
            // Redireccionar o mostrar mensaje de éxito
            return redirect()->back()->with('success', 'La importación del archivo Excel se realizó correctamente.');
        } else {
            // Redireccionar o mostrar mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error durante la importación del archivo Excel.');
        }
    }
}
