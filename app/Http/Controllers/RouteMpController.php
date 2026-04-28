<?php

namespace App\Http\Controllers;

use App\Models\RouteMp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class RouteMpController extends Controller
{
    public function index()
    {
        $routes = RouteMp::all();
        return view('RouteMp.index', compact('routes'));
    }

    public function create()
    {
        return view('RouteMp.create');
    }

public function store(Request $request)
{
    Log::info('Route store method triggered');

    try {
        // your validation
        Log::info('Validation passed');

        $route = RouteMp::create([
            'name' => $request->name,
            'start_lat' => $request->start_lat,
            'start_lng' => $request->start_lng,
            'end_lat' => $request->end_lat,
            'end_lng' => $request->end_lng,
            'distance_km' => $request->distance_km,
            'coordinates' => $request->coordinates
        ]);

        Log::info('Route created', ['route' => $route]);

        return response()->json([
            'status' => 'success',
            'message' => 'Route saved successfully!',
            'route' => $route
        ]);
    } catch (\Exception $e) {
        Log::error('Route Store Error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong!',
            'error' => $e
        ], 500);
    }
}




    public function show($id)
    {
        $route = RouteMp::findOrFail($id);
        return view('RouteMp.show', compact('route'));
    }
}