@extends('layout.default')

@section('title', 'Dashboard')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .counter-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        padding: 20px;
        text-align: center;
        transition: 0.3s ease;
        border: 1px solid #f1f1f1;
    }
    .counter-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
    }
    .counter-icon {
        font-size: 32px;
        margin-bottom: 10px;
        color: #4e73df;
        transition: 0.3s ease;
    }
    .counter-card:hover .counter-icon {
        color: #224abe;
        transform: scale(1.1);
    }
    .counter-number {
        font-size: 26px;
        font-weight: 700;
        color: #333;
    }
    .counter-subheading {
        font-size: 14px;
        color: #777;
        margin-top: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }
    .card-header {
        background: #f8f9fa;
        padding: 12px 15px;
        border-bottom: 1px solid #dee2e6;
    }
    .card-header h6 {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    /* Select2 Bootstrap 5 Theme Fixes */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-3">
    Hi, {{ Auth::user()->name }}. <small>here's what's happening today.</small>
</h1>

@php
    use App\Models\{SelectedChecklist, CSTRequest, Pixel};
    use Carbon\Carbon;

    $startDate   = request('start_date');
    $endDate     = request('end_date');
    $ticketId    = request('ticket_id');
    $quarter     = request('quarter');
    $pixelFilter = request('pixel');

    // Convert quarter selection to a date range (current year, overrides manual dates only when no manual range is set)
    if ($quarter && !($startDate && $endDate)) {
        $year = (int) Carbon::now()->year;
        $quarterMap = [
            'Q1' => [$year . '-01-01', $year . '-03-31'],
            'Q2' => [$year . '-04-01', $year . '-06-30'],
            'Q3' => [$year . '-07-01', $year . '-09-30'],
            'Q4' => [$year . '-10-01', $year . '-12-31'],
        ];
        if (isset($quarterMap[$quarter])) {
            $startDate = $quarterMap[$quarter][0];
            $endDate   = $quarterMap[$quarter][1];
        }
    }

    $endDateTime = ($startDate && $endDate) ? Carbon::parse($endDate)->endOfDay() : null;

    // Shared base query builder closure
    $applyFilters = function ($query) use ($startDate, $endDate, $endDateTime, $ticketId, $pixelFilter) {
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDateTime]);
        }
        if ($ticketId) {
            $query->where('id', $ticketId);
        }
        if ($pixelFilter) {
            $query->where('pixel', $pixelFilter);
        }
        return $query;
    };

    $baseQuery         = $applyFilters(CSTRequest::query());
    $totalCost         = (clone $baseQuery)->sum('total_cost');
    $totalCstRequests  = (clone $baseQuery)->count();
    $completedRequests = (clone $baseQuery)->where('status', 5)->count();
    $pendingRequests   = (clone $baseQuery)->where('status', 1)->count();

    // KM — filter via cstRequest relationship
    $kmQuery = SelectedChecklist::query();
    if ($startDate && $endDate) {
        $kmQuery->whereHas('cstRequest', fn($q) => $q->whereBetween('created_at', [$startDate, $endDateTime]));
    }
    if ($ticketId) {
        $kmQuery->where('cst_request_id', $ticketId);
    }
    if ($pixelFilter) {
        $kmQuery->whereHas('cstRequest', fn($q) => $q->where('pixel', $pixelFilter));
    }
    $totalWorkingKm = $kmQuery->sum('total_km');

    // Dropdowns
    $allTickets = CSTRequest::select('id', 'unique_request_id')->orderBy('created_at', 'desc')->get();
    $allPixels  = Pixel::orderBy('region')->get(['id', 'grid_id', 'region', 'city']);

    // Latest requests table
    $latestRequestsQuery = $applyFilters(CSTRequest::with('user')->latest());
    $latestRequests = $latestRequestsQuery->take(10)->get();
@endphp

<!-- ✅ Filter Section -->
<div class="card mb-4">
    <div class="card-header">
        <h6><i class="fa fa-filter me-2"></i>Filter Dashboard</h6>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-2">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
                <label for="quarter" class="form-label">Quarter</label>
                <select class="form-select" id="quarter" name="quarter">
                    <option value="">-- All Quarters --</option>
                    <option value="Q1" {{ request('quarter') == 'Q1' ? 'selected' : '' }}>Q1 (Jan – Mar)</option>
                    <option value="Q2" {{ request('quarter') == 'Q2' ? 'selected' : '' }}>Q2 (Apr – Jun)</option>
                    <option value="Q3" {{ request('quarter') == 'Q3' ? 'selected' : '' }}>Q3 (Jul – Sep)</option>
                    <option value="Q4" {{ request('quarter') == 'Q4' ? 'selected' : '' }}>Q4 (Oct – Dec)</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="pixel" class="form-label">Pixel</label>
                <select class="form-select select2" id="pixel" name="pixel">
                    <option value="">-- All Pixels --</option>
                    @foreach($allPixels as $px)
                        <option value="{{ $px->grid_id }}" {{ request('pixel') == $px->grid_id ? 'selected' : '' }}>
                            {{ $px->grid_id }}{{ $px->region ? ' — ' . $px->region : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="ticket_id" class="form-label">Ticket</label>
                <select class="form-select select2" id="ticket_id" name="ticket_id">
                    <option value="">-- All Tickets --</option>
                    @foreach($allTickets as $ticket)
                        <option value="{{ $ticket->id }}" {{ request('ticket_id') == $ticket->id ? 'selected' : '' }}>
                            {{ $ticket->unique_request_id ?? 'ID: '.$ticket->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="fa fa-search"></i> Apply
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary flex-fill">
                    <i class="fa fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

@php
    $user = auth()->user()->roles;
    $role = $user->first()?->pivot->role_id;
    $team = \Illuminate\Support\Facades\Auth::user();
    $position = optional($team->teamDetail)->position;
@endphp

@if((int) $role === 2 || $position === 'Project Manager')
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="counter-card">
                <i class="fa fa-file-alt counter-icon"></i>
                <div class="counter-number">{{ $totalCstRequests }}</div>
                <div class="counter-subheading">Total CST Requests</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <i class="fa fa-check-circle counter-icon"></i>
                <div class="counter-number">{{ $completedRequests }}</div>
                <div class="counter-subheading">Completed Requests</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <i class="fa fa-road counter-icon"></i>
                <div class="counter-number">{{ $totalWorkingKm }}</div>
                <div class="counter-subheading">Total KM</div>
            </div>
        </div>
         <div class="col-md-3">
            <div class="counter-card">
               <span class="counter-icon"><strong>SAR</strong></span>
                <div class="counter-number">{{ number_format($totalCost, 2) }}</div>
                <div class="counter-subheading">
                    Total Cost
                    @if($ticketId)
                        <small class="d-block text-primary">(Filtered by Ticket)</small>
                    @elseif($quarter)
                        <small class="d-block text-primary">({{ $quarter }} {{ Carbon::now()->year }})</small>
                    @elseif($startDate && $endDate)
                        <small class="d-block text-primary">({{ $startDate }} – {{ $endDate }})</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<!-- ✅ LATEST CST REQUESTS -->
<div class="card mb-4">
    <div class="card-header">
        <h6><i class="fa fa-history me-2"></i>Latest CST Requests</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Request Number</th>
                        <th>Request Type</th>
                        <th>Requested By</th>
                        <th>Status</th>
                        <th>Total Cost (SAR)</th>
                        <th>Request Created At</th>
                        <th>Report</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestRequests as $index => $request)
                        @php
                            $statusMap = [
                                1 => ['label' => 'Pending', 'class' => 'warning'],
                                2 => ['label' => 'Approved', 'class' => 'success'],
                                3 => ['label' => 'Rejected', 'class' => 'danger'],
                                4 => ['label' => 'Ongoing', 'class' => 'info'],
                                5 => ['label' => 'Completed', 'class' => 'primary'],
                            ];
                            $status = $statusMap[$request->status] ?? ['label' => 'Unknown', 'class' => 'secondary'];
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $request->unique_request_id ?? 'N/A' }}</td>
                            <td>{{ $request->request_type ?? 'N/A' }}</td>
                            <td>{{ $request->user->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-{{ $status['class'] }}">{{ $status['label'] }}</span></td>
                            <td class="fw-bold">{{ number_format($request->total_cost, 2) }}</td>
                            <td>{{ $request->created_at->toDayDateTimeString() ?? 'N/A'}}</td>
                            <td>
                                <a href="{{ route('cstform.view', $request->id) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fa fa-link"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-3 text-muted">No requests found matching your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'default',
            placeholder: "Select a Ticket",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
