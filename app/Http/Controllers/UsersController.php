<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('view', User::class);

        $perPage = $request->wantsJson() ? 999999999999999999 : 10;
        $rowDatas = User::paginate(
            $perPage,
            [
                'id',
                'name',
                'email',
            ],
            'users_page'
        );
        if (request()->wantsJson())
            return response()->json($rowDatas, 200);
        return "The access is just for JSON request";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::create($validatedData);
        if (!User::create($validatedData))
            return response()->json([
                "status" => "error",
                'message' => 'User ' . $user->name . ' was not created.'
            ], 422);
        elseif (request()->wantsJson()) {
            return response()->json([
                "status" => "success",
                'message' => 'User ' . $user->name . ' was created perfectly.'
            ], 200);
        }
        return "The access is just for JSON request";
        // $validatedData = $request->validated();
        // $user = User::create($validatedData);
        // if (request()->wantsJson()) {
        //     return response()->json([
        //         "status" => "success",
        //         'message' => 'User ' . $user->name . ' was created perfectly.'
        //     ]);
        // }
        // return "The access is just for JSON request";
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UserRequest $request, string $id)
    public function update(UserRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $user = User::findOrFail($id);
        if (!$user->updateOrFail($validatedData)) {
            return response()->json([
                "status" => "error",
                'message' => 'User ' . $user->name . ' was not updated.'
            ], 422);
        } elseif (request()->wantsJson()) {
            return response()->json([
                "status" => "info",
                'message' => 'User ' . $user->name . ' was updated perfectly.'
            ], 200);
        }
        return "The access is just for JSON request";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}