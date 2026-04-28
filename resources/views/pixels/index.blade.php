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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Pixels</h4>
        <a href="{{ route('pixels.create') }}" class="btn btn-primary">Add Pixel</a>
    </div>

    <form class="row g-2 mb-3" method="get">
        <div class="col-auto">
            <input name="q" value="{{ $q }}" class="form-control" placeholder="Search grid/region">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Grid ID</th>
                    <th>Region</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pixels as $i => $p)
                    <tr>
                        <td>{{ $pixels->firstItem() + $i }}</td>
                        <td>{{ $p->grid_id }}</td>
                        <td>{{ $p->region }}</td>
                        <td class="text-end">
                            <a href="{{ route('pixels.show',$p) }}" class="btn btn-sm btn-light">View</a>
                            <a href="{{ route('pixels.edit',$p) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('pixels.destroy',$p) }}" method="post" class="d-inline"
                                  onsubmit="return confirm('Delete this pixel?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No records.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $pixels->links() }}
    </div>
@endsection
