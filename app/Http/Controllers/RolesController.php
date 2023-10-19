<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize("view", Role::class);
        $perPage = $request->wantsJson() ? 999999999999999999 : 10;
        if (request()->wantsJson()) {
            try {
                $rowDatas = Role::orderBy('id', 'asc')
                    ->with('permissions:id,name,guard_name,description,tags')
                    ->paginate(
                        $perPage,
                        [
                            "id",
                            "name",
                            "guard_name",
                            "description",
                            "tags",
                        ],
                        "roles_page"
                    );
                $rowDatas->transform(function ($role) {
                    $role->tags = $role->tags === "" ? [] : explode(', ', $role->tags);
                    // $role->permissions = $this->getRolePermissions($role->id);
                    return $role;
                });

                return response()->json($rowDatas, 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get All Roles",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }

        return "The access for get all roles is just for JSON request";
    }

    /**
     * Display the specified resource.
     */
    /* public function getRolePermissions($roleId)
    {
        if (request()->wantsJson()) {
            try {
                $role = Role::findOrFail($roleId);
                $permissions = $role->permissions()
                    ->select('id', 'name', 'guard_name', 'description', 'tags')
                    ->get();
                return $permissions;
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get Permissions for the Role",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for get permissions for the role is just for JSON request";
    } */

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        if (request()->wantsJson()) {
            try {
                $permissions = $request->input('permissions', []);
                if ($request->has('tags') && is_array($request->tags)) {
                    $tagsToString = implode(', ', $request->tags);
                    $request->merge(['tags' => $tagsToString]);
                    $role = Role::create($request->all());
                } else {
                    $role = Role::create($request->all());
                }

                $role->syncPermissions(array_column($permissions, 'name'));

                return response()->json([
                    "severity" => "success",
                    "summary" => "Successful",
                    "detail" => "Role " . $role->name . " was created perfectly."
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in create Role",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }

        return "The access for store roles is just for JSON request";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        if (request()->wantsJson()) {
            try {
                $permissions = $request->input('permissions', []);
                if ($request->has('tags')) {
                    $tagsToString = implode(', ', $request->tags);
                    $request->merge(['tags' => $tagsToString]);
                    $role = Role::findOrFail($id);
                    $role->updateOrFail($request->all());
                    $role->save();
                } elseif (empty($request->tags)) {
                    $request->merge(['tags' => null]);
                    $role = Role::findOrFail($id);
                    $role->updateOrFail($request->all());
                    $role->save();
                }
                if (empty($permissions))
                    $role->permissions()->detach();
                else
                    $role->syncPermissions(array_column($permissions, 'name'));

                return response()->json([
                    "severity" => "info",
                    "summary" => "Successful",
                    "detail" => "Role " . $role->name . " was updated perfectly."
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in update Role",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }

        return "The access for update roles is just for JSON request";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (request()->wantsJson()) {
            try {
                $role = Role::findOrFail($id);
                if (auth()->check()) { //Por el momento este if se usa por que aun no manejo los roles e inicios de sesión
                    $user = auth()->user();
                    if ($user->hasRole($role->name))
                        return response()->json([
                            "severity" => "error",
                            "summary" => "Error",
                            "detail" => $role->name . ", You cannot delete a user with the same role."
                        ], 422);
                }

                $usersWithRoles = User::whereHas('roles', function ($query) use ($id) {
                    $query->where('id', $id);
                })->count();
                if ($usersWithRoles > 0) {
                    return response()->json([
                        "severity" => "error",
                        "summary" => "Error",
                        "detail" => "This role is in use and cannot be deleted.",
                        "errors" => "Role in Use"
                    ], 422);
                }

                $role->syncPermissions([]);
                $role->deleteOrFail();

                return response()->json([
                    "severity" => "warn",
                    "summary" => "Warning",
                    "detail" => "Role " . $role->name . " was deleted perfectly."
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in delete Role",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }

        return "The access for destroy roles is just for JSON request";
    }

    public function destroyMany(Request $request)
    {
        if (request()->wantsJson()) {
            $rolesID = $request->all();
            $rolesCount = count($rolesID);
            if (!$rolesID) {
                return response()->json([
                    'severity' => 'error',
                    'summary' => 'Error',
                    'detail' => 'List of roles is null o undefined.'
                ], 400);
            }

            try {
                Role::whereIn('id', $rolesID)->each(function ($role) {
                    $role->syncPermissions([]);
                });
                Role::whereIn('id', $rolesID)->delete();
                return response()->json([
                    "severity" => "warn",
                    "summary" => "Warning",
                    'detail' => $rolesCount . ' Roles Deleted.'
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'severity' => 'error',
                    'summary' => 'Error',
                    'detail' => 'Error to Delete Roles.',
                    'errors' => $th->getMessage()
                ], 422);
            }
        }

        return "The access for destroy many roles is just for JSON request";
    }

    public function getCurrentRoleId()
    {
        if (request()->wantsJson()) {
            try {
                $nextId = DB::table('roles')->max('id');

                return response()->json([
                    "severity" => "success",
                    "summary" => "Successful",
                    "detail" => "Next role id was get perfectly.",
                    "nextId" => $nextId
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get next role id",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }

        return "The access for get next roles id is just for JSON request";
    }
}