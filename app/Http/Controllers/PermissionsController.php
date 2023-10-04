<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
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
        if (request()->wantsJson())
            try {
                $rowDatas = Permission::orderBy('id', 'asc')
                    ->paginate(
                        $perPage,
                        [
                            "id",
                            "name",
                            "guard_name",
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
        return "The access for get all permissions is just for JSON request";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        if (request()->wantsJson()) {
            try {
                $permission = Permission::create($request->all());
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
                $permission = Permission::findOrFail($id);
                $permission->updateOrFail($request->all());
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
                $permission = Permission::findOrFail($id);
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
            if (!$permissionsID) {
                return response()->json([
                    'severity' => 'error',
                    'summary' => 'Error',
                    'detail' => 'List of permissions is null o undefined.'
                ], 400);
            }
            try {
                Permission::whereIn('id', $permissionsID)->delete();
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
