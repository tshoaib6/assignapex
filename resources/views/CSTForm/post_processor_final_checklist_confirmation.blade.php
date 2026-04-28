@extends('layout.default')

@section('title', 'CST Tester Assignment')

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

<div id="ninthForm" style="display:none; margin-top:20px;">
  <div class="card">
    <div class="card-body">
      <h5 class="mb-3">Post Processor Final Checklist Confirmation</h5>

      <p>I read all the items in the <strong>"Post Processor Final Checklist"</strong></p>

      <!-- Show Checklist Button (optional functionality) -->
      <button type="button" class="btn btn-info mb-3" onclick="alert('Checklist will be displayed in a modal or popup (future implementation)')">
        Show the Checklist
      </button>

      <div class="row">
        <!-- Checklist Confirmation -->
        <div class="col-md-4 mb-3">
          <label class="form-label">Checklist Confirmation</label>
          <select class="form-select" id="checklistConfirmation" required>
            <option value="">-- Select --</option>
            <option value="confirmed">Confirmed</option>
            <option value="not_confirmed">Not Confirmed</option>
          </select>
        </div>

        <!-- Actual KM -->
        <div class="col-md-4 mb-3">
          <label class="form-label">Actual KM</label>
          <input type="number" class="form-control" id="actualKm" placeholder="Enter Actual KM" required>
        </div>

        <!-- Actual Hours -->
        <div class="col-md-4 mb-3">
          <label class="form-label">Actual Hours</label>
          <input type="text" class="form-control" id="actualHours" placeholder="Enter Actual Hours" required>
        </div>
      </div>
<div class="row justify-content-end " style="margin-right: calc(0 * var(--bs-gutter-x)); margin-left: calc(0 * var(--bs-gutter-x)); margin-top:15px;">
<button type="button" class="btn btn-lime mb-1 me-2" style="width: fit-content;"  onclick="window.location.href='{{ url('cstform') }}'">   Cancel</button>
<!-- SECOND FORM SUBMIT BUTTON (Simulated) -->
<button  type="button" class="btn btn-lime mb-1 me-2" style="width: fit-content;" onclick="proceedToTenthForm()">Submit Ninth Form</button>
</div>
      
    </div>
  </div>
</div>

@endsection