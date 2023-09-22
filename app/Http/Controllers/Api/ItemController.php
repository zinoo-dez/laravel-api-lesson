<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // 1. all functions are return json()
    // 2.json contains data,status code and message 
    public function index()
    {
        $items = Item::all();
        if ($items) {
            return response()->json([
                'data' => $items,
                'message' => 'Items retrieved successfully',
                'status' => 200
            ], 200);
        } else {
            return response()->json([
                "status" => 500,
                'message' => "Server Error"
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|min:2',
            'price' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 422,
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ], 422);
        } else {
            $item = Item::create($request->all());
            if ($item) {
                return response()->json([
                    'data' => $item,
                    'message' => 'Item created successfully',
                    'status' => 201
                ], 201);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'server error',
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::find($id);
        if ($item) {
            return response()->json([
                'data' => $item,
                'message' => 'Item retrieved successfully',
                'status' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Item Not Found',
                'status' => 404
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|max:255',
            'description' => 'nullable',
            'price' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
            ], 422);
        }
        $item = Item::find($id);
        $res = $item->update($request->all());
        if ($res) {
            return response()->json([
                'data' => $item,
                'message' => 'Item updated successfully',
                'status' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Item updated fail',
                'status' => 409
            ], 409);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::find($id);
        $res = $item->delete();
        if ($res) {
            return response()->json([
                'message' => 'Item deleted successfully',
                'status' => 202
            ], 202);
        } else {
            return response()->json([
                'message' => 'Item alredy been deleted',
                'status' => 410
            ], 410);
        }
    }
}
