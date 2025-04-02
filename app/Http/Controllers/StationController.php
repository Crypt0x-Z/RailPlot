<?php
namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class StationController extends Controller
{
    // (Authentication middleware is unchanged; ensure routes allow public access for now if needed)

    /** List all stations */
    public function index()
    {
        $stations = Station::all();
        return response()->json($stations);
        
    }

    /** Create a new station */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'x'    => 'required|integer',
            'y'    => 'required|integer',
            'location' => 'required|array|min:1',
            'location.*' => 'in:ground,underground,suspended',
        ]);
    
        $station = Station::create([
            'name' => $data['name'],
            'x' => $data['x'],
            'y' => $data['y'],
            'location' => json_encode($data['location'] ?? []), // Always store as JSON array
        ]);
        return response()->json($station, 201);
    }
    


    /** Show a single station (if needed) */
    public function show(Station $station)
    {
        return response()->json($station);
    }

    /** Update an existing station */
    public function update(Request $request, $id)
    {
        $station = Station::findOrFail($id);
    
        $validated = $request->validate([
            'name' => 'required|string',
            'x' => 'required|integer',
            'y' => 'required|integer',
            'location' => 'required|array',
        ]);
    
        // Convert array to JSON string for storage
        $station->name = $validated['name'];
        $station->x = $validated['x'];
        $station->y = $validated['y'];
        $station->location = json_encode($validated['location']);
        $station->save();
    
        return response()->json($station);
    }
    


    /** Delete a station */
    public function destroy(Station $station)
    {
        try {
            $station->delete();
            return response()->json(['message' => 'Station deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting station'], 500);
        }
    }

    public function clearAllStations()
{
    try {
        DB::table('stations')->delete(); // safer with foreign keys
        return response()->json(['message' => 'All stations deleted successfully.']);
    } catch (\Exception $e) {
        // Log the error
        return response()->json(['error' => 'Error deleting stations.'], 500);
    }
}

}
