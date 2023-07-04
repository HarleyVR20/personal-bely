<?php

namespace App\Http\Controllers;

use App\Imports\AsistenciasImport;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use Illuminate\Http\Response;

/**
 * Class AsistenciaController
 * @package App\Http\Controllers
 */
class AsistenciaController extends Controller
{
    /**
     * Display the index page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $asistenciaId = 0;
        $columns = [
            'id',
            'empleado_id',
            'empleado',
            'area_id',
            'área',
            'día',
            'hora entrada',
            'hora salida',
            'estado',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.asistencia', compact('asistenciaId', 'columns', 'data'));
    }

    /**
     * Get data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $asistencias = Asistencia::all();
                $data = $this->transformAsistencias($asistencias);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transform asistencias collection.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $asistencias
     * @return \Illuminate\Support\Collection
     */
    private function transformAsistencias($asistencias)
    {
        return $asistencias->map(function ($asistencia) {
            return [
                'id' => $asistencia->id,
                'empleado_id' => optional($asistencia->empleado)->id,
                'empleado' => optional($asistencia->empleado)->nombre . ' ' . optional($asistencia->empleado)->apellidos,
                'area_id' => optional($asistencia->area)->id,
                'área' => optional($asistencia->area)->gerencia,
                'día' => $asistencia->dia,
                'hora entrada' => $asistencia->hora_entrada,
                'hora salida' => $asistencia->hora_salida,
                'estado' => $asistencia->estado,
                'creado en' => optional($asistencia->created_at)->toDateTimeString(),
                'actualizado en' => optional($asistencia->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Store a new asistencia.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'i_selectEmpleado' => 'required',
            'i_area' => 'required',
            'i_asistencia' => 'required',
            'i_fecha' => 'required',
        ]);

        // Crear una nueva instancia de Asistencia
        $asistencia = new Asistencia;
        $asistencia->empleado_id = $validatedData['i_selectEmpleado'];
        $asistencia->area_id = $validatedData['i_area'];
        $asistencia->estado = $validatedData['i_asistencia'];
        $asistencia->dia = $validatedData['i_fecha'];

        // Verificar si se seleccionó la asistencia
        if ($validatedData['i_asistencia']) {
            // Validar los datos adicionales
            $validatedData = $request->validate([
                'i_hora_entrada' => 'required',
                'i_hora_salida' => 'required',
            ]);
            $asistencia->hora_entrada = $validatedData['i_hora_entrada'];
            $asistencia->hora_salida = $validatedData['i_hora_salida'];
        }

        // Guardar la asistencia en la base de datos
        $asistencia->save();

        // Redireccionar a la página anterior y mostrar un mensaje de éxito
        return redirect()->back()->with('success', 'Registro realizado con éxito');
    }

    /**
     * Update an existing asistencia.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'e_selectEmpleado' => 'required',
            'e_area' => 'required',
            'e_asistencia' => 'required',
            'e_fecha' => 'required',
        ]);

        // Buscar la asistencia correspondiente al ID proporcionado
        $asistencia = Asistencia::findOrFail($id);

        // Verificar si se encontró la asistencia
        if ($asistencia) {
            // Actualizar los datos de la asistencia
            $asistencia->empleado_id = $validatedData['e_selectEmpleado'];
            $asistencia->area_id = $validatedData['e_area'];
            $asistencia->estado = $validatedData['e_asistencia'];
            $asistencia->dia = $validatedData['e_fecha'];

            // Verificar si se seleccionó la asistencia
            if ($validatedData['e_asistencia']) {
                // Validar los datos adicionales
                $validatedData = $request->validate([
                    'e_hora_entrada' => 'required',
                    'e_hora_salida' => 'required',
                ]);
                $asistencia->hora_entrada = $validatedData['e_hora_entrada'];
                $asistencia->hora_salida = $validatedData['e_hora_salida'];
            }

            // Guardar los cambios en la base de datos
            $asistencia->update();

            // Redireccionar a la página anterior y mostrar un mensaje de éxito
            return redirect()->back()->with('success', 'Actualización realizada con éxito');
        }

        // Redireccionar a la página anterior y mostrar un mensaje de error
        return redirect()->back()->withErrors(['Error al actualizar']);
    }

    /**
     * Delete an asistencia.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Buscar la asistencia correspondiente al ID proporcionado
        $asistencia = Asistencia::findOrFail($id);

        // Verificar si se encontró la asistencia
        if ($asistencia) {
            // Eliminar la asistencia
            $asistencia->delete();
        }

        // Redireccionar a la página anterior y mostrar un mensaje de éxito
        return redirect()->back()->with('success', 'Eliminación realizada con éxito');
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
        $areasImport = new AsistenciasImport();
        $imported = $areasImport->import($filePath);

        if ($imported) {
            // Redireccionar o mostrar mensaje de éxito
            return redirect()->back()->with('success', 'La importación del archivo Excel se realizó correctamente.');
        } else {
            // Redireccionar o mostrar mensaje de error
            return redirect()->back()->withErrors(['Ocurrió un error durante la importación del archivo Excel.']);
        }
    }
}
