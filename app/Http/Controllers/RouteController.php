<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        return Route::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'start_station_id' => 'required|exists:stations,id',
            'end_station_id' => 'required|exists:stations,id',
        ]);

        return Route::create($request->all());
    }

    public function show(Route $route)
    {
        return $route;
    }

    public function update(Request $request, Route $route)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'start_station_id' => 'sometimes|exists:stations,id',
            'end_station_id' => 'sometimes|exists:stations,id',
        ]);

        $route->update($request->all());
        return $route;
    }

    public function destroy(Route $route)
    {
        $route->delete();
        return response(null, 204);
    }
}
