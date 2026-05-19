<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::orderBy('created_at', 'desc')->get();
        return response()->json($incidents);
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required',
            'address' => 'required',
            'description' => 'required',
            'reporter' => 'required',
            'scale' => 'required',
            'lat' => 'required',
            'lng' => 'required'
        ]);

        $incident = Incident::create($request->all());
        return response()->json(['success' => true, 'data' => $incident]);
    }

    public function update(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        $incident->update($request->all());
        return response()->json(['success' => true, 'data' => $incident]);
    }

    public function destroy($id)
    {
        Incident::destroy($id);
        return response()->json(['success' => true]);
    }

    public function getUserReports(Request $request)
    {
        $reporter = $request->query('reporter');
        $reports = Incident::where('reporter', $reporter)
                          ->where('source', 'Masyarakat')
                          ->orderBy('created_at', 'desc')
                          ->get();
        return response()->json($reports);
    }

    public function reverseGeocode(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        
        try {
            $response = Http::get("https://nominatim.openstreetmap.org/reverse", [
                'format' => 'json',
                'lat' => $lat,
                'lon' => $lng,
                'zoom' => 18,
                'addressdetails' => 1
            ]);
            
            $data = $response->json();
            $address = $data['display_name'] ?? "{$lat}, {$lng}";
            
            return response()->json(['address' => $address]);
        } catch (\Exception $e) {
            return response()->json(['address' => "{$lat}, {$lng}"]);
        }
    }
}