<?php

namespace App\Http\Controllers;

use App\Models\RegionCity;
use Illuminate\Http\Request;

class RegionCityController extends Controller
{
    // ✅ List all Region & City entries
    public function index()
    {
        $regions = RegionCity::all();
        return view('Region.index', compact('regions'));
    }

    // ✅ Show create form
    public function create()
    {
        return view('Region.create');
    }

    // ✅ Store new Region & City entry
    public function store(Request $request)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'city_highway' => 'required|string|max:255',
            'test_type' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
        ]);

        RegionCity::create($request->all());

        return redirect()->route('region.index')->with('status', 'Region & City added successfully!');
    }

    // ✅ Show edit form
    public function edit($id)
    {
        $region = RegionCity::findOrFail($id);
        return view('Region.edit', compact('region'));
    }

    // ✅ Update Region & City entry
    public function update(Request $request, $id)
    {
        $request->validate([
            'region' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'city_highway' => 'required|string|max:255',
            'test_type' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
        ]);

        $region = RegionCity::findOrFail($id);
        $region->update($request->all());

        return redirect()->route('region.index')->with('status', 'Region & City updated successfully!');
    }

    // ✅ Delete entry
    public function destroy($id)
    {
        RegionCity::findOrFail($id)->delete();
        return redirect()->route('region.index')->with('status', 'Region & City deleted successfully!');
    }
}
