@extends('layout.default')

@section('title', 'Reviewer Rejections')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        background: #198754 !important; /* Bootstrap green */
        color: white;
        font-weight: 600;
        font-size: 15px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
        border: none;
        padding: 6px 14px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #224abe, #1b3c96);
    }

    .table {
        font-size: 14px;
    }

    .table th {
        background: #f1f3f5;
        font-weight: 600;
        color: #495057;
    }

    .fa {
        cursor: pointer;
        transition: 0.2s ease;
    }

    .fa:hover {
        opacity: 0.8;
        transform: scale(1.1);
    }

    .alert {
        border-radius: 6px;
        font-size: 14px;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Reviewer Rejections <small class="text-muted">Manage rejection issues efficiently</small>
</h1>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="fa fa-exclamation-triangle me-2 text-danger"></i> Rejection List</h4>
            <a href="{{ route('reviewer_rejections.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Add New
            </a>
        </div>

        @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="row">
            @foreach($groupedRejections as $category => $issues)
                <div class="col-md-6">
                    <div class="card mt-3">
                        <div class="card-header">
                            <i class="fa fa-folder-open me-1"></i> {{ $category }}
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th>Issue</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($issues as $issue)
                                        <tr>
                                            <td>{{ $issue->issue }}</td>
                                            <td>
                                                <a href="{{ route('reviewer_rejections.edit', $issue->id) }}">
                                                    <i class="fa fa-edit text-success" title="Edit"></i>
                                                </a>
                                                <form action="{{ route('reviewer_rejections.destroy', $issue->id) }}" method="POST" style="display:inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background:none; border:none; padding:0;"
                                                        onclick="return confirm('Are you sure you want to delete this issue?')">
                                                        <i class="fa fa-trash text-danger ms-2" title="Delete"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($issues->isEmpty())
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No issues found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
