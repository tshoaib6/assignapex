@extends('layout.default')

@section('title', 'Roles & Permissions')

@push('css')
<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 15px 20px;
    }

    .card-header h4 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
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

    .table thead {
        background: #f8f9fa;
    }

    .table thead th {
        color: #495057;
        font-weight: 600;
    }

    .badge {
        font-size: 12px;
        padding: 5px 8px;
        margin: 2px;
        background: #e9f1ff;
        color: #004085;
        border: 1px solid #cce5ff;
    }

    .action-icons i {
        cursor: pointer;
        font-size: 16px;
        margin: 0 4px;
        transition: 0.2s ease-in-out;
    }

    .action-icons i:hover {
        opacity: 0.8;
        transform: scale(1.1);
    }

    .alert-success {
        border-radius: 8px;
        font-size: 14px;
    }
</style>
@endpush

@push('js')
<script src="/assets/plugins/masonry-layout/dist/masonry.pkgd.min.js"></script>
<script src="/assets/plugins/chart.js/dist/chart.umd.js"></script>
<script src="/assets/plugins/moment/min/moment.min.js"></script>
<script src="/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="/assets/js/demo/analytics.demo.js"></script>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Roles & Permissions <small class="text-muted">Manage system roles & permissions</small>
</h1>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fa fa-user-shield me-2"></i> Role List</h4>
        <a href="{{ url('roles/create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle me-1"></i> Add Role
        </a>
    </div>

    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th style="width:60px;">ID</th>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        <th style="width:120px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td><strong>{{ $role->name }}</strong></td>
                        <td>
                            @foreach ($role->permissions as $permission)
                            <span class="badge">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td class="action-icons">
                            <a href="{{ url('roles/'.$role->id.'/edit') }}" title="View">
                                <i class="fa fa-eye text-primary"></i>
                            </a>
                            <a href="{{ url('roles/'.$role->id.'/edit') }}" title="Edit">
                                <i class="fa fa-edit text-success"></i>
                            </a>
                            <a href="{{ url('roles/'.$role->id.'/delete') }}"
                                onclick="return confirm('Are you sure you want to delete this role?')" title="Delete">
                                <i class="fa fa-trash text-danger"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach

                    @if($roles->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center text-muted">No roles found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
