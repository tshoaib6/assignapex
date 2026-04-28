<?php


namespace App\Http\Controllers;

use App\Models\PostProcessorRejection;
use Illuminate\Http\Request;

class PostProcessorRejectionController extends Controller
{
    public function index()
    {
        $rejections = PostProcessorRejection::all();
        return view('PostProcessorRejections.index', compact('rejections'));
    }

    public function create()
    {
        return view('PostProcessorRejections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'field' => 'required|string|max:255'
        ]);

        PostProcessorRejection::create($request->all());

        return redirect()->route('post_processor_rejections.index')
                         ->with('status', 'Post Processor Rejection Added Successfully');
    }

    public function edit(PostProcessorRejection $postProcessorRejection)
    {
        return view('PostProcessorRejections.edit', compact('postProcessorRejection'));
    }

    public function update(Request $request, PostProcessorRejection $postProcessorRejection)
    {
        $request->validate([
            'field' => 'required|string|max:255'
        ]);

        $postProcessorRejection->update($request->all());

        return redirect()->route('post_processor_rejections.index')
                         ->with('status', 'Post Processor Rejection Updated Successfully');
    }

    public function destroy(PostProcessorRejection $postProcessorRejection)
    {
        $postProcessorRejection->delete();

        return redirect()->route('post_processor_rejections.index')
                         ->with('status', 'Post Processor Rejection Deleted Successfully');
    }
}
