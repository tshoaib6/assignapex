<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    public function index()
    {
        $checklists = Checklist::all()->groupBy('section');
        return view('Checklists.index', compact('checklists'));
    }

    public function create()
    {
        return view('Checklists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|string|max:255',
            'check_point' => 'required|string|max:255',
            'status' => 'required|in:Yes,No,N/A',
            'remarks' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $request->hasFile('image') 
            ? $request->file('image')->store('checklists', 'public') 
            : null;

        Checklist::create([
            'section' => $request->section,
            'check_point' => $request->check_point,
            'status' => $request->status,
            'remarks' => $request->remarks,
            'image' => $imagePath,
        ]);

        return redirect()->route('checklists.index')->with('status', 'Checklist added successfully');
    }

    public function edit(Checklist $checklist)
    {
        return view('Checklists.edit', compact('checklist'));
    }

    public function update(Request $request, Checklist $checklist)
    {
        $request->validate([
            'section' => 'required|string|max:255',
            'check_point' => 'required|string|max:255',
            'status' => 'required|in:Yes,No,N/A',
            'remarks' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $checklist->image = $request->file('image')->store('checklists', 'public');
        }

        $checklist->update($request->only('section','check_point','status','remarks','image'));

        return redirect()->route('checklists.index')->with('status', 'Checklist updated successfully');
    }

    public function destroy(Checklist $checklist)
    {
        $checklist->delete();
        return redirect()->route('Checklists.index')->with('status', 'Checklist deleted successfully');
    }
}
