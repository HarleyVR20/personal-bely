<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Obtiene los datos de los usuarios en formato JSON.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud HTTP.
     * @return \Illuminate\Http\JsonResponse Datos de los usuarios en formato JSON.
     */
    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $usuarios = User::all();
                // throw new \Exception($usuarios);
                $data = $this->transformUsuario($usuarios);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Transforma los datos de los usuarios en el formato deseado.
     *
     * @param \Illuminate\Database\Eloquent\Collection $usuarios Colección de usuarios.
     * @return array Datos de usuarios transformados.
     */
    private function transformUsuario($usuarios)
    {
        return $usuarios->map(function ($usuario) {
            return [
                'id' => $usuario->id,
                'id_area' => optional($usuario->area)->id,
                'Nombre' => $usuario->name,
                'área' => optional($usuario->area)->gerencia,
                'Correo' =>  $usuario->email,
                'creado en' => optional($usuario->created_at)->toDateTimeString(),
                'actualizado en' => optional($usuario->updated_at)->toDateTimeString(),
            ];
        });
    }

    /**
     * Elimina un usuario de la base de datos.
     *
     * @param int $id ID del usuario a eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirección después de eliminar el usuario.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();

        return redirect()->back()->with('success', 'Eliminación realizada con éxito.');
    }
}
