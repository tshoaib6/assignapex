@extends('layout.default')

@section('title', 'CST Tester Assignment + Field Report')
@push('css')

<style>
    body {
        background-color: #f7f9fc;
        font-family: 'Arial', sans-serif;
    }
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        background-color: #ffffff;
    }
    .card-header {
        background-color: #ffffff;
        color: #333;
        border-bottom: 1px solid #ddd;
        font-size: 16px;
        font-weight: 600;
        padding: 10px 15px;
        cursor: pointer;
    }
    .card-header i {
        font-size: 18px;
        margin-right: 8px;
    }
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    .form-control:disabled {
        background-color: #f0f0f0;
    }

    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #224abe) !important;;
        border: none;
        padding: 8px 18px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #224abe, #1b3c96) !important;;
    }

    .btn-lime {
        background: #e2e6ea !important;
        color: #495057 !important;
        border-radius: 8px;
        font-size: 14px;
    }

    .btn-lime:hover {
        background: #d6d8db !important;
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

@php
    $cstid = $cstid;
    $cst = \App\Models\CSTRequest::find($cstid);
    $testResult = \App\Models\FieldTestResult::where('cst_request_id', $cstid)->first();
@endphp
@include('partials.cst_request_readonly')

@if( !$testResult)
@if($cst->step == 4)
<div class="card mb-5">
  <form action="{{ route('fieldtest.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="cst_request_id" value="{{ $cstid }}">

    <div class="card-body">
      <h5 class="mb-3">Field Test Results</h5>

        <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Test Start Time</label>
                <input type="hidden" name="end_time" id="testEnd" value="{{ now()->format('Y-m-d\TH:i') }}">
                <input type="hidden" name="working_hours" id="testDuration" value="">
            </div>
        </div>

        <h6 class="mb-3">Test Log File Link</h6>
        <div class="row">
            <!-- Test Log File Link -->
            <div class="col-md-8 mb-3">
                <label class="form-label">Test Log File Link</label>
                <textarea name="log_file_link" class="form-control" rows="2"
                          placeholder="Paste the log file link here..." required></textarea>
            </div>

            <!-- Test Log File Quantity -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Test Log File Quantity</label>
                <input type="number" name="log_file_quantity" class="form-control" placeholder="Enter quantity"
                       required>
            </div>
        </div>
{{--        <div class="form-group mb-3">--}}
{{--            <label for="docs" class="form-label">Attachments (optional)</label>--}}
{{--            <input type="file" class="form-control" id="docs" name="docs[]" multiple />--}}
{{--        </div>--}}

        <h6 class="mb-3">End Activity / ODO</h6>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">End KM Reading</label>
                <input type="number" name="ending_km" id="endingKm" class="form-control" placeholder="Enter end KM">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Total KM (auto)</label>
                <input type="text" name="total_km" id="totalKm" class="form-control" readonly>
            </div>

            <div class="col-md-4 mb-3 d-flex align-items-end">
                <div class="form-check">
                    <input type="checkbox" name="is_endactivity_odmeter" id="endActivity" class="form-check-input" value="1">
                    <label class="form-check-label" for="endActivity">End Activity in ODO Meter</label>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">End ODO Picture</label>
            <input type="file" class="form-control" accept="image/*" name="end_od_pic">
        </div>

      <div class="mb-3">
        <label class="form-label">Field Team’s Notes and Observation</label>
        <textarea class="form-control" name="notes" rows="3" required></textarea>
      </div>
       <div class="form-group mb-3">
            <label for="docs" class="form-label">Attachments (optional)</label>
            <input type="file"
                   class="form-control"
                   id="docs"
                   name="docs[]"
                   multiple>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ url('cstform') }}'">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Submit
                </button>
            </div>
                    </div>

    </div>
  </form>
</div>
@endif
@endif

@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startIso = @json(optional(
            \App\Models\FieldTestResult::where('cst_request_id', $cstid)->first()
        )->start_time?->format('c'));

            if (!startIso) {
                document.getElementById('testDuration').value = "";
                return;
            }

            const start = new Date(startIso);

            // End time from hidden field (fallback to now if empty)
            const endField = document.getElementById('testEnd');
            let end = endField && endField.value ? new Date(endField.value) : new Date();

            if (!isNaN(start.getTime()) && !isNaN(end.getTime()) && end > start) {
                const diffMs = end - start;                 // milliseconds
                const hours  = (diffMs / 3600000).toFixed(2); // hours with 2 decimals
                document.getElementById('testDuration').value = hours;
            } else {
                document.getElementById('testDuration').value = "";
            }
        });
    </script>
    <script>
        (function () {
            const ending = document.getElementById('endingKm');
            const total  = document.getElementById('totalKm');

            @php
                $startKm = optional(
                  \App\Models\SelectedChecklist::where('cst_request_id', $cstid)->first()
                )->starting_km;
            @endphp
            const startKm = {{ (float)($startKm ?? 0) }};

            function updateTotal() {
                const endKm = parseFloat(ending.value || '0');
                if (!isNaN(endKm) && !isNaN(startKm) && endKm >= startKm) {
                    total.value = (endKm - startKm).toFixed(2) + ' KM';
                } else {
                    total.value = '';
                }
            }

            ending?.addEventListener('input', updateTotal);
        })();
    </script>
@endpush
