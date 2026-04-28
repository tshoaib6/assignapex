<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('getTodoCount')) {
    function getTodoCount()
    {
        $user = Auth::user();
        $count = 0;

        if ($user) {
            $position = optional($user->teamDetail)->position;

            if ($user->hasRole('Team')) {
                switch ($position) {
                    case 'Project Manager':
                        $count = \App\Models\CSTRequest::where(function ($q) use ($user) {
                            $q->where('user_id', $user->id)
                              ->orWhereNull('assign_to');
                        })->where('status', '0')->count();
                        break;

                    case 'Team Lead':
                        $count = \App\Models\SelectedChecklist::where('status', '0')->count();
                        break;

                    case 'Post Processor':
                        $count = \App\Models\TestLogFile::where('status', '0')->count();
                        break;

                    case 'Drive Tester':
                        $count = \App\Models\TesterAssignment::where('tester_id', $user->id)
                                                            ->where('status', '0')->count();
                        break;
                }
            } else {
                $count = \App\Models\CSTRequest::where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('assign_to', $user->id);
                })->whereNotNull('assign_to')
                  ->whereIn('step', [1, 12])->count();
            }
        }

        return $count;
    }
}
