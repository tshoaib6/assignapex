@extends('layout.default')

@section('title', 'CST Tester Assignment')

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
        background: linear-gradient(45deg, #4e73df, #224abe);
        border: none;
        padding: 8px 18px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #224abe, #1b3c96);
    }

    .btn-lime {
        background: #e2e6ea;
        color: #495057;
        border-radius: 8px;
        font-size: 14px;
    }

    .btn-lime:hover {
        background: #d6d8db;
    }
</style>
@endpush

@section('content')
<h1 class="page-header mb-4">
    CST Tester Assignment <small class="text-muted">Assign tester for the selected request</small>
</h1>


@php $cstid = $request->id;
$user = Auth::user();
$role = $user->getRoleNames()->first();
$position = optional($user->teamDetail)->position;
$cst = \App\Models\CSTRequest::where('id', $cstid)->first();
$is_show = 0;
if ($cst && ($cst->assign_to === null || ($cst->assign_to !== null && $cst->status == 2))) {
    $is_show = 1;
} else {
    $is_show = 0;
}


@endphp
@include('partials.cst_request_readonly')


<!-- Assign Tester -->
@if($role != 'User')
    @if($position == 'Project Manager')
        @if(
            ($cst->assign_to === null && $cst->step == 1) ||
            ($cst->assign_to !== null && $cst->step == 2)
        )
        <div class="card">
            <div class="card-header">
                <h4><i class="fa fa-user-check me-2"></i> Assign Tester</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('request.tester') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="request_id" value="{{ $request->id }}">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tester Name <span class="text-danger">*</span></label>
                            <select name="tester_id" id="confirmTester" class="form-select" required>
                                <option value="">-- Select Tester --</option>
                                @foreach($testers as $tester)
                                    <option value="{{ $tester->user->id }}"
                                            data-contact="{{ $tester->user->phone }}"
                                            data-email="{{ $tester->user->email }}">
                                        {{ $tester->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Contact</label>
                            <input type="text" name="contact" id="testerPhoneno" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="testerEmail" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Team Leader's Note</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Add any special instructions..." required></textarea>
                    </div>
                            <div class="form-group mb-3">
    <label for="docs" class="form-label">Attachments (optional)</label>
    <input type="file" 
           class="form-control" 
           id="docs" 
           name="docs[]" 
           multiple>
</div>
 <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-lime" onclick="window.location.href='{{ url('cstform') }}'">
                    <i class="fa fa-arrow-left me-1"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Submit
                </button>
            </div> 
                    </div>
           
                </form>
            </div>
        </div>
        @endif
    @endif
@endif

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const testerSelect = document.getElementById('confirmTester');
    const contactInput = document.getElementById('testerPhoneno');
    const emailInput = document.getElementById('testerEmail');

    testerSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        contactInput.value = selectedOption.getAttribute('data-contact') || '';
        emailInput.value = selectedOption.getAttribute('data-email') || '';
    });
});
</script>
@endpush