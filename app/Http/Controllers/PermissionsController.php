<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Models\CustomPermission;
use App\Models\CustomRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionsController extends Controller
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
                $rowDatas = CustomPermission::orderBy('id', 'asc')
                    ->with(
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
                        "permissions_page"
                    );

                return response()->json($rowDatas, 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get All Permissions",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }

        return "The access for get all permissions is just for JSON request";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        if (request()->wantsJson()) {
            try {
                $tags = $request->input('tags', []);
                if ($tags && is_array($tags)) {
                    $tagIds = collect($tags)->pluck('id')->toArray();
                    unset($request['tags']);
                    $permission = CustomPermission::create($request->all());
                    $permission->tags()->attach($tagIds);
                } else {
                    $permission = CustomPermission::create($request->all());
                }

                return response()->json([
                    "severity" => "success",
                    "summary" => "Successful",
                    "detail" => "Permission " . $permission->name . " was created perfectly."
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in create Permission",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }

        return "The access for store permissions is just for JSON request";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, string $id)
    {
        if (request()->wantsJson()) {
            try {
                $tags = $request->input('tags', []);
                $tagIds = collect($tags)->pluck('id')->toArray();
                unset($request['tags']);
                $permission = CustomPermission::findOrFail($id);
                $permission->updateOrFail($request->all());
                $permission->save();
                $permission->tags()->sync($tagIds);

                return response()->json([
                    "severity" => "info",
                    "summary" => "Successful",
                    "detail" => "Permission " . $permission->name . " was updated perfectly."
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in update Permission",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }

        return "The access for update permissions is just for JSON request";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (request()->wantsJson()) {
            try {
                $permission = CustomPermission::findOrFail($id);

                if (auth()->check()) {
                    $user = auth()->user();

                    if ($user->hasPermissionTo($permission->name)) {
                        return response()->json([
                            "severity" => "error",
                            "summary" => "Error",
                            "detail" => "You cannot delete a permission you possess directly."
                        ], 422);
                    }
                    $rolesWithPermission = $user->getRoleNames()->filter(function ($role) use ($permission) {
                        return $role->hasPermissionTo($permission->name);
                    });

                    if (!$rolesWithPermission->isEmpty()) {
                        return response()->json([
                            "severity" => "error",
                            "summary" => "Error",
                            "detail" => "You cannot delete a permission associated with your roles."
                        ], 422);
                    }
                }

                $rolesWithPermission = CustomRole::whereHas('permissions', function ($query) use ($id) {
                    $query->where('id', $id);
                })->count();
                $usersWithPermission = User::whereHas('permissions', function ($query) use ($id) {
                    $query->where('id', $id);
                })->count();
                if ($rolesWithPermission > 0 || $usersWithPermission > 0) {
                    return response()->json([
                        "severity" => "error",
                        "summary" => "Error",
                        "detail" => "This permission is in use and cannot be deleted.",
                        "errors" => "Permission In Use"
                    ], 422);
                }

                $permission->tags()->detach();
                $permission->deleteOrFail();

                return response()->json([
                    "severity" => "warn",
                    "summary" => "Warning",
                    "detail" => "Permission " . $permission->name . " was deleted perfectly."
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in delete Permission",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }

        return "The access for destroy permissions is just for JSON request";
    }

    public function destroyMany(Request $request)
    {
        if (request()->wantsJson()) {
            $permissionsID = $request->all();
            $permissionsCount = count($permissionsID);
            if (auth()->check()) {
                $user = auth()->user();
                $permissionsInUse = $user->permissions()->whereIn('id', $permissionsID)->exists();
                if ($permissionsInUse) {
                    return response()->json([
                        'severity' => 'error',
                        'summary' => 'Error',
                        'detail' => 'One or more permissions are in use by the authenticated user.'
                    ], 400);
                }

                $rolesWithPermissions = $user->roles()->whereHas('permissions', function ($query) use ($permissionsID) {
                    $query->whereIn('id', $permissionsID);
                })->exists();
                if ($rolesWithPermissions) {
                    return response()->json([
                        'severity' => 'error',
                        'summary' => 'Error',
                        'detail' => 'One or more permissions are associated with roles of the authenticated user.'
                    ], 400);
                }
            }

            $rolesWithPermission = CustomRole::whereHas('permissions', function ($query) use ($permissionsID) {
                $query->whereIn('id', $permissionsID);
            })->exists();

            $usersWithPermissions = User::whereHas('permissions', function ($query) use ($permissionsID) {
                $query->whereIn('id', $permissionsID);
            })->exists();

            if ($rolesWithPermission || $usersWithPermissions) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "This permissions is in use and cannot be deleted.",
                    "errors" => "Permissions In Use"
                ], 422);
            }

            if (!$permissionsID) {
                return response()->json([
                    'severity' => 'error',
                    'summary' => 'Error',
                    'detail' => 'List of permissions is null o undefined.'
                ], 400);
            }

            try {
                $permissionIDs = CustomPermission::whereIn('id', $permissionsID)->pluck('id')->toArray();
                CustomPermission::whereIn('id', $permissionsID)->delete();
                DB::table('model_has_tags')
                    ->whereIn('model_id', $permissionIDs)
                    ->where('model_type', CustomPermission::class)
                    ->delete();

                return response()->json([
                    "severity" => "warn",
                    "summary" => "Warning",
                    'detail' => $permissionsCount . ' Permissions Deleted.'
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'severity' => 'error',
                    'summary' => 'Error',
                    'detail' => 'Error to Delete Permissions.',
                    'errors' => $th->getMessage()
                ], 422);
            }
        }

        return "The access for destroy many permissions is just for JSON request";
    }

    public function getCurrentPermissionId()
    {
        if (request()->wantsJson()) {
            try {
                $nextId = DB::table('permissions')->max('id');

                return response()->json([
                    "severity" => "success",
                    "summary" => "Successful",
                    "detail" => "Next permission id was get perfectly.",
                    "nextId" => $nextId
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get next permission id",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }

        return "The access for get next permissions id is just for JSON request";
    }
}