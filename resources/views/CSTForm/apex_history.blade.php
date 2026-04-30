@extends('layout.default')

@section('title', 'Apex History')
@push('css')
<style>
.card { border-radius:10px; border:none; box-shadow:0 3px 8px rgba(0,0,0,.05); }
.card-header { background:#f8f9fa; border-bottom:1px solid #dee2e6; padding:15px 20px; }
.card-header h4 { font-size:18px; font-weight:600; color:#333; margin:0; }
.table { font-size:13px; }
.table th { background:#f1f3f5; font-weight:600; color:#495057; }
.badge { padding:4px 8px; border-radius:6px; font-size:11px; }
</style>
@endpush

@section('content')
<h1 class="page-header mb-4">
    Apex History <small class="text-muted">Previous process records</small>
</h1>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fa fa-history me-2"></i> All Apex Process Steps</h4>
        <a href="{{ route('cstform.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i> Back
        </a>
    </div>
    <div class="card-body">

        {{-- Search --}}
        <form method="GET" action="{{ route('apex.history') }}" class="d-flex mb-3 gap-2">
            <input type="text" name="q" class="form-control" placeholder="Search by Process ID, Step, or User…" value="{{ $q }}">
            <button type="submit" class="btn btn-primary btn-sm px-3">Search</button>
            @if($q)
                <a href="{{ route('apex.history') }}" class="btn btn-secondary btn-sm px-3">Clear</a>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Process ID</th>
                        <th>Status</th>
                        <th>Step Name</th>
                        <th>Step User</th>
                        <th>Step Start</th>
                        <th>Step End</th>
                        <th>Duration (min)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $record)
                        <tr>
                            <td>
                                <strong>{{ $record->process_id }}</strong>
                            </td>
                            <td>
                                @php
                                    $sc = match(strtolower($record->process_step_status ?? '')) {
                                        'completed' => 'success',
                                        'pending'   => 'warning',
                                        'rejected'  => 'danger',
                                        default     => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $sc }}">{{ $record->process_step_status }}</span>
                            </td>
                            <td>{{ $record->step_name }}</td>
                            <td>{{ $record->step_user }}</td>
                            <td>{{ $record->step_start ? $record->step_start->format('Y-m-d H:i') : '—' }}</td>
                            <td>{{ $record->step_end   ? $record->step_end->format('Y-m-d H:i')   : '—' }}</td>
                            <td>{{ $record->step_duration_min !== null ? number_format($record->step_duration_min) : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No history records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $history->links() }}

    </div>
</div>
@endsection
