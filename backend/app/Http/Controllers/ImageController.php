<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Book;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = Image::with('book')->get();
        return response()->json($images);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:png,jpeg,jpg|max:2048',
            'book_id' => 'required|exists:books,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if ($file = $request->file('file')) {
            $path = $file->store('public/files');
            $name = $file->getClientOriginalName();

            $image = Image::create([
                'name' => $name,
                'path' => $path,
                'book_id' => $request->book_id
            ]);

            return response()->json([
                'message' => 'File saved successfully',
                'data' => $image
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
    }
}
