@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i> Pusat Notifikasi
                    </h5>
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light">
                                <i class="fas fa-check"></i> Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif
                </div>

                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item p-3 {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            {!! $notification->data['message'] ?? 'Notifikasi' !!}
                                            
                                            <div class="mt-2 small text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $notification->created_at->diffForHumans(locale: 'id') }}
                                            </div>

                                            @if(isset($notification->data['action_url']))
                                                <div class="mt-2">
                                                    <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-outline-primary">
                                                        Lihat Detail
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ms-2">
                                            @if(is_null($notification->read_at))
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    onclick="markAsRead('{{ $notification->id }}')">
                                                    <i class="fas fa-envelope-open"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteNotification('{{ $notification->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer bg-white">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Tidak ada notifikasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteNotification(notificationId) {
    if (confirm('Hapus notifikasi ini?')) {
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
@endsection
