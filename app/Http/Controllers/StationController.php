<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        return Station::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
        ]);

        return Station::create($request->all());
    }

    public function show(Station $station)
    {
        return $station;
    }

    public function update(Request $request, Station $station)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'location' => 'sometimes|string',
        ]);

        $station->update($request->all());
        return $station;
    }

    public function destroy(Station $station)
    {
        $station->delete();
        return response(null, 204);
    }
}
