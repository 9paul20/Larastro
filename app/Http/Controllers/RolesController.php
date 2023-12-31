<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\CustomRole;
use App\Models\User;
use Illuminate\Http\Request;
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
                $rowDatas = CustomRole::orderBy('id', 'asc')
                    ->with(
                        'permissions:id,name',
                        'tags:id,name'
                    )
                    ->paginate(
                        $perPage,
                        [
                            "id",
                            "name",
                            "guard_name",
                            "description",
                        ],
                        "roles_page"
                    );

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
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        if (request()->wantsJson()) {
            try {
                $permissions = $request->input('permissions', []);
                $tags = $request->input('tags', []);
                if ($tags && is_array($tags)) {
                    $tagIds = collect($tags)->pluck('id')->toArray();
                    unset($request['tags']);
                    $role = CustomRole::create($request->all());
                    $role->tags()->attach($tagIds);
                } else {
                    $role = CustomRole::create($request->all());
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
                $tags = $request->input('tags', []);
                $tagIds = collect($tags)->pluck('id')->toArray();
                unset($request['tags']);
                $role = CustomRole::findOrFail($id);
                $role->updateOrFail($request->all());
                $role->save();
                $role->tags()->sync($tagIds);

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
                $role = CustomRole::findOrFail($id);
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
                $role->tags()->detach();
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
            if (auth()->check()) {
                $user = auth()->user();
                $rolesInUse = $user->roles()->whereIn('id', $rolesID)->exists();
                if ($rolesInUse) {
                    return response()->json([
                        'severity' => 'error',
                        'summary' => 'Error',
                        'detail' => 'One or more roles are in use by the authenticated user.'
                    ], 400);
                }
            }

            $usersWithRoles = User::whereHas('roles', function ($query) use ($rolesID) {
                $query->whereIn('id', $rolesID);
            })->exists();

            if ($usersWithRoles) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "This roles is in use and cannot be deleted.",
                    "errors" => "Roles In Use"
                ], 422);
            }


            if (!$rolesID) {
                return response()->json([
                    'severity' => 'error',
                    'summary' => 'Error',
                    'detail' => 'List of roles is null o undefined.'
                ], 400);
            }

            try {
                $roleIDs = CustomRole::whereIn('id', $rolesID)->pluck('id')->toArray();
                CustomRole::whereIn('id', $rolesID)->delete();
                DB::table('model_has_tags')
                    ->whereIn('model_id', $roleIDs)
                    ->where('model_type', CustomRole::class)
                    ->delete();
                CustomRole::whereIn('id', $rolesID)->each(function ($role) {
                    $role->syncPermissions([]);
                });
                CustomRole::whereIn('id', $rolesID)->delete();
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