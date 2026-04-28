@include('partial.head')
@php
        $request = \App\Models\CSTRequest::with(['user.teamDetail'])->find($cstid);
        $currentStep = (int)($request->step ?? $request->step);
        $currentStatus = (int)$request->status;

        $steps = [
          1  => 'Request Info',
          2  => 'Tester Assigned',
          3  => 'Driver Checklist',
          4  => 'Field Test',
          5  => 'Test Log File',
          8  => 'Report',
          11 => 'Team Leader Evaluation',
          13 => 'Final Acceptance',
        ];

        // Optional icons per step (Font Awesome)
        $stepIcons = [
          1  => 'fa-file-alt',
          2  => 'fa-th-list',
          3  => 'fa-user-check',
          4  => 'fa-clipboard-check',
          5  => 'fa-road',
          8  => 'fa-file-code',
          11  => 'fa-tasks',
          13  => 'fa-flag-checkered',
        ];

        $total = count($steps);
        if ($currentStatus === 5) {
            $completedCount = $total;
        } else {
            $completedCount = 0;
            foreach(array_keys($steps) as $stepNum) {
                if ($stepNum < $currentStep) $completedCount++;
            }
        }

        $progressPct = round(($completedCount / $total) * 100);
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    :root{
        --bg:#f6f7fb; --card:#ffffff; --text:#1f2937; --muted:#6b7280;
        --line:#e5e7eb; --ring:#e6e6e8;
        --ok:#16a34a; --ok-bg:#eafaf0;
        --now:#f59e0b; --now-bg:#fff7e6;
        --idle:#9aa4af; --idle-bg:#f8fafc;
        --radius:16px;
    }
    @media (prefers-color-scheme: dark){
        :root{
            --bg:#0f1419; --card:#141a20; --text:#e6edf3; --muted:#9aa4af;
            --line:#242b33; --ring:#1e242b;
            --ok-bg:#0e2818; --now-bg:#2b2413; --idle-bg:#151b21;
        }
    }

    .flow-shell{ background:var(--card); border:1px solid var(--ring); border-radius:var(--radius); overflow:hidden; box-shadow:0 14px 34px rgba(0,0,0,.08); }
    .flow-head{ padding:14px 18px; border-bottom:1px solid var(--ring); display:flex; align-items:center; gap:12px; }
    .flow-title{ margin:0; font-weight:800; color:var(--text); letter-spacing:.2px; }
    .flow-sub{ margin-left:auto; font-size:12px; color:var(--muted); white-space:nowrap; }

    .progressbar{ position:relative; height:15px; border-radius:999px; background:var(--line); overflow:hidden; margin:30px auto 10px; width: 70%}
    .progressbar .bar{ position:absolute; inset:0 auto 0 0; width:0; background:linear-gradient(90deg,#22c55e,#16a34a); transition:width .35s ease; }

    .vstack{ padding:18px; position:relative; }

    .step{
        position:relative; display:flex; gap:14px; padding:14px 16px; margin-left:44px; margin-bottom:8px;
        background:var(--card); border:1px solid var(--ring); border-radius:14px;
        box-shadow:0 4px 16px rgba(0,0,0,.05); width: 40%; margin: 1rem auto;
        transition:transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    }
    .step:hover{ transform:translateY(-1px); box-shadow:0 10px 26px rgba(0,0,0,.08); }

    .dot{
        position:absolute; left:-32px; top:16px; width:28px; height:28px; border-radius:50%;
        background:#fff; display:flex; align-items:center; justify-content:center;
        border:3px solid var(--idle); color:var(--idle); font-size:13px; box-shadow:0 6px 14px rgba(0,0,0,.10);
    }
    .icon-circle{
        display:flex; align-items:center; justify-content:center;
        width:30px; height:30px; border-radius:50%; background:rgba(0,0,0,.04);
        color:var(--muted); border:1px solid var(--line);
    }

    .main{ flex:1 1 auto; min-width:0; }
    .title{ margin:0; font-weight:800; font-size:15px; color:var(--text); letter-spacing:.2px; display:flex; gap:10px; align-items:center; }
    .note{ margin:.25rem 0 0; font-size:12px; color:var(--muted); }

    .badge{
        align-self:center; font-size:12px; font-weight:800; padding:6px 10px; border-radius:999px; border:1px solid transparent;
    }

    /* states */
    .is-ok{ background:var(--ok-bg); border-color:rgba(22,163,74,.30); }
    .is-ok .dot{ border-color:var(--ok); color:var(--ok); }
    .is-ok .icon-circle{ color:var(--ok); background:rgba(22,163,74,.08); border-color:rgba(22,163,74,.25); }
    .is-ok .badge{ color:var(--ok); background:rgba(22,163,74,.10); border-color:rgba(22,163,74,.35); }

    .is-now{ background:var(--now-bg); border-color:rgba(245,158,11,.35); }
    .is-now .dot{ border-color:var(--now); color:var(--now); animation:pulse 1.15s ease-in-out infinite; }
    .is-now .icon-circle{ color:var(--now); background:rgba(245,158,11,.10); border-color:rgba(245,158,11,.30); }
    .is-now .badge{ color:var(--now); background:rgba(245,158,11,.12); border-color:rgba(245,158,11,.35); }

    .is-next{ background:var(--idle-bg); }
    .is-next .dot{ border-color:var(--idle); color:var(--idle); }
    .is-next .icon-circle{ color:var(--muted); }

    @keyframes pulse {
        0% { box-shadow:0 0 0 0 rgba(245,158,11,.35); }
        70%{ box-shadow:0 0 0 12px rgba(245,158,11,0); }
        100%{ box-shadow:0 0 0 0 rgba(245,158,11,0); }
    }

    /* arrow between steps */
    .arrow{
        position:relative; display:flex; justify-content:center; padding:8px 0 14px; margin-left:22px;
    }
    .arrow .shaft{
        width:2px; background:var(--line); height:16px;
    }
    .arrow .head {
        margin-top: 8px;
        font-size: 14px;
        color: var(--line);
        margin-left: -7px;
    }

    /* compact on small screens */
    @media (max-width:576px){
        .vstack::before{ left:38px; }
        .step{ margin-left:18px; }
        .dot{ left:-28px; }
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


    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #224abe) !important;
        border: none;
        padding: 8px 18px;
        font-size: 14px;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #224abe, #1b3c96) !important;
    }

    .btn-lime {
        background: #e2e6ea !important;
        color: #495057;
        border-radius: 8px;
        font-size: 14px;
    }

    .btn-lime:hover {
        background: #d6d8db !important;
    }

    .form-label {
        font-weight: 500;
        color: #495057;
    }

    @media print {

        .no-print,
        .no-print * {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            width: 0 !important;
            overflow: hidden !important;
        }
    }
</style>
<div class="minn m-3 p-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="m-0"><i class="fa fa-file-alt"></i> Final Report</h4>
    </div>
    <div class="flow-shell mb-3">
        <div class="flow-head">
            <i class="fa fa-compass text-muted"></i>
            <h6 class="flow-title">Process Overview</h6>
            <span class="flow-sub">{{ $completedCount }} / {{ $total }} completed ({{ $progressPct }}%)</span>
        </div>
        <div class="progressbar" aria-label="Progress">
            <div class="bar" style="width: {{ $progressPct }}%;"></div>
        </div>

        <div class="vstack">
            @foreach($steps as $num => $label)
                @php
                    // A step is finished if we have passed it, OR if it's the final step and status is 5
                    $isFinished = ($num < $currentStep) || ($num === 13 && $currentStatus === 5);

                    // A step is active ONLY if it's the current step AND it's not finished yet
                    $isActive = ($num === $currentStep && $currentStatus !== 5);

                    $state = $isFinished ? 'is-ok' : ($isActive ? 'is-now' : 'is-next');

                    $badgeText = $isFinished ? 'Completed' : ($isActive ? 'In Progress' : 'Upcoming');
                    $badgeIcon = $isFinished ? 'fa-check' : ($isActive ? 'fa-hourglass-half' : 'fa-circle');
                    $leftIcon = $stepIcons[$num] ?? 'fa-circle';
                @endphp

                <div class="step {{ $state }}" aria-label="Step {{ $num }}: {{ $label }}">
                    <div class="dot">
                        @if($isFinished)
                            <i class="fa fa-check"></i>
                        @elseif($isActive)
                            <i class="fa fa-hourglass-half"></i>
                        @else
                            {{ $loop->iteration }}
                        @endif
                    </div>

                    <div class="icon-circle" aria-hidden="true">
                        <i class="fa {{ $leftIcon }}"></i>
                    </div>

                    <div class="main">
                        <p class="title">{{ $label }}</p>
                        @if($isFinished)
                            <p class="note">Finished successfully.</p>
                        @elseif($isActive)
                            <p class="note">Currently in progress…</p>
                        @else
                            <p class="note">Will start after current step.</p>
                        @endif
                    </div>

                    <span class="badge">
                        <i class="fa {{ $badgeIcon }} me-1"></i>{{ $badgeText }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
