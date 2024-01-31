<?php

// app/Http/Controllers/Api/GenerationController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Generation;
use Illuminate\Http\Request;

class GenerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $generations = Generation::all();
        return response()->json($generations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'gen' => 'required|integer',
        ]);

        $generation = Generation::create($validatedData);

        return response()->json($generation, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Generation  $generation
     * @return \Illuminate\Http\Response
     */
    public function show(Generation $generation)
    {
        return response()->json($generation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Generation  $generation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Generation $generation)
    {
        $validatedData = $request->validate([
            'gen' => 'integer',
        ]);

        $generation->update($validatedData);

        return response()->json($generation, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Generation  $generation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Generation $generation)
    {
        $generation->delete();

        return response()->json(null, 204);
    }
}
