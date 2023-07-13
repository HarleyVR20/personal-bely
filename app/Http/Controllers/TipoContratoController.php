<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoContrato;
use Illuminate\Http\Response;

class TipoContratoController extends Controller
{
    /**
     * Muestra la vista principal de los tipos de contrato.
     *
     * @return \Illuminate\Contracts\View\View Vista principal de los tipos de contrato.
     */
    public function index()
    {
        $tipoContratoId = 0;
        $columns = [
            'id',
            'tipo de contrato',
            'plazo en meses',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];

        // Renderiza la vista 'admin.tipo_contrato' y pasa las variables tipoContratoId, columns y data a la vista
        return view('admin.tipo_contrato', compact('tipoContratoId', 'columns', 'data'));
    }

    /**
     * Obtiene los datos de los tipos de contrato en formato JSON.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud HTTP.
     * @return \Illuminate\Http\JsonResponse Datos de los tipos de contrato en formato JSON.
     */
    public function getData(Request $request)
    {
        try {
            // Verifica si la solicitud es una solicitud Ajax
            if ($request->ajax()) {
                // Obtiene todos los tipos de contrato
                $tipoContrato = TipoContrato::all();

                // Transforma los datos de los tipos de contrato utilizando el método transformTipocontrato
                $data = $this->transformTipocontrato($tipoContrato);

                // Retorna los datos de los tipos de contrato en formato JSON con el código de respuesta HTTP 200 (OK)
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                // Si la solicitud no es una solicitud Ajax, lanza una excepción indicando una solicitud no válida
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            // Captura cualquier excepción lanzada y retorna un mensaje de error en formato JSON con el código de respuesta HTTP 400 (BAD REQUEST)
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transforma los datos de los tipos de contrato en el formato deseado.
     *
     * @param \Illuminate\Database\Eloquent\Collection $tipoContrato Colección de tipos de contrato.
     * @return array Datos de tipos de contrato transformados.
     */
    private function transformTipocontrato($tipoContrato)
    {
        return $tipoContrato->map(function ($tipo_contrato) {
            return [
                'id' => $tipo_contrato->id,
                'tipo de contrato' => $tipo_contrato->tipo,
                'plazo en meses' => $tipo_contrato->plazo,
                'creado en' => optional($tipo_contrato->created_at)->toDateTimeString(),
                'actualizado en' => optional($tipo_contrato->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Realiza una búsqueda de tipos de contrato según el término de búsqueda proporcionado.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud HTTP.
     * @return \Illuminate\Http\JsonResponse Resultados de búsqueda de tipos de contrato en formato JSON.
     */
    public function search(Request $request)
    {
        // Obtiene el término de búsqueda del campo de entrada 'tipoCon' en la solicitud
        $term = $request->input('tipoCon');

        try {
            // Realiza una búsqueda de tipos de contrato que coincidan con el término de búsqueda
            $tipoContratos = TipoContrato::where('tipo', 'like', '%' . $term . '%')->get();

            $data = [];

            foreach ($tipoContratos as $tipoContrato) {
                // Agrega los resultados de la búsqueda a un array de datos
                $data[] = [
                    'id' => $tipoContrato->id,
                    'text' => $tipoContrato->tipo
                ];
            }

            // Retorna los datos de búsqueda en formato JSON con el código de respuesta HTTP 200 (OK)
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            // Captura cualquier excepción lanzada y devuelve un mensaje de error en formato JSON con el código de respuesta HTTP 400 (BAD REQUEST)
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Almacena un nuevo tipo de contrato en la base de datos.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud HTTP.
     * @return \Illuminate\Http\RedirectResponse Redirección después de almacenar el tipo de contrato.
     */
    public function store(Request $request)
    {
        // Valida los datos recibidos en la solicitud
        $validatedData = $request->validate([
            'i_tipo_contrato' => 'required',
            'i_plazo' => 'required',
        ]);

        // Crea un nuevo objeto TipoContrato con los datos validados
        $tipo_contrato = TipoContrato::create([
            'tipo' => $validatedData['i_tipo_contrato'],
            'plazo en meses' => $validatedData['i_plazo']
        ]);

        // Redirige a la ruta 'tipo-contratos' y muestra un mensaje de éxito
        return redirect(route('tipo-contratos'))->with('success', 'Registro realizado con éxito');
    }

    /**
     * Actualiza un tipo de contrato existente en la base de datos.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud HTTP.
     * @param int $id ID del tipo de contrato a actualizar.
     * @return \Illuminate\Http\RedirectResponse Redirección después de actualizar el tipo de contrato.
     */
    public function update(Request $request, $id)
    {
        // Valida los datos recibidos en la solicitud
        $validatedData = $request->validate([
            'e_tipo_contrato' => 'required',
            'e_plazo' => 'required',
        ]);

        // Busca el objeto TipoContrato por su ID
        $tipo_contrato = TipoContrato::findOrFail($id);

        // Actualiza los campos del objeto TipoContrato con los datos validados
        $tipo_contrato->update([
            'tipo' => $validatedData['e_tipo_contrato'],
            'plazo en meses' => $validatedData['e_plazo']
        ]);

        // Redirige a la ruta 'tipo-contratos' y muestra un mensaje de éxito
        return redirect(route('tipo-contratos'))->with('success', 'Actualización realizada con éxito');
    }

    /**
     * Elimina un tipo de contrato de la base de datos.
     *
     * @param int $id ID del tipo de contrato a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirección después de eliminar el tipo de contrato.
     */
    public function destroy($id)
    {
        // Busca el objeto TipoContrato por su ID
        $tipo_contrato = TipoContrato::findOrFail($id);

        // Elimina el objeto TipoContrato de la base de datos
        $tipo_contrato->delete();

        // Redirige a la ruta 'tipo-contratos' y muestra un mensaje de éxito
        return redirect(route('tipo-contratos'))->with('success', 'Eliminación realizada con éxito');
    }
}
