<?php

namespace App\Http\Controllers;

use App\Models\ReviewerRejection;  
use Illuminate\Http\Request;

class ReviewerRejectionController extends Controller
{
    
    public function index()
    {
        $groupedRejections = ReviewerRejection::all()->groupBy('category');
        return view('ReviewerRejections.index', compact('groupedRejections'));
    }

    public function create()
    {
        return view('ReviewerRejections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|in:Field,Report',
            'issue' => 'required|string|max:255',
        ]);

        ReviewerRejection::create($request->all());

        return redirect()->route('reviewer_rejections.index')->with('status', 'Reviewer Rejection Added Successfully');
    }

    public function edit(ReviewerRejection $reviewerRejection)
    {
        return view('ReviewerRejections.edit', compact('reviewerRejection'));
    }

    public function update(Request $request, ReviewerRejection $reviewerRejection)
    {
        $request->validate([
            'category' => 'required|in:Field,Report',
            'issue' => 'required|string|max:255',
        ]);

        $reviewerRejection->update($request->all());

        return redirect()->route('reviewer_rejections.index')->with('status', 'Reviewer Rejection Updated Successfully');
    }

    public function destroy(ReviewerRejection $reviewerRejection)
    {
        $reviewerRejection->delete();
        return redirect()->route('reviewer_rejections.index')->with('status', 'Reviewer Rejection Deleted Successfully');
    }
}


