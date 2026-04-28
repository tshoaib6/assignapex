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
@php
$user = Auth::user();
$position = optional($user->teamDetail)->position;

@endphp

@if($position == 'Team Lead')
<div id="seventhForm" style="display:none; margin-top:20px;">
  <div class="card">
    <div class="card-body">
      <h5 class="mb-3">Post Processor Data Validation</h5>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Post Processor's Decision</label>
          <select class="form-select" id="postProcessorDecision" required>
            <option value="">-- Select Decision --</option>
            <option value="accept">Accept</option>
            <option value="reject">Reject</option>
            <option value="review">Need Review</option>
          </select>
        </div>
      </div>

      <div class="form-group mb-3">
        <label class="form-label">Post Processor's Notes</label>
        <textarea class="form-control" id="postProcessorNotes" rows="3" placeholder="Please add any notes if there is any..."></textarea>
      </div>
<div class="row justify-content-end " style="margin-right: calc(0 * var(--bs-gutter-x)); margin-left: calc(0 * var(--bs-gutter-x)); margin-top:15px;">
<button type="button" class="btn btn-lime mb-1 me-2" style="width: fit-content;"  onclick="window.location.href='{{ url('cstform') }}'">   Cancel</button>
<!-- SECOND FORM SUBMIT BUTTON (Simulated) -->




<button  type="button" class="btn btn-lime mb-1 me-2" style="width: fit-content;" onclick="proceedToEighthForm()">Submit Seven Form</button>
</div>
  
    </div>
  </div>
</div>
@endif
@endsection