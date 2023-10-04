<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
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
        if (request()->wantsJson())
            try {
                $rowDatas = Role::orderBy('id', 'asc')
                    ->paginate(
                        $perPage,
                        [
                            "id",
                            "name",
                            "guard_name",
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
        return "The access for get all roles is just for JSON request";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        if (request()->wantsJson()) {
            try {
                $role = Role::create($request->all());
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
                $role = Role::findOrFail($id);
                $role->updateOrFail($request->all());
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