<?php
namespace App\Http\Controllers;

use App\Models\PostProcessorChecklist;
use Illuminate\Http\Request;

class PostProcessorChecklistController extends Controller
{
    public function index()
    {
        $checklists = PostProcessorChecklist::all();
        return view('PostProcessorChecklists.index', compact('checklists'));
    }

    public function create()
    {
        return view('PostProcessorChecklists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|string|max:255',
            'parent_title' => 'nullable|string|max:255',
            'check_point' => 'required|string|max:255',
            'status' => 'required|in:Yes,No,N/A',
            'remarks' => 'nullable|string',
        ]);

        PostProcessorChecklist::create($request->all());

        return redirect()->route('post-processor-checklists.index')
                         ->with('status', 'Check Point added successfully.');
    }

    public function edit(PostProcessorChecklist $postProcessorChecklist)
    {
        return view('PostProcessorChecklists.edit', ['checklist' => $postProcessorChecklist]);
    }

    public function update(Request $request, PostProcessorChecklist $postProcessorChecklist)
    {
        $request->validate([
            'section' => 'required|string|max:255',
            'parent_title' => 'nullable|string|max:255',
            'check_point' => 'required|string|max:255',
            'status' => 'required|in:Yes,No,N/A',
            'remarks' => 'nullable|string',
        ]);

        $postProcessorChecklist->update($request->all());

        return redirect()->route('post-processor-checklists.index')
                         ->with('status', 'Check Point updated successfully.');
    }

    public function destroy(PostProcessorChecklist $postProcessorChecklist)
    {
        $postProcessorChecklist->delete();

        return redirect()->route('post-processor-checklists.index')
                         ->with('status', 'Check Point deleted successfully.');
    }
}


