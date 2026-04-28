@extends('layout.default')

@section('title', 'Roles & Permissions')

@push('css')
<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<style>
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        vertical-align: middle;
    }

    .badge {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .action-icons i {
        font-size: 16px;
        margin: 0 5px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .action-icons i:hover {
        transform: scale(1.2);
        opacity: 0.8;
    }

    .profile-img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }

    .card-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
    }

    .card-header h4 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .btn-primary {
        background: linear-gradient(to right, #4e73df, #224abe);
        border: none;
        font-size: 14px;
        padding: 8px 14px;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #224abe, #1b3c96);
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
    Roles & Permissions <small class="text-muted">Manage user roles efficiently</small>
</h1>

<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Users List</h4>
        @can('role.create')
        <a href="{{ url('users/create') }}" class="btn btn-primary">
            <i class="fa fa-user-plus me-1"></i> Add User
        </a>
        @endcan
    </div>

    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Phone</th>
                        <th>Profile</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->roles as $role)
                            <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $user->phone }}</td>
                        <td class="text-center">
                          
    @if($user->profile_image)
        <a href="{{ asset('storage/' . $user->profile_image) }}" target="_blank">
            <img src="{{ asset('storage/' . $user->profile_image) }}" 
                 class="profile-img" 
                 style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
        </a>
    @else
        <div style="width:40px; height:40px; border-radius:50%; background:#ddd; 
                    display:flex; align-items:center; justify-content:center; 
                    font-weight:bold; color:#555; font-size:14px;">
            {{ strtoupper(substr($user->name, 0, 1) . substr(strrchr($user->name, ' '), 1, 1)) }}
        </div>
    @endif

                        </td>
                        <td class="text-center action-icons">
                            <a href="{{ url('users/'.$user->id.'/edit') }}" title="View">
                                <i class="fa fa-eye text-primary"></i>
                            </a>
                            <a href="{{ url('users/'.$user->id.'/edit') }}" title="Edit">
                                <i class="fa fa-edit text-success"></i>
                            </a>
                            <a href="{{ url('users/'.$user->id.'/delete') }}"
                                onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="fa fa-trash text-danger"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
