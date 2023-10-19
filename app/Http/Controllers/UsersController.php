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
        if (request()->wantsJson()) {
            try {
                $rowDatas = User::orderBy('id', 'asc')
                    ->with([
                        'roles:id,name,guard_name,description,tags',
                        'permissions:id,name,guard_name,description,tags'
                    ])
                    ->paginate(
                        $perPage,
                        [
                            "id",
                            "name",
                            "email",
                        ],
                        "users_page"
                    );
                // $rowDatas->transform(function ($user) {
                //     $user->roles = $user->roles === "" ? [] : explode(', ', $user->roles);
                //     $user->roles = $this->getUserRoles($user->id);
                //     $user->permissions = $this->getUserPermissions($user->id);
                //     return $user;
                // });
                return response()->json($rowDatas, 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get All Users",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for get all users is just for JSON request";
    }

    /**
     * Display the specified resource.
     */
    /* public function getUserRoles($userId)
    {
        if (request()->wantsJson()) {
            try {
                $user = User::findOrFail($userId);
                $roles = $user->roles()
                    ->select('id', 'name', 'guard_name', 'description', 'tags')
                    ->get();
                return $roles;
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get Roles for the User",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for get roles for the user is just for JSON request";
    } */

    /**
     * Display the specified resource.
     */
    /* public function getUserPermissions($userId)
    {
        if (request()->wantsJson()) {
            try {
                $user = User::findOrFail($userId);
                $roles = $user->permissions()
                    ->select('id', 'name', 'guard_name', 'description', 'tags')
                    ->get();
                return $roles;
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get Permissions for the User",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for get permissions for the user is just for JSON request";
    } */

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        if (request()->wantsJson()) {
            try {
                $roles = $request->input('roles', []);
                $permissions = $request->input('permissions', []);

                $user = User::create($request->all());

                if (!empty($roles))
                    $user->syncRoles(array_column($roles, 'name'));

                if (!empty($permissions))
                    $user->syncPermissions(array_column($permissions, 'name'));

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
                $roles = $request->input('roles', []);
                $permissions = $request->input('permissions', []);

                $user = User::findOrFail($id);
                $user->updateOrFail($request->all());

                if (empty($roles))
                    $user->permissions()->detach();
                else
                    $user->syncRoles(array_column($roles, 'name'));

                if (empty($permissions))
                    $user->permissions()->detach();
                else
                    $user->syncPermissions(array_column($permissions, 'name'));

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
                    $user = auth()->user();
                    if ($user->id === $id)
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
                $user->syncRoles([]);
                $user->syncPermissions([]);
                $user->deleteOrFail();
                return response()->json([
                    "severity" => "warn",
                    "summary" => "Warning",
                    "detail" => "User " . $user->name . " was deleted perfectly."
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in delete User",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for destroy user is just for JSON request";
    }

    public function destroyMany(Request $request)
    {
        if (request()->wantsJson()) {
            $usersID = $request->all();
            $usersCount = count($usersID);
            if (!$usersID) {
                return response()->json([
                    'severity' => 'error',
                    'summary' => 'Error',
                    'detail' => 'List of users is null o undefined.'
                ], 400);
            }
            try {
                User::whereIn('id', $usersID)->each(function ($user) {
                    $user->syncPermissions([]);
                    $user->syncRoles([]);
                });
                User::whereIn('id', $usersID)->delete();
                return response()->json([
                    "severity" => "warn",
                    "summary" => "Warning",
                    'detail' => $usersCount . ' Users Deleted.'
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'severity' => 'error',
                    'summary' => 'Error',
                    'detail' => 'Error to Delete Users.',
                    'errors' => $th->getMessage()
                ], 422);
            }
        }
        return "The access for destroy many users is just for JSON request";
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