@section('title', 'Pixels')

@push('css')
    <link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
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

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .form-control {
            border-radius: 6px;
            font-size: 14px;
        }

        .form-check-label {
            font-size: 14px;
        }

        .form-check-input:checked {
            background-color: #4e73df;
            border-color: #4e73df;
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
            background: #d1e7dd;
            color: #0f5132;
            border: none;
            padding: 6px 14px;
            font-size: 14px;
            border-radius: 8px;
        }

        .btn-lime:hover {
            background: #badbcc;
            color: #0f5132;
        }

        .alert-warning {
            border-radius: 6px;
            font-size: 14px;
        }
    </style>
@endpush
@section('content')
    <h4 class="mb-3">Edit Pixel</h4>
    <form method="post" action="{{ route('pixels.update', $pixel) }}">
        @csrf @method('PUT')
        @include('pixels._form', ['pixel' => $pixel])
        <div class="mt-3">
            <a href="{{ route('pixels.index') }}" class="btn btn-light">Cancel</a>
            <button class="btn btn-primary">Update</button>
        </div>
    </form>
@endsection
