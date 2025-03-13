<?php

namespace App\Http\Controllers;

use App\Models\Train;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    public function index()
    {
        return Train::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'route_id' => 'required|exists:routes,id',
            'capacity' => 'required|integer|min:1'
        ]);

        return Train::create($request->all());
    }

    public function show(Train $train)
    {
        return $train;
    }

    public function update(Request $request, Train $train)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'route_id' => 'sometimes|exists:routes,id',
            'capacity' => 'sometimes|integer|min:1'
        ]);

        $train->update($request->all());
        return $train;
    }

    public function destroy(Train $train)
    {
        $train->delete();
        return response(null, 204);
    }
}
