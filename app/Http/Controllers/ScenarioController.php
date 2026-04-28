<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;

class ScenarioController extends Controller
{
    public function index()
    {
        $scenarios = Scenario::all();
        return view('Scenarios.index', compact('scenarios'));
    }

    public function create()
    {
        $scenarioTypes = ['Benchmarking Scenarios', 'Complain Scenarios', 'Obligation Scenarios']; // You can expand later
        return view('Scenarios.create', compact('scenarioTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'scenario_type' => 'required|string|max:255',
            'scenario' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'network' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:50',
            'pause' => 'nullable|string|max:50',
            'number_of_devices' => 'nullable|integer'
        ]);

        Scenario::create($request->all());

        return redirect()->route('scenarios.index')->with('status', 'Scenario added successfully!');
    }

    public function edit($id)
    {
        $scenario = Scenario::findOrFail($id);
        $scenarioTypes = ['Benchmarking Scenarios', 'Complain Scenarios', 'Obligation Scenarios'];
        return view('Scenarios.edit', compact('scenario', 'scenarioTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'scenario_type' => 'required|string|max:255',
            'scenario' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'network' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:50',
            'pause' => 'nullable|string|max:50',
            'number_of_devices' => 'nullable|integer'
        ]);

        $scenario = Scenario::findOrFail($id);
        $scenario->update($request->all());

        return redirect()->route('scenarios.index')->with('status', 'Scenario updated successfully!');
    }

    public function destroy($id)
    {
        Scenario::findOrFail($id)->delete();
        return redirect()->route('scenarios.index')->with('status', 'Scenario deleted successfully!');
    }
}
