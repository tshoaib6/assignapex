@extends('layout.default')

@section('title', 'Notifications')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; }
    .container { max-width: 750px; margin: 30px auto; padding: 10px; }
    h2 { font-size: 24px; color: #333; margin-bottom: 20px; font-weight: 600; }

    .notification-card {
        position: relative; background: #fff; padding: 20px;
        border-radius: 10px; margin-bottom: 15px;
        border-left: 5px solid #007bff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease-in-out;
    }
    .notification-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1);}
    .notification-card.unread { border-left-color: #ffc107; background: #fffaf0; }

    .notification-header { display: flex; align-items: center; margin-bottom: 8px; }
    .notification-icon {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #fff; margin-right: 10px; font-size: 16px;
    }

    .type-new_request { background: #007bff; }
    .type-driver_test_completed { background: #17a2b8; }
    .type-post_checklist_completed { background: #6c757d; }
    .type-user_approved { background: #28a745; }

    .notification-title { font-size: 16px; font-weight: 600; color: #333; }
    .notification-message { color: #555; font-size: 14px; margin-top: 4px; }
    .notification-time { font-size: 12px; color: #999; margin-top: 6px; }

    .status-badge {
        position: absolute; top: 18px; right: 20px;
        font-size: 12px; padding: 4px 8px; border-radius: 4px;
        font-weight: 600; text-transform: uppercase;
    }
    .status-badge.unread { background: #ffc107; color: #000; }
    .status-badge.read { background: #28a745; color: #fff; }

    .notification-actions { margin-top: 10px; }
    .btn { padding: 7px 12px; font-size: 13px; border: none; border-radius: 6px; cursor: pointer; margin-right: 6px; display: inline-flex; align-items: center; }
    .btn i { margin-right: 5px; }
    .btn-read { background: #28a745; color: #fff; }
    .btn-read:hover { background: #218838; }
    .btn-delete { background: #dc3545; color: #fff; }
    .btn-delete:hover { background: #c82333; }
</style>
@endpush

@section('content')
<div class="container">
    <h2><i class="fas fa-bell"></i> Notifications</h2>

    @foreach($notifications as $notification)
        @php $type = $notification['type'] ?? 'new_request'; @endphp
        <div class="notification-card {{ !$notification['is_read'] ? 'unread' : '' }}" id="notification-{{ $notification['id'] }}">
            <div class="notification-header">
                <div class="notification-icon type-{{ $type }}">
                    @if($type == 'new_request')
                        <i class="fas fa-file-alt"></i>
                    @elseif($type == 'driver_test_completed')
                        <i class="fas fa-car"></i>
                    @elseif($type == 'post_checklist_completed')
                        <i class="fas fa-check-square"></i>
                    @elseif($type == 'user_approved')
                        <i class="fas fa-user-check"></i>
                    @endif
                </div>
                <div>
                    <div class="notification-title">{{ $notification['title'] }}</div>
                    <div class="notification-message">{{ $notification['message'] }}</div>
                    <div class="notification-time"><i class="far fa-clock"></i> {{ $notification['created_at'] }}</div>
                </div>
            </div>

            <span class="status-badge {{ $notification['is_read'] ? 'read' : 'unread' }}">
                {{ $notification['is_read'] ? 'Read' : 'Unread' }}
            </span>

            <div class="notification-actions">
                @if(!$notification['is_read'])
                    <button class="btn btn-read" onclick="markAsRead({{ $notification['id'] }})">
                        <i class="fas fa-check"></i> Mark as Read
                    </button>
                @endif
                <button class="btn btn-delete" onclick="deleteNotification({{ $notification['id'] }})">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </div>
        </div>
    @endforeach
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

@push('scripts')
<script>
    let csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function markAsRead(id) {
        fetch(`/notifications/read/${id}`, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" }
        }).then(res => res.json()).then(data => {
            if (data.success) {
                let card = document.getElementById(`notification-${id}`);
                card.classList.remove("unread");
                card.querySelector(".status-badge").classList.remove("unread");
                card.querySelector(".status-badge").classList.add("read");
                card.querySelector(".status-badge").innerText = "Read";
                let readBtn = card.querySelector(".btn-read");
                if (readBtn) readBtn.remove();
            }
        });
    }

    function deleteNotification(id) {
        if (confirm("Are you sure?")) {
            fetch(`/notifications/delete/${id}`, {
                method: "DELETE",
                headers: { "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" }
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    document.getElementById(`notification-${id}`).remove();
                }
            });
        }
    }
</script>
@endpush
@endsection
