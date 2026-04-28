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


.form-label {
    font-weight: 500;
    color: #495057;
}
</style>
@endpush
@section('content')

<h1 class="page-header mb-4">
    CST Tester Assignment <small class="text-muted">Assign tester for the selected request</small>
</h1>

<div class="card">
    <div class="card-header">
        <h4><i class="fa fa-user-check me-2"></i> Assign Tester</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('request.tester') }}">
            @csrf
            <input type="hidden" name="request_id" value="{{ $id }}">

            <div class="row">
                <!-- Tester Name -->
                <div class="col-md-4 mb-3">
                    <label for="confirmTester" class="form-label">Tester Name <span class="text-danger">*</span></label>
                    <select name="tester_id" id="confirmTester" class="form-select" required>
                        <option value="">-- Select Tester --</option>
                        @foreach($testers as $tester)
                        <option value="{{ $tester->user->id }}" data-contact="{{ $tester->user->phone }}"
                            data-email="{{ $tester->user->email }}">
                            {{ $tester->user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tester Contact -->
                <div class="col-md-4 mb-3">
                    <label for="testerPhoneno" class="form-label">Tester Contact Number</label>
                    <input type="text" name="contact" class="form-control" id="testerPhoneno" required>
                </div>

                <!-- Tester Email -->
                <div class="col-md-4 mb-3">
                    <label for="testerEmail" class="form-label">Tester Email</label>
                    <input type="email" name="email" class="form-control" id="testerEmail" required>
                </div>
            </div>

            <!-- Team Leader Note -->
            <div class="mb-3">
                <label for="assignNotes" class="form-label">Team Leader's Note</label>
                <textarea name="note" class="form-control" id="assignNotes" rows="3"
                    placeholder="Add any special instructions here..." required></textarea>
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
           
           
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const testerSelect = document.getElementById('confirmTester');
    const contactInput = document.getElementById('testerPhoneno');
    const emailInput = document.getElementById('testerEmail');

    testerSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        contactInput.value = selectedOption.getAttribute('data-contact') || '';
        emailInput.value = selectedOption.getAttribute('data-email') || '';
    });
});
</script>
@endpush