@extends('layout.default')

@section('title', 'Email Configuration')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
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

    .btn-lime {
        background: linear-gradient(45deg, #32cd32, #28a745);
        border: none;
        color: #fff;
        padding: 6px 14px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-lime:hover {
        background: linear-gradient(45deg, #28a745, #1e7e34);
    }

    .form-label {
        font-weight: 500;
        color: #495057;
    }
</style>
@endpush

@section('content')

<h1 class="page-header mb-4">
    Email Configuration <small class="text-muted">Manage and update email settings</small>
</h1>

<div class="card">
    <div class="card-header">
        <h4><i class="fa fa-envelope me-2"></i> Configure Email Settings</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('store.emailconfig') }}">
            @csrf
            <div class="row">
                <div class="col-xl-6">
                    <div class="form-group mb-3">
                        <label class="form-label">App Name</label>
                        <input type="text" class="form-control" placeholder="e.g. ApexAssign"
                               name="app_name" value="{{ $data->app_name ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Mail Host</label>
                        <input type="text" class="form-control" placeholder="mail@apexassign.com"
                               name="host" value="{{ $data->host ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Mail Port</label>
                        <input type="number" class="form-control" placeholder="556"
                               name="port" value="{{ $data->port ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Mail Password</label>
                        <input type="password" class="form-control" placeholder="********"
                               name="password" value="{{ $data->password ?? '' }}">
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Mail Mailer</label>
                        <input type="text" class="form-control" placeholder="mail.apexassign.com"
                               name="mailer" value="{{ $data->mailer ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Mail Username</label>
                        <input type="text" class="form-control" placeholder="Apex"
                               name="username" value="{{ $data->username ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Mail Encryption</label>
                        <input type="text" class="form-control" placeholder="e.g. tls or ssl"
                               name="encryption" value="{{ $data->encryption ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Mail From Address</label>
                        <input type="email" class="form-control" placeholder="mail@apexassign.com"
                               name="from_address" value="{{ $data->from_address ?? '' }}">
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="row justify-content-end" style="margin-top: 15px;">
                <div class="col-auto">
                    <button type="button" class="btn btn-secondary"
                            onclick="window.location.href='{{ url()->previous() }}'">
                        <i class="fa fa-times me-1"></i> Cancel
                    </button>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
