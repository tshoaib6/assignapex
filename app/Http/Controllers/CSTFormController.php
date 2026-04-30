<?php

namespace App\Http\Controllers;

use App\Models\Pixel;
use Illuminate\Http\Request;
use App\Models\CSTRequest;
use App\Models\Pricing;
use App\Models\SelectedChecklist;
use App\Models\Checklist;
use App\Models\User;
use App\Models\TeamDetail;
use App\Models\PostProcessorFinalChecklistConfirmation;
use App\Models\TesterAssignment;
use App\Models\RegionCity;
use App\Models\FieldTestResult;
use App\Models\TestLogFile;
use App\Models\PostProcessorChecklist;
use App\Models\Scenario;
use App\Models\CstFinalAcceptance;
use App\Models\TeamLeaderEvaluation;
use App\Models\SelectedScenario;
use App\Models\PostProcessorReport;
use App\Models\PostProcessorReportValidation;
use App\Models\PPdataValidation;
use App\Models\CstPostProcessor;
use App\Models\ApexHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use DB;

class CSTFormController extends Controller
{

  public function index()
{

    $user = Auth::user();
    $CSTForm = collect(); // default empty
    $checklists = collect();
    $position = optional($user->teamDetail)->position;
    $role = $user->getRoleNames()->first();
    if ($user->hasRole('Team')) {
        $position = optional($user->teamDetail)->position;
        if ($position === 'Project Manager') {
             $CSTForm = CSTRequest::with('user')
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhereNull('assign_to');
                })
                ->get();
          $apexHistoryCount = ApexHistory::count();
          return view('CSTForm.index', compact('CSTForm','checklists','position','role','apexHistoryCount'));
        }
        elseif ($position === 'Team Lead') {
            $checklists = SelectedChecklist::with('cstRequest')
                ->get();
            return view('CSTForm.index', compact('CSTForm','checklists','position','role'));
        }
        elseif ($position === 'Post Processor') {
            $checklists = TestLogFile::with('cstRequest')
            ->get();
        return view('CSTForm.index', compact('CSTForm','checklists','position','role'));

        }
        elseif($position ===  'Drive Tester') {
             $requests = TesterAssignment::with('user','cstRequest')->where('tester_id',$user->id)->get();
            return view('CSTForm.tester_requests', compact('requests','CSTForm','checklists','position','role'));
        }
    } else {
        $CSTForm = CSTRequest::with('user')
            ->where('user_id', $user->id)
            ->orWhere('assign_to', $user->id)
            ->get();
    }

    return view('CSTForm.index', compact('CSTForm','checklists','position','role'));
}

 public function create()
{

        $regions = \App\Models\RegionCity::query()
            ->selectRaw('LOWER(TRIM(region)) AS region, MIN(TRIM(region)) AS region')
            ->groupBy('region')
            ->orderBy('region')
            ->get();
        $pixels = Pixel::orderBy('region')->get(['id','grid_id','region','city']);
        $scenarios = Scenario::select('scenario_type')->groupBy('scenario_type')->get();
        $CSTForm = [];
        $users = User::role('User')->get();
        return view('CSTForm.cstform_request_Generate', compact('CSTForm','regions','scenarios','users', 'pixels'));
 }

 public function store(Request $request)
{
    try {

        $validated = $request->validate([
            'request_type'   => 'required|string',
            'test_type'      => 'required|string',
            'region'         => 'required|string',
            'severity'       => 'required|string',
            'activity_type'  => 'required|string',
            'operator'       => 'required|string',
            'latitude'       => 'required|string',
            'longitude'      => 'required|string',
            'scenario_type'  => 'required|string',
            'scenario_set'   => 'required|string',
            'kml_path'       => 'nullable',
            'docs'         => 'nullable',
            'pixel_id'      => 'nullable',
            'area'           => 'nullable|string',
        ]);

        $kmlNames = [];

        if ($request->hasFile('kml_path')) {
            foreach ($request->file('kml_path') as $file) {
                $ext  = $file->getClientOriginalExtension();
                $name = uniqid('', true) . '.' . $ext;        // unique, no original
                $file->storeAs('kml_files', $name, 'public'); // storage/app/public/kml_files
                $kmlNames[] = $name;
            }
        }

        $docsArray = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                // Get file extension only
                $extension = $doc->getClientOriginalExtension();

                // Create unique filename without original name
                $docName = uniqid() . '.' . $extension;

                // Store file
                $doc->storeAs('docs_files', $docName, 'public');

                // Add to array
                $docsArray[] = $docName;
            }
        }


        // 1. Create CST Request
        $cstRequest = CSTRequest::create([
            'user_id'        => auth()->id(),
            'request_type'   => $validated['request_type'],
            'test_type'      => $validated['test_type'],
            'region'         => $validated['region'],
            'city'           => $validated['area'] ?? null,
            'severity'       => $validated['severity'],
            'activity_type'  => $validated['activity_type'],
            'operator'       => $validated['operator'],
            'latitude'       => $validated['latitude'],
            'longitude'      => $validated['longitude'],
            'scenario_type'  => $validated['scenario_type'],
            'scenario_set'   => $validated['scenario_set'],
            'test_details'   => $request->test_details ?? null,
            'route_link'     => $request->route_link ?? null,
            'route_distance' => $request->route_distance ?? null,
            'route_details'  => $request->route_details ?? null,
            'assign_to'      => $request->user_id ?? null,
            'kml_path'       => $kmlNames,
            'docs'           => $docsArray,
            'pixel'         => $validated['pixel_id'],
        ]);

        function normalizeCsv($v) {
            if (is_array($v)) {
                $flat = array_map(fn($x) => trim((string)$x), $v);
                $flat = array_filter($flat, fn($x) => $x !== '');
                $flat = array_values(array_unique($flat));
                return implode(', ', $flat);
            }
            // string: also collapse whitespace and dedupe on commas
            $v = (string) $v;
            if ($v === '') return '';
            $parts = preg_split('/\s*,\s*/', $v, -1, PREG_SPLIT_NO_EMPTY);
            $parts = array_map('trim', $parts);
            $parts = array_values(array_unique($parts));
            return implode(', ', $parts);
        }

        // inside store(): when saving scenarios
        if ($request->has('scenarios')) {
            foreach (array_values($request->scenarios) as $item) {
                SelectedScenario::create([
                    'cst_request_id' => $cstRequest->id,
                    'scenario'       => $item['scenario'] ?? '',
                    'description'    => normalizeCsv($item['description'] ?? ''),
                    'network'        => normalizeCsv($item['network'] ?? ''),
                    'duration'       => normalizeCsv($item['duration'] ?? ''),
                    'devices'        => normalizeCsv($item['device'] ?? ''), // number can still pass; kept as string CSV for consistency
                    'pause'          => normalizeCsv($item['cause'] ?? ''),
                ]);
            }
        }

        // 3. Email Notification
        // $subject = 'CST Request Notification';

        // if (auth()->user()->hasRole('Team')) {
        //     $messageBody = 'A Request has been generated by Team. Accept or reject it...';
        //     $user = User::find($request->user_id);

        //     if ($user) {
        //         Mail::raw($messageBody, function ($message) use ($user, $subject) {
        //             $message->to($user->email)->subject($subject);
        //         });
        //     }
        // } else {
        //     $users = User::role('Team')->get();
        //     $messageBody = 'A Request has been generated by a user. Visit dashboard for more details...';

        //     foreach ($users as $user) {
        //         Mail::raw($messageBody, function ($message) use ($user, $subject) {
        //             $message->to($user->email)->subject($subject);
        //         });
        //     }
        // }

        return redirect()->route('cstform.index')->with('status', 'CST Form submitted successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('status', 'Something went wrong. Please try again later.');
    }
}


public function getCheckpointOptions(Request $request)
{
    $scenarioType = $request->input('scenario_type');

    $distinctScenarios = Scenario::where('scenario_type', $scenarioType)
        ->select('scenario')
        ->groupBy('scenario')
        ->pluck('scenario');

    $data = [];

    foreach ($distinctScenarios as $index => $scenarioName) {
        $records = Scenario::where('scenario_type', $scenarioType)
            ->where('scenario', $scenarioName)
            ->get(['description', 'network', 'duration', 'pause', 'number_of_devices']);

        $data[] = [
            'scenario'    => $scenarioName,
            'description' => $records->pluck('description')->unique()->values(),
            'network'     => $records->pluck('network')->unique()->values(),
            'duration'    => $records->pluck('duration')->unique()->values(),
            'cause'       => $records->pluck('pause')->unique()->values(),
            'device'      => $records->pluck('number_of_devices')->unique()->values(),
        ];
    }

    return response()->json($data);
}

public function approve($id)
{
    $request = CSTRequest::findOrFail($id);
    $request->status = 2;
    $request->step = 2;// Approved
    $request->save();

    return response()->json(['message' => 'Request approved']);
}

public function reject($id)
{
    $request = CSTRequest::findOrFail($id);
    $request->status = 3;
    $request->step = 1;
    $request->save();

    return response()->json(['message' => 'Request rejected']);
}

public function assignTester($id)
{

    $request = CSTRequest::findOrFail($id);
    $testers = TeamDetail::with('user')->where('position','Drive Tester')->get();
    $user = Auth::user();
    $position = optional($user->teamDetail)->position;
    return view('CSTForm.tester_assignment', compact('request','testers','id','position'));
}


public function requestTester(Request $request)
{
    try {
        $request->validate([
            'request_id' => 'required|exists:cst_requests,id',
            'tester_id' => 'required|exists:users,id',
            'contact' => 'required|string',
            'email' => 'required|email',
            'note' => 'nullable|string',
            'docs'   => 'nullable' ,// store array in json field

        ]);

 // 2. Handle Multiple Docs Upload
      $docsArray = [];
if ($request->hasFile('docs')) {
    foreach ($request->file('docs') as $doc) {
        // Get file extension only
        $extension = $doc->getClientOriginalExtension();

        // Create unique filename without original name
        $docName = uniqid() . '.' . $extension;

        // Store file
        $doc->storeAs('docs_files', $docName, 'public');

        // Add to array
        $docsArray[] = $docName;
    }
}
        $data = [
            'cst_request_id' => $request->request_id,
            'tester_id' => $request->tester_id,
            'contact' => $request->contact,
            'email' => $request->email,
            'note' => $request->note,
            'docs'           => $docsArray, // store array in json field
            'status' => 0,
             'user_id' => auth()->id(),
        ];

        $record = TesterAssignment::create($data);
         $cst = CSTRequest::findOrFail($request->request_id);
         $cst->status = 4;
         $cst->step = 3;
         $cst->save();

// Email subject and body
        // $subject = 'CST Request Notification';
        // $messageBody = 'A CST Request has been assigned by Manager. Please review and proceed accordingly.';

        // // Get only the selected tester
        // $testerUser = User::find($request->tester_id);

        // if ($testerUser && filter_var($testerUser->email, FILTER_VALIDATE_EMAIL)) {
        //     Mail::raw($messageBody, function ($message) use ($testerUser, $subject) {
        //         $message->to($testerUser->email)->subject($subject);
        //     });
        // }

        return redirect()->route('cstform.index')->with('status', 'Tester assigned successfully!');
    } catch (\Exception $e) {
        \Log::error('Tester assignment failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()->with('status', 'Something went wrong while assigning the tester.');
    }
}

public function testerchecklist($id){
    $checklists = Checklist::where('section', '!=', 'Plan and Tools')->get()->groupBy('section');
    $tools = Checklist::where('section', 'Plan and Tools')->get()->groupBy('section');
    $postchecklists = Checklist::where('section', 'Field Observation')
        ->orderBy('id')
        ->get();
    $postchecklist = $postchecklists->groupBy('section');
    return view('CSTForm.driver_check_list',compact('checklists','tools','id', 'postchecklist'));
}

public function storechecklist(Request $request)
{
    try {
        $selectedChecklistIds = collect($request->checklists)
            ->filter(fn($item) => isset($item['checked']))
            ->keys()
            ->toArray();

        $startPicPath = $request->hasFile('start_od_pic')
            ? $request->file('start_od_pic')->store('odmeter_images', 'public')
            : null;

        $endPicPath = $request->hasFile('end_od_pic')
            ? $request->file('end_od_pic')->store('odmeter_images', 'public')
            : null;

        $docsArray = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $extension = $doc->getClientOriginalExtension();
                $docName   = uniqid() . '.' . $extension;
                $doc->storeAs('docs_files', $docName, 'public');
                $docsArray[] = $docName;
            }
        }

        $list = SelectedChecklist::updateOrCreate(
            ['cst_request_id' => $request->request_id],
            [
                'checklist_id'           => json_encode($selectedChecklistIds),
                'driver_id'              => auth()->id(),
                'is_checked'             => true,
                'start_od_pic'           => $startPicPath,
                'end_od_pic'             => $endPicPath,
                'starting_km'            => $request->starting_km ?? null,
                'ending_km'              => $request->ending_km ?? null,
                'total_km'               => isset($request->total_km) ? preg_replace('/[^0-9.]/', '', $request->total_km) : null,
                'is_endactivity_odmeter' => $request->has('is_endactivity_odmeter') ? 1 : 0,
                'docs'                   => $docsArray,
            ]
        );

        $fieldTest = FieldTestResult::updateOrCreate(
            ['cst_request_id' => $request->request_id],
            [
                'start_time'        => $request->start_time,
                'driver_id'          => auth()->id(),
            ]
        );

        $cst = CSTRequest::findOrFail($request->request_id);
        $cst->step = 4;
        $cst->save();

        $t_type  = $cst->test_type;
        $pricing = Pricing::firstOrCreate(
            ['id' => 1],
            [
                'unit_cost_driver_test' => 21.65,
                'unit_cost_walk_test'   => 1168.00,
            ]
        );

        $price = ($t_type === 'Drive Test')
            ? (float) $pricing->unit_cost_driver_test
            : (($t_type === 'Walk Test')
                ? (float) $pricing->unit_cost_walk_test
                : (float) $pricing->unit_cost_driver_test + (float) $pricing->unit_cost_walk_test);

        $totalKm   = $list->total_km ?? $sanitizedTotalKm ?? 0.0;
        $totalKm   = (float) $totalKm;
        $totalCost = $totalKm * (float) $price;

        $list->update([
            'total_cost' => $totalCost
        ]);
        $list->refresh();

        // $subject     = 'CST Request Notification';
        // $messageBody = 'A CST Request has been assigned. Please review and proceed accordingly.';

        // $testerUser = User::find($request->tester_id);

        // if ($testerUser && filter_var($testerUser->email, FILTER_VALIDATE_EMAIL)) {
        //     Mail::raw($messageBody, function ($message) use ($testerUser, $subject) {
        //         $message->to($testerUser->email)->subject($subject);
        //     });
        // }

        return redirect()->back()->with('status', 'Checklist saved successfully.');
    } catch (\Exception $e) {
        \Log::error('Checklist Store Error: ' . $e->getMessage());
        return back()->with('status', 'Failed to save checklist.');
    }
}


public function fieldTest($id)
{
    try {
        $SelectedChecklist = SelectedChecklist::where('id', $id)->first();

        if (!$SelectedChecklist) {
            return redirect()->back()->with('error', 'Checklist not found.');
        }

        $cstid = $SelectedChecklist->cst_request_id;

        return view('CSTForm.field_test_results', compact('cstid'));

    } catch (\Exception $e) {
        \Log::error('Field Test Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
}
    public function fieldteststore(Request $request)
    {
        try {
            $selectedChecklistIds = collect($request->checklists)->filter(fn($item) => isset($item['checked']))->keys()->toArray();

            $validated = $request->validate([
                'cst_request_id'          => 'required|integer|exists:cst_requests,id',
                'start_time'              => 'nullable|date',
                'end_time'                => 'nullable|date|after_or_equal:start_time',
                'working_hours'           => 'nullable|numeric',
                'notes'                   => 'nullable|string',
                'docs'                    => 'nullable|array',
                'docs.*'                  => 'nullable|file|max:51200', // 50MB each
                'ending_km'               => 'nullable|numeric|min:0',
                'total_km'                => 'nullable|string',
                'is_endactivity_odmeter'  => 'nullable|boolean',
                'end_od_pic'              => 'nullable|file|mimes:jpg,jpeg,png,gif,webp',

                // ---- Optional Test Log File fields (bring storetestlog logic here)
                'log_file_link'           => 'nullable|string',
                'log_file_quantity'       => 'nullable|integer|min:1',
            ]);

            $validated['driver_id'] = auth()->id();
            $validated['status']    = 0;

            // 2) Upload attachments once (reused for FieldTestResult + TestLogFile)
            $uploadedDocs = [];
            if ($request->hasFile('docs')) {
                foreach ($request->file('docs') as $doc) {
                    $ext     = $doc->getClientOriginalExtension();
                    $docName = uniqid('', true) . '.' . $ext;
                    $doc->storeAs('docs_files', $docName, 'public');
                    $uploadedDocs[] = $docName;
                }
            }

            // 3) Create/Update FieldTestResult
            $fieldTest = \App\Models\FieldTestResult::where('cst_request_id', $request->cst_request_id)->first();

            // add docs to validated payload for FTR
            $validated['docs'] = $fieldTest
                ? (!empty($uploadedDocs) ? $uploadedDocs : ($fieldTest->docs ?? []))
                : $uploadedDocs;

            if ($fieldTest) {
                $fieldTest->update($validated);
            } else {
                $fieldTest = \App\Models\FieldTestResult::create($validated + [
                        'cst_request_id' => (int) $request->cst_request_id,
                    ]);
            }

            // 4) Update SelectedChecklist ODO end-side data (if provided)
            $endPicPath = null;
            if ($request->hasFile('end_od_pic')) {
                $endPicPath = $request->file('end_od_pic')->store('odmeter_images', 'public');
            }

            // sanitize total_km ("123 KM" → "123")
            $sanitizedTotalKm = $request->filled('total_km')
                ? preg_replace('/[^0-9.]/', '', $request->input('total_km'))
                : null;

            if ($checklist = \App\Models\SelectedChecklist::where('cst_request_id', $request->cst_request_id)->first()) {
                $checklist->ending_km              = $request->input('ending_km') ?? $checklist->ending_km;
                $checklist->total_km               = $sanitizedTotalKm ?? $checklist->total_km;
                $checklist->is_endactivity_odmeter = $request->boolean('is_endactivity_odmeter') ? 1 : 0;
                if ($endPicPath) {
                    $checklist->end_od_pic = $endPicPath;
                }
                $checklist->status = 0;
                $checklist->save();
            }

            // 5) Move CST to step=5 after field test save
            if ($cst = \App\Models\CSTRequest::find($request->cst_request_id)) {
                $cst->step = 5;
                $cst->save();
            }

            // 6) Tester assignment status in-progress
            if ($record = \App\Models\TesterAssignment::where('cst_request_id', $request->cst_request_id)->first()) {
                $record->status = 1;
                $record->save();
            }

            // 7) If log_file_link + quantity present, ALSO save TestLogFile here (merged functionality)
            $hasLog = $request->filled('log_file_link') && $request->filled('log_file_quantity');
            if ($hasLog) {
                $log = \App\Models\TestLogFile::updateOrCreate(
                    ['cst_request_id' => (int)$request->cst_request_id],
                    [
                        'file_link'     => $request->input('log_file_link'),
                        'file_quantity' => (int)$request->input('log_file_quantity'),
                        'docs'          => $uploadedDocs, // reuse same docs array; change if you want separate inputs
                        'status'        => 0,
                    ]
                );

                // Mark field test done when logs are submitted
                $fieldTest->status = 1;
                $fieldTest->save();

                // Move CST step → 6 (log submitted)
                if ($cst = \App\Models\CSTRequest::find($request->cst_request_id)) {
                    $cst->step = 8;
                    $cst->save();
                }

                // Checklist to done
                if ($checklist = \App\Models\SelectedChecklist::where('cst_request_id', $request->cst_request_id)->first()) {
                    $checklist->status = 1;
                    $checklist->save();
                }
            }

            $selectedChecklistIds = array_map('intval', $selectedChecklistIds ?? []);

            if (!empty($selectedChecklistIds)) {
                $updateChecklist = \App\Models\FieldTestResult::updateOrCreate(
                    ['cst_request_id' => (int) $request->cst_request_id],
                    ['checklist_id'   => json_encode($selectedChecklistIds)] // or rely on cast below
                );
            }

            return redirect()->route('cstform.index')
                ->with('status', $hasLog
                    ? 'Field test and test log saved successfully.'
                    : 'Field test result saved successfully.'
                );

        } catch (\Throwable $e) {
            \Log::error('Error saving field test/log: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('status', 'Something went wrong. Please try again.');
        }
    }


// public function fieldteststore(Request $request)
// {
//     try {
//         $validatedData = $request->validate([
//             'cst_request_id' => 'required|integer|exists:cst_requests,id',
//             'start_time' => 'nullable|date',
//             'end_time' => 'nullable|date|after_or_equal:start_time',
//             'working_hours' => 'nullable|numeric',
//             'notes' => 'nullable|string',
//         ]);

//         $validatedData['driver_id'] = auth()->id();
//         $validatedData['status'] = 0;

//         // Create or update field test result
//         $fieldTest = FieldTestResult::updateOrCreate(
//             ['cst_request_id' => $request->cst_request_id],
//             $validatedData
//         );

//         // Update checklist status
//         $checklist = SelectedChecklist::where('cst_request_id', $request->cst_request_id)->first();
//         if ($checklist) {
//             $checklist->status = 0;
//             $checklist->save();
//         }

//          $cst = CSTRequest::findOrFail($request->cst_request_id);
//          $cst->step = 5;
//          $cst->save();
//          $record = TesterAssignment::where('cst_request_id',$request->cst_request_id)->first();
//          $record->status = 1;
//          $record->save();
//          $checklists = SelectedChecklist::where('cst_request_id',$request->cst_request_id)->first();
//          $checklists->status = 0;
//          $checklists->save();


// // Email subject and body
// $subject = 'CST Request Notification';
// $messageBody = 'A CST Request has been assigned. Please review and proceed accordingly.';

// // Find the Team Lead via TeamDetail model, then get the related user
// $teamLeadDetail = TeamDetail::teamLead()->with('user')->first();
// $teamLeadUser = $teamLeadDetail?->user;

// if ($teamLeadUser && filter_var($teamLeadUser->email, FILTER_VALIDATE_EMAIL)) {
//     Mail::raw($messageBody, function ($message) use ($teamLeadUser, $subject) {
//         $message->to($teamLeadUser->email)->subject($subject);
//     });
// } else {
//     \Log::warning('Team Lead not found or has invalid email when sending CST notification.');
// }


//      return redirect()->route('cstform.index')
//     ->with('status', 'Field test result saved successfully.');
//     } catch (\Exception $e) {
//         \Log::error('Error saving field test result: ' . $e->getMessage());
//         return back()->with('status', 'Something went wrong. Please try again.');
//     }
// }


public function logfile($id){
    try {
        return view('CSTForm.test_log_file', compact('id'));
    }

      catch (\Exception $e) {
        return back()->with('status', 'Something went wrong. Please try again.');
    }

}

public function storetestlog(Request $request)
{
    try {
        $validatedData = $request->validate([
            'cst_request_id' => 'required|integer|exists:cst_requests,id',
            'log_file_link' => 'required',
            'log_file_quantity' => 'required|integer|min:1',
            'dos' => 'nullable'

        ]);
   // --- Upload files with unique names (no original) ---
        $newDocs = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $ext     = $doc->getClientOriginalExtension();
                $docName = uniqid('', true) . '.' . $ext;   // e.g. 66b84b2e7d3a21.12345678.pdf
                $doc->storeAs('docs_files', $docName, 'public');
                $newDocs[] = $docName;
            }
        }

        // Create or update logic
        $log = TestLogFile::updateOrCreate(
            ['cst_request_id' => $validatedData['cst_request_id']],
            [
                'file_link' => $validatedData['log_file_link'],
                'file_quantity' => $validatedData['log_file_quantity'],
                'docs' =>             $validatedData['docs'] = $newDocs,
                'status' => 0
            ]
        );

        // Update FieldTestResult status
        $fieldtest = FieldTestResult::where('cst_request_id', $validatedData['cst_request_id'])->first();
        if ($fieldtest) {
            $fieldtest->status = 1;
            $fieldtest->save();
        }

        // Update CSTRequest step
        $cst = CSTRequest::findOrFail($validatedData['cst_request_id']);
        $cst->step = 6;
        $cst->save();
        $checklists = SelectedChecklist::where('cst_request_id',$request->cst_request_id)->first();
        $checklists->status = 1;
        $checklists->save();

 // Email subject and body
        // $subject = 'CST Request Notification';
        // $messageBody = 'Team Lead Assigned you the task compelte it, Please review and proceed accordingly.';

        // // Find the Team Lead from TeamDetail table
        // $teamLeadDetail = TeamDetail::whereRaw('LOWER(position) = ?', ['Post Processor'])
        //     ->with('user')
        //     ->first();

        // $postprocessorUser = $teamLeadDetail?->user;

        // if ($postprocessorUser && filter_var($postprocessorUser->email, FILTER_VALIDATE_EMAIL)) {
        //     Mail::raw($messageBody, function ($message) use ($postprocessorUser, $subject) {
        //         $message->to($postprocessorUser->email)->subject($subject);
        //     });
        // } else {
        //     \Log::warning('Team Lead not found or has invalid email when sending CST notification.');
        // }


        return redirect()->route('cstform.index')->with('status', 'Log file saved successfully.');
    } catch (\Exception $e) {
        \Log::error('Error saving test log file: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong while saving the log file.');
    }
}

public function postprocessorchecklist($id)
{
    try {
        $logfile = TestLogFile::findOrFail($id);
        $cstid = $logfile->cst_request_id;
        $checklists = PostProcessorChecklist::get()->groupBy('section');

        return view('CSTForm.post_processor_checklist', compact('cstid', 'checklists'));

    }
    catch (\Exception $e) {
        return back()->with('error', 'An unexpected error occurred.');
    }
}
public function storepostchecklist(Request $request)
{
    try {
        $data = $request->validate([
            'cst_request_id' => 'required|exists:cst_requests,id',
            'checklist_ids' => 'required|array',
            'docs' => 'nullable',
        ]);
   // --- Upload files with unique names (no original) ---
        $newDocs = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $ext     = $doc->getClientOriginalExtension();
                $docName = uniqid('', true) . '.' . $ext;   // e.g. 66b84b2e7d3a21.12345678.pdf
                $doc->storeAs('docs_files', $docName, 'public');
                $newDocs[] = $docName;
            }
        }


        CstPostProcessor::updateOrCreate(
            ['cst_request_id' => $data['cst_request_id']],
            ['checklist_ids' => $data['checklist_ids'],
              'docs' =>    $validatedData['docs'] = $newDocs,]


        );

        $logfile = TestLogFile::where('cst_request_id', $data['cst_request_id'])->first();
        if ($logfile) {
            $logfile->status = 1;
            $logfile->save();
        }

        $cst = CSTRequest::find($data['cst_request_id']);
        if ($cst) {
            $cst->step = 8;
            $cst->save();
        }

        $checklists = SelectedChecklist::where('cst_request_id', $data['cst_request_id'])->first();
        if ($checklists) {
            $checklists->status = 0;
            $checklists->save();
        }

        $fieldtest = FieldTestResult::where('cst_request_id', $data['cst_request_id'])->first();
        if ($fieldtest) {
            $fieldtest->status = 0;
            $fieldtest->save();
        }

        // Email subject and body
        // $subject = 'CST Request Notification';
        // $messageBody = 'Field Operator Completed the Checklist, Please review and proceed accordingly.';

        // // Find the Team Lead from TeamDetail table
        // $teamLeadDetail = TeamDetail::whereRaw('LOWER(position) = ?', ['team lead'])
        //     ->with('user')
        //     ->first();

        // $teamLeadUser = $teamLeadDetail?->user;

        // if ($teamLeadUser && filter_var($teamLeadUser->email, FILTER_VALIDATE_EMAIL)) {
        //     Mail::raw($messageBody, function ($message) use ($teamLeadUser, $subject) {
        //         $message->to($teamLeadUser->email)->subject($subject);
        //     });
        // } else {
        //     \Log::warning('Team Lead not found or has invalid email when sending CST notification.');
        // }


        return redirect()->route('cstform.index')->with('success', 'Post Processor checklist saved successfully.');
    } catch (\Exception $e) {
        \Log::error('Failed to store post processor checklist: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}


public function showSummary($id)
{
    $request = \App\Models\CSTRequest::with(['user.teamDetail'])->findOrFail($id);

}

public function storepostprocessor(Request $request)
{
    try {
        $request->validate([
            'decision' => 'required|in:accept,reject,review',
            'notes' => 'nullable|string|max:1000',
            'cst_request_id' => 'required|integer|exists:cst_requests,id',
            'docs' => 'nullable',
        ]);
   // --- Upload files with unique names (no original) ---
        $newDocs = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $ext     = $doc->getClientOriginalExtension();
                $docName = uniqid('', true) . '.' . $ext;   // e.g. 66b84b2e7d3a21.12345678.pdf
                $doc->storeAs('docs_files', $docName, 'public');
                $newDocs[] = $docName;
            }
        }

        // Update or Create decision
        PPdataValidation::updateOrCreate(
            ['cst_request_id' => $request->input('cst_request_id')],
            [
                'decision' => $request->input('decision'),
                'notes'    => $request->input('notes'),
                'status'   => 0,
                'docs' =>    $validatedData['docs'] = $newDocs,
            ]
        );

        $cst = CSTRequest::find($request->input('cst_request_id'));
        if ($cst) {
            $cst->step = 8;
            $cst->save();

              $fieldtest = FieldTestResult::where('cst_request_id',$request->cst_request_id)->first();
              $fieldtest->status = 1;
              $fieldtest->save();

        $logfile = TestLogFile::where('cst_request_id',$request->cst_request_id)->first();
        $logfile->status = 0;
        $logfile->save();
        $checklists = SelectedChecklist::where('cst_request_id',$request->cst_request_id)->first();
        $checklists->status = 1;
        $checklists->save();
        }


// Email subject and body
        // $subject = 'CST Request Notification';
        // $messageBody = 'Team Lead Assigned you the task compelte it, Please review and proceed accordingly.';

        // // Find the Team Lead from TeamDetail table
        // $teamLeadDetail = TeamDetail::whereRaw('LOWER(position) = ?', ['Post Processor'])
        //     ->with('user')
        //     ->first();

        // $postprocessorUser = $teamLeadDetail?->user;

        // if ($postprocessorUser && filter_var($postprocessorUser->email, FILTER_VALIDATE_EMAIL)) {
        //     Mail::raw($messageBody, function ($message) use ($postprocessorUser, $subject) {
        //         $message->to($postprocessorUser->email)->subject($subject);
        //     });
        // } else {
        //     \Log::warning('Team Lead not found or has invalid email when sending CST notification.');
        // }




        return redirect()->back()->with('success', 'Post Processor decision submitted successfully.');
    } catch (\Exception $e) {
        \Log::error('Error saving Post Processor decision: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while saving the decision. Please try again.');
    }
}

public function storepostprocessorreport(Request $request)
{
    $request->validate([
        'cst_request_id' => 'required|integer|exists:cst_requests,id',
        'report_link'    => 'required|string|max:2000',
        'notes'          => 'nullable|string|max:1000',
        'actual_km'      => 'nullable|numeric',
        'actual_hours'   => 'nullable|string',
        'checklists'     => 'nullable|array', // Validate the checklist input
        'docs.*'         => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:10240',
    ]);

    DB::beginTransaction();

    try {
        $requestId = $request->input('cst_request_id');

        // 1. Extract checked IDs
        // Your input name is checklists[id][checked]. array_keys gets the IDs.
        $checkedIds = $request->has('checklists') ? array_keys($request->input('checklists')) : [];

        // 2. Handle File Uploads
        $newDocs = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $docName = uniqid('', true) . '.' . $doc->getClientOriginalExtension();
                $doc->storeAs('docs_files', $docName, 'public');
                $newDocs[] = $docName;
            }
        }

        // 3. Update/Create PostProcessorReport
        PostProcessorReport::updateOrCreate(
            ['cst_request_id' => $requestId],
            [
                'report_link' => $request->report_link,
                'notes'       => $request->notes,
                'docs'        => $newDocs,
                'status'      => 0,
            ]
        );

        // 4. Update/Create Final Confirmation (Including Checklist IDs)
        PostProcessorFinalChecklistConfirmation::updateOrCreate(
            ['cst_request_id' => $requestId],
            [
                'checklist_confirmation' => 'confirmed',
                'checklist_id'           => $checkedIds, // Saving the array of IDs here
                'actual_km'              => $request->actual_km ?? 0,
                'actual_hours'           => $request->actual_hours ?? '',
                'docs'                   => $newDocs,
                'status'                 => 0,
            ]
        );

        // 5. Update CSTRequest Status & Step
        $cst = CSTRequest::findOrFail($requestId);
        $cst->update([
            'step'   => 11,
            'status' => 1
        ]);

        // 6. Update Secondary Tables
        SelectedChecklist::where('cst_request_id', $requestId)->update(['status' => 1]);
        TestLogFile::where('cst_request_id', $requestId)->update(['status' => 1]);

        DB::commit();

        $evaluation = TeamLeaderEvaluation::where('cst_request_id', $requestId)->first();

        if ($evaluation) {
            $evaluation->delete();
        }

        return redirect()->route('cstForm.index')->with('success', 'Report and checklist stored successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Combined Submission Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }

// Email subject and body
        // $subject = 'CST Request Notification';
        // $messageBody = 'Field Operator Completed the Report, Please review and proceed accordingly.';

        // // Find the Team Lead from TeamDetail table
        // $teamLeadDetail = TeamDetail::whereRaw('LOWER(position) = ?', ['Team Lead'])
        //     ->with('user')
        //     ->first();

        // $postprocessorUser = $teamLeadDetail?->user;

        // if ($postprocessorUser && filter_var($postprocessorUser->email, FILTER_VALIDATE_EMAIL)) {
        //     Mail::raw($messageBody, function ($message) use ($postprocessorUser, $subject) {
        //         $message->to($postprocessorUser->email)->subject($subject);
        //     });
        // } else {
        //     \Log::warning('Team Lead not found or has invalid email when sending CST notification.');
        // }
}

public function submitNinthForm(Request $request)
{
    try {
        $validated = $request->validate([
            'cst_request_id' => 'required|integer',
            'checklist_confirmation' => 'required',
            'actual_km' => 'nullable|numeric',
            'actual_hours' => 'nullable|string',
            'docs' => 'nullable'
        ]);

        $newDocs = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $ext     = $doc->getClientOriginalExtension();
                $docName = uniqid('', true) . '.' . $ext;   // e.g. 66b84b2e7d3a21.12345678.pdf
                $doc->storeAs('docs_files', $docName, 'public');
                $newDocs[] = $docName;
            }
        }

        $confirmation = PostProcessorFinalChecklistConfirmation::updateOrCreate(
            ['cst_request_id' => $validated['cst_request_id']],
            [
                'checklist_confirmation' => $validated['checklist_confirmation'],
                'actual_km' => $validated['actual_km'] ?? null,
                'actual_hours' => $validated['actual_hours'] ?? null,
                'docs' =>    $validatedData['docs'] = $newDocs,
                'status' => 0,
            ]
        );

        $cst = CSTRequest::find($validated['cst_request_id']);
        if ($cst && $validated['checklist_confirmation'] === 'not_confirmed') {
            $cst->step = 4;
            $cst->status = 2;
            $cst->save();
        } else {
            $cst->step = 11;
            $cst->status = 1;
            $cst->save();
        }

        // Update checklist status
        $checklists = SelectedChecklist::where('cst_request_id', $validated['cst_request_id'])->first();
        if ($checklists) {
            $checklists->status = 1;
            $checklists->save();
        }
        return redirect()->back()->with('success', 'Checklist confirmation submitted successfully.');
    } catch (\Exception $e) {
        \Log::error('Checklist submission error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Something went wrong.');
    }
}

public function submitTenthForm(Request $request)
{
    try {
        $request->validate([
            'cst_request_id' => 'required|exists:cst_requests,id',
            'decision' => 'required|in:accept,reject,review',
            'notes' => 'nullable|string|max:1000',
            'docs' => 'nullable',
        ]);
   // --- Upload files with unique names (no original) ---
        $newDocs = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $ext     = $doc->getClientOriginalExtension();
                $docName = uniqid('', true) . '.' . $ext;   // e.g. 66b84b2e7d3a21.12345678.pdf
                $doc->storeAs('docs_files', $docName, 'public');
                $newDocs[] = $docName;
            }
        }

        $reportValidation = PostProcessorReportValidation::firstOrNew([
            'cst_request_id' => $request->cst_request_id,
        ], [        'docs' =>    $validatedData['docs'] = $newDocs,]);

        $reportValidation->report_validation_decision = $request->decision;
        $reportValidation->report_validation_notes = $request->notes;
        $reportValidation->save();

        $cst = CSTRequest::find($request->cst_request_id);
        if ($cst && $request->decision === 'not_confirmed') {
            $cst->step = 4;
            $cst->save();
        } else {
            $cst->step = 11;
            $cst->save();
        }
// Email subject and body
        // $subject = 'CST Request Notification';
        // $messageBody = 'User Perform Final Review, Please review and proceed accordingly.';

        // // Find the Team Lead from TeamDetail table
        // $teamLeadDetail = TeamDetail::whereRaw('LOWER(position) = ?', ['Project Manager'])
        //     ->with('user')
        //     ->first();

        // $postprocessorUser = $teamLeadDetail?->user;

        // if ($postprocessorUser && filter_var($postprocessorUser->email, FILTER_VALIDATE_EMAIL)) {
        //     Mail::raw($messageBody, function ($message) use ($postprocessorUser, $subject) {
        //         $message->to($postprocessorUser->email)->subject($subject);
        //     });
        // } else {
        //     \Log::warning('Team Lead not found or has invalid email when sending CST notification.');
        // }


        return redirect()->back()->with('success', 'Report validation submitted successfully.');
    } catch (\Exception $e) {
        \Log::error('Error submitting report validation: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Something went wrong while submitting the report.');
    }
}

public function submitEvaluation(Request $request)
{
    $request->validate([
        'cst_request_id' => 'required|exists:cst_requests,id',
        'decision' => 'required|in:approve,reject,review',
        'notes' => 'nullable|string|max:1000',
        'docs' => 'nullable',
    ]);
   // --- Upload files with unique names (no original) ---
        $newDocs = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $ext     = $doc->getClientOriginalExtension();
                $docName = uniqid('', true) . '.' . $ext;   // e.g. 66b84b2e7d3a21.12345678.pdf
                $doc->storeAs('docs_files', $docName, 'public');
                $newDocs[] = $docName;
            }
        }
    try {
        // Create or update the evaluation
        TeamLeaderEvaluation::updateOrCreate(
            ['cst_request_id' => $request->cst_request_id],
            [
                'decision' => $request->decision,
                'notes' => $request->notes,
                'docs' =>    $validatedData['docs'] = $newDocs,
            ]
        );



        // Update the CST request
        $cst = CSTRequest::find($request->cst_request_id);
        if ($cst && $request->decision === 'approve') {
            $cst->step = 12;
            $cst->status = 4;
            $cst->save();
        } elseif ($cst && $request->decision === 'reject') {
            $cst->step = 8;
            $cst->status = 2;
            $cst->save();

            TeamLeaderEvaluation::updateOrCreate(
                ['cst_request_id' => $request->cst_request_id],
                [
                    'status' => 'pending'
                ]
            );
            CstFinalAcceptance::where('cst_request_id', $request->cst_request_id)->delete();
        } else {
            $cst->step = 8;
            $cst->status = 3;
            $cst->save();

            TeamLeaderEvaluation::updateOrCreate(
                ['cst_request_id' => $request->cst_request_id],
                [
                    'status' => 'pending'
                ]
            );
            CstFinalAcceptance::where('cst_request_id', $request->cst_request_id)->delete();
        }

// 3. Email Notification (no Team role checks)
// $subject     = 'CST Request Notification';
// $messageBody = 'Your ticket completed kindly review it and confimred it..';

// // Send to the user tied to the request; fallback to the currently logged-in user
// $recipientUser = User::find($request->user_id) ?? auth()->user();

// if ($recipientUser && filter_var($recipientUser->email, FILTER_VALIDATE_EMAIL)) {
//     Mail::raw($messageBody, function ($message) use ($recipientUser, $subject) {
//         $message->to($recipientUser->email)->subject($subject);
//     });
// }

        return redirect()->route('cstform.index')->with('success', 'Evaluation submitted successfully.');
    } catch (\Exception $e) {
        \Log::error('Evaluation submission failed: ' . $e->getMessage());

        return redirect()->back()->with('error', 'An error occurred while submitting the evaluation. Please try again.');
    }
}


public function submitfinalForm(Request $request)
{
     try {
        $request->validate([
            'cst_request_id' => 'required|exists:cst_requests,id',
            'decision' => 'required|in:accept,reject,review',
            'notes' => 'nullable|string|max:1000',
            'docs' => 'nullable',
        ]);
   // --- Upload files with unique names (no original) ---
        $newDocs = [];
        if ($request->hasFile('docs')) {
            foreach ($request->file('docs') as $doc) {
                $ext     = $doc->getClientOriginalExtension();
                $docName = uniqid('', true) . '.' . $ext;   // e.g. 66b84b2e7d3a21.12345678.pdf
                $doc->storeAs('docs_files', $docName, 'public');
                $newDocs[] = $docName;
            }
        }
        CstFinalAcceptance::create([
            'cst_request_id' => $request->cst_request_id,
            'decision' => $request->decision,
            'notes' => $request->notes,
            'docs' =>    $validatedData['docs'] = $newDocs,
            'status' => 'submitted',
        ]);

        $cst = CSTRequest::find($request->cst_request_id);
        if ($cst) {
            $cst->step = 13;
            $cst->status = ($request->decision == 'accept') ? 5 : 4;
            $cst->save();
        }

         if ($request->decision == 'accept') {
             $confirmation = \App\Models\PostProcessorFinalChecklistConfirmation::where('cst_request_id', $cst->id)->first();

             if ($confirmation) {
                 $kmRate = 21.65;
                 $hrRate = 1168;

                 $actualKm = (float) $confirmation->actual_km;
                 $actualHrs = (float) $confirmation->actual_hours;

                 $totalCost = match ($cst->test_type) {
                     'Drive Test'           => $actualKm * $kmRate,
                     'Walk Test'            => $actualHrs * $hrRate,
                     'Drive and Walk Test'  => ($actualKm * $kmRate) + ($actualHrs * $hrRate),
                     default                => 0,
                 };

                 $cst->total_cost = $totalCost;
                 $cst->save();
             }
         }

        return back()->with('success', 'Final acceptance submitted successfully.');
    } catch (\Exception $e) {
        \Log::error('Final Acceptance Error: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong while submitting final acceptance.');
    }
}

public function map(){
return view('map.index');
}

public function viewreport($id){
    $cstid = $id ;
    $user = auth()->user()->roles;
    if( $user[0]->pivot->role_id === 2 ) {
        return view('partials.client_report',compact('cstid'));
    }
    return view('partials.client_report',compact('cstid'));
}

public function viewcst($id){
    $cstid = $id ;
    return view('partials.view',compact('cstid'));
}

public function destroyrequest($id)
{
    $request = CSTRequest::findOrFail($id);
    $request->delete();

    return redirect()->back()->with('success', 'Request deleted successfully.');
}

 public function todolist()
{

    $user = Auth::user();
    $role = $user->getRoleNames()->first();
    $CSTForm = collect(); // default empty
    $checklists = collect();
        $position = optional($user->teamDetail)->position;

    if ($user->hasRole('Team')) {
    $position = optional($user->teamDetail)->position;
    if ($position === 'Project Manager') {
         $CSTForm = CSTRequest::with('user')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereNull('assign_to');
            })
            ->where('status','1')->get();

      return view('CSTForm.todo', compact('CSTForm','checklists','position','role'));
    }
    elseif ($position === 'Team Lead') {
        $checklists = SelectedChecklist::with('cstRequest')->where('status','0')
            ->get();
    return view('CSTForm.todo', compact('CSTForm','checklists','position','role'));

    }

     elseif ($position === 'Post Processor') {
        $checklists = TestLogFile::with('cstRequest')->where('status','0')
            ->get();
    return view('CSTForm.todo', compact('CSTForm','checklists','position','role'));

    }

    elseif($position ===  'Drive Tester') {
         $requests = TesterAssignment::with('user','cstRequest')->where('tester_id',$user->id)->where('status','0')->get();
        return view('CSTForm.tester_todo', compact('requests','CSTForm','checklists','position','role'));
    }

    } else {
      $CSTForm = CSTRequest::with('user')
    ->where(function ($query) use ($user) {
        $query->where('user_id', $user->id)
              ->orWhere('assign_to', $user->id);
    })
    ->whereNotNull('assign_to')
    ->whereIn('step', [1, 12])
    ->get();
    }

    return view('CSTForm.todo', compact('CSTForm','checklists','position','role'));
}


public function redolist(){
    $user = Auth::user();
    $CSTForm = collect(); // default empty
    $position = optional($user->teamDetail)->position;
    $tasks[] = '';

    if ($position === 'Project Manager') {
        $tasks = CstFinalAcceptance::where('status','submitted')->where('decision', '!=' , 'accept')->get();
    }

    if ($position === 'Post Processor') {
        $tasks = TeamLeaderEvaluation::where('status','pending')->where('decision', '!=' , 'approve')->get();
    }

    if ($position === 'Drive Tester') {
        $tasks = SelectedChecklist::where('status', 0)
            ->whereHas('cstRequest', function ($query) {
                $query->where('step', 4);
            })->get();
    }

//    if ($position === 'Post Processor') {
//        $task1 = PPdataValidation::where('status', '0')
//            ->where('decision', '!=', 'accept')
//            ->get();
//
//        $task2 = PostProcessorFinalChecklistConfirmation::where('status', '0')
//            ->where('checklist_confirmation', 'not_confirmed')
//            ->get();
//
//        $task3 = PostProcessorReportValidation::where('status', '0')
//            ->where('report_validation_decision', '!=', 'accept')
//            ->get();
//
//        $tasks = $task1->concat($task2)->concat($task3);
//
//    }

    return view('CSTForm.redo',compact('tasks'));

}

public function ppvalidationredo($id)
{
    try {
        $request = CSTRequest::findOrFail($id);
        $request->step = 6;
        $request->save();

        $ppdata = PPdataValidation::where('cst_request_id', $id)->first();
        if ($ppdata) {
            $ppdata->status = 1;
            $ppdata->decision = 'accept';
            $ppdata->save();
        }
        $checklist = SelectedChecklist::where('cst_request_id', $id)->first();

        return redirect()->route('post.checklist', ['id' => $checklist->id])->with('success', 'Fill the form Now.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Something went wrong.');
    }
}

public function ppchecklistredo($id)
{
    try {
        $request = CSTRequest::findOrFail($id);
        $request->step = 6;
        $request->save();

        $ppdata = PostProcessorFinalChecklistConfirmation::where('cst_request_id', $id)->first();
        if ($ppdata) {
            $ppdata->status = 1;
            $ppdata->checklist_confirmation = 'confirmed';
            $ppdata->save();
        }
        $checklist = SelectedChecklist::where('cst_request_id', $id)->first();

        return redirect()->route('post.checklist', ['id' => $checklist->id])->with('success', 'Fill the form Now.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Something went wrong.');
    }
}

public function ppreportredo($id)
{
    try {
        $request = CSTRequest::findOrFail($id);
        $request->step = 8;
        $request->save();

        $ppdata = PostProcessorReportValidation::where('cst_request_id', $id)->first();

        if ($ppdata) {
            $ppdata->status = 1;
            $ppdata->report_validation_decision = 'accept';
            $ppdata->save();
        }

        return redirect()->route('assign.tester', ['id' => $id])->with('success', 'Fill the form Now.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Something went wrong.');
    }
}

public function ppevaluationredo($id)
{
    return redirect()->route('assign.tester', ['id' => $id]);
}

public function redoacceptance($id)
{
    try {
        $request = CSTRequest::findOrFail($id);
        $request->step = 11;
        $request->save();

        $ppdata = TeamLeaderEvaluation::where('cst_request_id', $id)->first();

        if ($ppdata) {
            $ppdata->status = 1;
            $ppdata->decision = 'approve';
            $ppdata->save();
        }

        return redirect()->route('assign.tester', ['id' => $id])->with('success', 'Fill the form Now.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Something went wrong.');
    }
}

    public function getCitiesByRegion(Request $request)
    {
        $request->validate([
            'region' => 'required|string'
        ]);

        $region = $request->query('region');

        // Get unique cities (areas) for the region; keep a representative lat/lon per city
        $rows = \App\Models\RegionCity::where('region', $region)
            ->orderBy('area')
            ->get(['area','lat','lon']);

        // If there could be duplicates for the same "area", collapse them (prefer non-null lat/lon)
        $byArea = [];
        foreach ($rows as $r) {
            $a = $r->area;
            if (!isset($byArea[$a])) {
                $byArea[$a] = ['area' => $a, 'lat' => $r->lat, 'lon' => $r->lon];
            } else {
                // prefer non-null
                if (is_null($byArea[$a]['lat']) && !is_null($r->lat)) $byArea[$a]['lat'] = $r->lat;
                if (is_null($byArea[$a]['lon']) && !is_null($r->lon)) $byArea[$a]['lon'] = $r->lon;
            }
        }

        return response()->json(array_values($byArea));
    }

    public function revertToStep4($id)
    {
        try {
            $cst = \App\Models\CSTRequest::findOrFail($id);
            $cst->update([
                'step' => 4,
                'status' => 2
            ]);
            \App\Models\SelectedChecklist::where('cst_request_id', $id)->update(['status' => 0]);

            return redirect()->route('cstForm.index')->with('success', 'Request has been sent back to Step 4 successfully.');

        } catch (\Exception $e) {
            \Log::error('Revert Step Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not revert step. Please try again.');
        }
    }

    public function getPixelDetails(Request $request)
    {
        $request->validate([
            'pixel_id' => 'required|string'
        ]);

        $pixel = Pixel::where('grid_id', $request->pixel_id)->first();

        if ($pixel) {
            return response()->json([
                'region' => $pixel->region,
                'city'   => $pixel->city,
                'lat'    => $pixel->lat,
                'lon'    => $pixel->lon,
            ]);
        }

        return response()->json(['error' => 'Pixel not found'], 404);
    }

    // ── Project Manager: edit an approved CST request ────────────────────────
    public function editRequest($id)
    {
        $cstRequest = CSTRequest::findOrFail($id);

        $user     = Auth::user();
        $position = optional($user->teamDetail)->position;

        abort_unless($position === 'Project Manager' && (int)$cstRequest->status === 2, 403);

        $regions   = \App\Models\RegionCity::query()
            ->selectRaw('MIN(TRIM(region)) AS region')
            ->groupBy('region')
            ->orderBy('region')
            ->get();
        $pixels    = Pixel::orderBy('region')->get(['id', 'grid_id', 'region', 'city']);
        $scenarios = Scenario::select('scenario_type')->groupBy('scenario_type')->get();
        $users     = User::role('User')->get();

        return view('CSTForm.edit_request', compact('cstRequest', 'regions', 'pixels', 'scenarios', 'users'));
    }

    public function updateRequest(Request $request, $id)
    {
        $cstRequest = CSTRequest::findOrFail($id);

        $user     = Auth::user();
        $position = optional($user->teamDetail)->position;

        abort_unless($position === 'Project Manager' && (int)$cstRequest->status === 2, 403);

        $validated = $request->validate([
            'request_type'   => 'required|string',
            'test_type'      => 'required|string',
            'region'         => 'required|string',
            'area'           => 'nullable|string',
            'severity'       => 'required|string',
            'activity_type'  => 'required|string',
            'operator'       => 'required|string',
            'latitude'       => 'nullable|string',
            'longitude'      => 'nullable|string',
            'scenario_type'  => 'nullable|string',
            'test_details'   => 'nullable|string',
            'route_link'     => 'nullable|string',
            'route_distance' => 'nullable|string',
            'route_details'  => 'nullable|string',
            'pixel_id'       => 'nullable|string',
        ]);

        $cstRequest->update([
            'request_type'   => $validated['request_type'],
            'test_type'      => $validated['test_type'],
            'region'         => $validated['region'],
            'city'           => $validated['area'] ?? null,
            'severity'       => $validated['severity'],
            'activity_type'  => $validated['activity_type'],
            'operator'       => $validated['operator'],
            'latitude'       => $validated['latitude'] ?? $cstRequest->latitude,
            'longitude'      => $validated['longitude'] ?? $cstRequest->longitude,
            'scenario_type'  => $validated['scenario_type'] ?? $cstRequest->scenario_type,
            'test_details'   => $validated['test_details'] ?? $cstRequest->test_details,
            'route_link'     => $validated['route_link'] ?? $cstRequest->route_link,
            'route_distance' => $validated['route_distance'] ?? $cstRequest->route_distance,
            'route_details'  => $validated['route_details'] ?? $cstRequest->route_details,
            'pixel'          => $validated['pixel_id'] ?? $cstRequest->pixel,
        ]);

        return redirect()->route('cstform.index')->with('success', 'Request updated successfully.');
    }

    public function cancelRequest($id)
    {
        $cstRequest = CSTRequest::findOrFail($id);

        $user     = Auth::user();
        $position = optional($user->teamDetail)->position;

        abort_unless($position === 'Project Manager' && (int)$cstRequest->status === 2, 403);

        $cstRequest->update(['status' => 6]); // 6 = Cancelled

        return redirect()->route('cstform.index')->with('success', 'Request cancelled successfully.');
    }

    // ── Apex History ─────────────────────────────────────────────────────────
    public function apexHistory(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $history = ApexHistory::query()
            ->when($q, fn($qry) => $qry->where('process_id', 'like', "%{$q}%")
                ->orWhere('step_name', 'like', "%{$q}%")
                ->orWhere('step_user', 'like', "%{$q}%"))
            ->orderByDesc('step_start')
            ->paginate(50)
            ->withQueryString();

        return view('CSTForm.apex_history', compact('history', 'q'));
    }

}
