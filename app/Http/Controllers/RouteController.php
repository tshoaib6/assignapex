<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Route;
use Illuminate\Support\Facades\Storage;


class RouteController extends Controller
{
    // ✅ Show list of all saved routes
    public function index()
    {
        $routes = Route::latest()->get();
        return view('map.index', compact('routes'));
    }

    // ✅ Show create form
    public function create()
    {
        return view('map.create');
    }

    // ✅ Save route with uploaded KML/KMZ
    public function store(Request $request)
{
    $request->validate([
        'routes' => 'required|array',
        'routes.*.name' => 'required|string|max:255',
        'routes.*.kml_data' => 'required',
        'routes.*.kmz_data' => 'required'
    ]);

    foreach ($request->routes as $routeData) {
        $kmlPath = 'routes/' . uniqid() . '.kml';
        Storage::disk('public')->put($kmlPath, base64_decode($routeData['kml_data']));

        $kmzPath = 'routes/' . uniqid() . '.kmz';
        Storage::disk('public')->put($kmzPath, base64_decode($routeData['kmz_data']));

        Route::create([
            'name' => $routeData['name'],
            'kml_file' => $kmlPath,
            'kmz_file' => $kmzPath
        ]);
    }

    return redirect()->route('map.index')->with('success', 'All routes saved successfully!');
}

    // ✅ View single route on map
    public function show($id)
    {
        $route = Route::findOrFail($id);
        return view('map.view', compact('route'));
    }
}
