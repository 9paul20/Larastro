<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
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
                $rowDatas = Tag::orderBy('id', 'asc')
                    ->paginate(
                        $perPage,
                        [
                            "id",
                            "name",
                            "description",
                        ],
                        "tags_page"
                    );
                return response()->json($rowDatas, 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get All Tags",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for get all tags is just for JSON request";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        if (request()->wantsJson()) {
            try {

                $tag = Tag::create($request->all());

                return response()->json([
                    "severity" => "success",
                    "summary" => "Successful",
                    "detail" => "Tag " . $tag->name . " was created perfectly."
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in create Tag",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for store tag is just for JSON request";
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, string $id)
    {
        if (request()->wantsJson()) {
            try {

                $tag = Tag::findOrFail($id);
                $tag->updateOrFail($request->all());

                return response()->json([
                    "severity" => "info",
                    "summary" => "Successful",
                    "detail" => "Tag " . $tag->name . " was updated perfectly."
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in update Tag",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for update tag is just for JSON request";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (request()->wantsJson()) {
            try {
                $tag = Tag::findOrFail($id);
                $tag->deleteOrFail();
                return response()->json([
                    "severity" => "warn",
                    "summary" => "Warning",
                    "detail" => "Tag " . $tag->name . " was deleted perfectly."
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in delete Tag",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for destroy tag is just for JSON request";
    }

    public function destroyMany(Request $request)
    {
        if (request()->wantsJson()) {
            $tagsID = $request->all();
            $tagsCount = count($tagsID);
            if (!$tagsID) {
                return response()->json([
                    'severity' => 'error',
                    'summary' => 'Error',
                    'detail' => 'List of tags is null o undefined.'
                ], 400);
            }
            try {
                Tag::whereIn('id', $tagsID)->delete();
                return response()->json([
                    "severity" => "warn",
                    "summary" => "Warning",
                    'detail' => $tagsCount . ' Tags Deleted.'
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'severity' => 'error',
                    'summary' => 'Error',
                    'detail' => 'Error to Delete Tags.',
                    'errors' => $th->getMessage()
                ], 422);
            }
        }
        return "The access for destroy many tags is just for JSON request";
    }

    public function getCurrentTagId()
    {
        if (request()->wantsJson()) {
            try {
                $nextId = DB::table('tags')->max('id');
                return response()->json([
                    "severity" => "success",
                    "summary" => "Successful",
                    "detail" => "Next tag id was get perfectly.",
                    "nextId" => $nextId
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    "severity" => "error",
                    "summary" => "Error",
                    "detail" => "Error in get next tag id",
                    "errors" => $th->getMessage()
                ], 422);
            }
        }
        return "The access for get next tag id is just for JSON request";
    }
}