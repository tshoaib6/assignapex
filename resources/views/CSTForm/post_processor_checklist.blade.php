@extends('layout.default')

@section('title', 'Post Processor Checlist')

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

@section('content')
@php
$cstid = $cstid;
$pplist = \App\Models\CSTRequest::where('id', $cstid)->where('step','6')->first();
@endphp
@include('partials.cst_request_readonly')

@php
$user = Auth::user();
$position = optional($user->teamDetail)->position;
@endphp
@if($position == 'Post Processor')
@if($pplist)
<div id="sixthForm" style="margin-top:20px;">
    <div class="card">
          <div class="card-header">
                <h4><i class="fa fa-plus-square me-2"></i>Post Processor Checklist</h4>
            </div>


        <div class="card-body">
            <form method="POST" action="{{ route('postprocessor.storechecklist') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="cst_request_id" value="{{ $cstid }}" />

                <table class="table table-bordered align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width:30%;">Section</th>
                            <th style="width:50%;">Check Points</th>
                            <th style="width:10%;">Check</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($checklists as $section => $items)
                        @foreach($items as $index => $checklist)

                        @if (!empty($checklist->parent_title))
                            <tr>
                                @if ($index === 0)
                                <td rowspan="{{ count($items) + 1 }}" class="fw-bold align-middle text-center">
                                    {{ $section }}</td>
                                @endif
                                <td colspan="2" class="fw-semibold">{{ $checklist->parent_title }}</td>
                            </tr>
                        @endif

                        <tr>
                            @if ($index === 0 && empty($checklist->parent_title))
                            <td rowspan="{{ count($items) }}" class="fw-bold align-middle text-center">{{ $section }}
                            </td>
                            @endif
                            <td>{{ $checklist->check_point }}</td>
                            <td>
                                <input type="checkbox" name="checklist_ids[]" value="{{ $checklist->id }}"
                                    class="form-check-input">
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
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
            </form>


    </div>

    </div>
</div>
@endif
@endif
@endsection
