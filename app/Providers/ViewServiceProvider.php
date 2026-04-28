<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\CSTRequest;
use App\Models\SelectedChecklist;
use App\Models\TestLogFile;
use App\Models\TesterAssignment;

// class ViewServiceProvider extends ServiceProvider
// {
//     public function boot()
//     {
//         View::composer('*', function ($view) {
//             $user = Auth::user();
//             $count = 0;

//             if ($user) {
//                 $position = optional($user->teamDetail)->position;

//                 if ($user->hasRole('Team')) {
//                     if ($position === 'Project Manager') {
//                         $count = CSTRequest::where(function ($q) use ($user) {
//                             $q->where('user_id', $user->id)
//                               ->orWhereNull('assign_to');
//                         })
//                         ->where('status', '0')
//                         ->count();

//                     } elseif ($position === 'Team Lead') {
//                         $count = SelectedChecklist::where('status', '0')->count();

//                     } elseif ($position === 'Post Processor') {
//                         $count = TestLogFile::where('status', '0')->count();

//                     } elseif ($position === 'Drive Tester') {
//                         $count = TesterAssignment::where('tester_id', $user->id)
//                                                  ->where('status', '0')
//                                                  ->count();
//                     }

//                 } else {
//                     $count = CSTRequest::where(function ($q) use ($user) {
//                         $q->where('user_id', $user->id)
//                           ->orWhere('assign_to', $user->id);
//                     })
//                     ->whereNotNull('assign_to')
//                     ->whereIn('step', [1, 12])
//                     ->count();
//                 }
//             }

//             $view->with('todoCount', $count);
//         });
//     }

//     public function register()
//     {
//         // You can keep this empty if not registering bindings
//     }
// }




 

class ViewServiceProvider extends ServiceProvider 

{ 

    // public function boot() 

    // { 

    //     View::composer('*', function ($view) { 

    //         $user = Auth::user(); 

    //         $count = 0; 

 

    //         if ($user) { 

    //             $position = optional($user->teamDetail)->position; 

 

    //             if ($user->hasRole('Team')) { 

    //                 if ($position === 'Project Manager') { 

    //                     $count = CSTRequest::where(function ($q) use ($user) { 

    //                         $q->where('user_id', $user->id) 

    //                           ->orWhereNull('assign_to'); 

    //                     }) 

    //                     ->where('status', '0') 

    //                     ->count(); 

 

    //                 } elseif ($position === 'Team Lead') { 

    //                     $count = SelectedChecklist::where('status', '0')->count(); 

 

    //                 } elseif ($position === 'Post Processor') { 

    //                     $count = TestLogFile::where('status', '0')->count(); 

 

    //                 } elseif ($position === 'Drive Tester') { 

    //                     $count = TesterAssignment::where('tester_id', $user->id) 

    //                                              ->where('status', '0') 

    //                                              ->count(); 

    //                 } 

 

    //             } else { 

    //                 $count = CSTRequest::where(function ($q) use ($user) { 

    //                     $q->where('user_id', $user->id) 

    //                       ->orWhere('assign_to', $user->id); 

    //                 }) 

    //                 ->whereNotNull('assign_to') 

    //                 ->whereIn('step', [1, 12]) 

    //                 ->count(); 

    //             } 

    //         } 

 

    //         $view->with('todoCount', $count); 

    //     }); 

    // } 

 

    // public function register() 

    // { 

    //     // You can keep this empty if not registering bindings 

    // } 

 

 

public function boot() 

{ 

    View::composer('*', function ($view) { 

        $user = Auth::user(); 

        $count = 0; 

 

        if ($user) { 

            $position = optional($user->teamDetail)->position; 

 

            if ($user->hasRole('Team')) { 

                if ($position === 'Project Manager') { 

                    $count = CSTRequest::where(function ($q) use ($user) { 

                        $q->where('user_id', $user->id) 

                          ->orWhereNull('assign_to'); 

                    })->where('status', '0')->count(); 

                } elseif ($position === 'Team Lead') { 

                    $count = SelectedChecklist::where('status', '0')->count(); 

                } elseif ($position === 'Post Processor') { 

                    $count = TestLogFile::where('status', '0')->count(); 

                } elseif ($position === 'Drive Tester') { 

                    $count = TesterAssignment::where('tester_id', $user->id) 

                                             ->where('status', '0') 

                                             ->count(); 

                } 

            } else { 

                $count = CSTRequest::where(function ($q) use ($user) { 

                    $q->where('user_id', $user->id) 

                      ->orWhere('assign_to', $user->id); 

                })->whereNotNull('assign_to') 

                  ->whereIn('step', [1, 12]) 

                  ->count(); 

            } 

        } 

 

        $view->with('todoCount', $count); 

    }); 

}}