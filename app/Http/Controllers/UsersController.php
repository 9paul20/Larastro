<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize("view", User::class);
        $perPage = $request->wantsJson() ? 999999999999999999 : 10;
        $rowDatas = User::paginate(
            $perPage,
            [
                "id",
                "name",
                "email",
            ],
            "users_page"
        );
        if (request()->wantsJson())
            try {
                return response()->json($rowDatas, 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get All Users",
                    "errors" => $th->getMessage()
                ], 422);
            }
        return "The access for get all users is just for JSON request";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        if (request()->wantsJson()) {
            try {
                $user = User::create($request->all());
                return response()->json([
                    "severity" => "success",
                    "summary" => "Successful",
                    "detail" => "User " . $user->name . " was created perfectly."
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in create User",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for store user is just for JSON request";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        if (request()->wantsJson()) {
            try {
                $user = User::findOrFail($id);
                $user->updateOrFail($request->all());
                return response()->json([
                    "severity" => "info",
                    "summary" => "Successful",
                    "detail" => "User " . $user->name . " was updated perfectly."
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in update User",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for update user is just for JSON request";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (request()->wantsJson()) {
            try {
                $user = User::findOrFail($id);
                if (auth()->check()) { //Por el momento este if se usa por que aun no manejo los roles e inicios de sesión
                    if (auth()->user()->id === $id)
                        return response()->json([
                            "severity" => "error",
                            "summary" => "Error",
                            "detail" => $user->name . ", You cannot delete yourself."
                        ], 422);
                    // if (auth()->user()->id === 1) //Ejemplo de como se puede proteger el rol de admin desde el controlador
                    //     return response()->json([
                    //         "severity" => "error",
                    //         "summary" => "Error",
                    //         "detail" => $user->name . ", You cannot delete the main admin."
                    //     ], 422);
                }
                $user->deleteOrFail();
                return response()->json([
                    "severity" => "warn",
                    "summary" => "Warning",
                    "detail" => "User " . $user->name . " was deleted perfectly."
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in delete User ",
                    "errors" => $e->getMessage()
                ], 422);
            }
        }
        return "The access for destroy user is just for JSON request";
    }

    public function destroyMany(Request $request)
    {
        $userIds = $request->input('user_ids');

        if (!$userIds) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se proporcionaron IDs de usuarios para eliminar.'
            ], 400);
        }

        try {
            // Elimina los usuarios con los IDs proporcionados
            User::whereIn('id', $userIds)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Usuarios eliminados exitosamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar usuarios.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCurrentUserId()
    {
        if (request()->wantsJson()) {
            try {
                $nextId = DB::table('users')->max('id');
                return response()->json([
                    "severity" => "success",
                    "summary" => "Successful",
                    "detail" => "Next user id was get perfectly.",
                    "nextId" => $nextId
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get next user id",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for get next user id is just for JSON request";
    }
}