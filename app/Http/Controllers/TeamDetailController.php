<?php

namespace App\Http\Controllers;

use App\Models\TeamDetail;
use App\Models\User;
use Illuminate\Http\Request;

class TeamDetailController extends Controller
{
    // ✅ List all team members
    public function index()
    {
        
        $teamDetails = TeamDetail::with('user')->get();
        return view('Team.index', compact('teamDetails'));
    }

    // ✅ Show create form
    public function create()
    {
        $teamUsers = User::role('team')->get();
          $departments = ['CST', 'Motabaqah'];
         $positions = [
        'Qos Monitoring Director',
        'Qos Monitoring Team Lead',
        'Project Manager',
        'Team Lead',
        'Drive Tester',
        'Post Processor'
    ];
       
        return view('Team.create', compact('teamUsers', 'positions', 'departments'));
    }

    // ✅ Store new team member
public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'department' => 'required|string|max:255',
        'position' => 'required|string|max:255',
    ]);

    TeamDetail::create([
        'user_id' => $request->user_id,
        'department' => $request->department,
        'position' => $request->position,
    ]);

    return redirect()->route('team.index')->with('status', 'Team member added successfully');
}



    // ✅ Show edit form
    public function edit($id)
    {
        $teamDetail = TeamDetail::findOrFail($id);
          $teamUsers = User::role('team')->get();
          $departments = ['CST', 'Motabaqah'];
           $positions = [
        'Qos Monitoring Director',
        'Qos Monitoring Team Lead',
        'Project Manager',
        'Team Lead',
        'Drive Tester',
        'Post Processor'
    ];
        return view('Team.edit', compact('teamDetail', 'teamUsers', 'departments', 'positions'));
    }

    // ✅ Update team member
public function update(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'department' => 'required|string|max:255',
        'position' => 'required|string|max:255',
    ]);

    $user = User::findOrFail($request->user_id);
    if (!$user->hasRole('Team')) {
        return redirect()->back()->withErrors(['user_id' => 'Only users with the Team role can be updated.']);
    }

    $teamDetail = TeamDetail::findOrFail($id);
    $teamDetail->update($request->all());

    return redirect()->route('team.index')->with('status', 'Team member updated successfully');
}


    // ✅ Delete team member
    public function destroy($id)
    {
        TeamDetail::findOrFail($id)->delete();
        return redirect()->back()->with('status', 'Team member removed successfully');
    }
}
