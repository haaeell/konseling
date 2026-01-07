@extends('layouts.dashboard')

@section('content')
    <div class="container">


        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Konseling Selesai</h6>
                        <h3 class="fw-bold text-success">{{ $konselingSelesai }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Konseling Pending</h6>
                        <h3 class="fw-bold text-warning">{{ $konselingPending }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <h6 class="text-muted">Disetujui</h6>
                        <h3 class="fw-bold text-primary">{{ $konselingDisetujui }}</h3>
                    </div>
                </div>
            </div>
        </div>


        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Notifikasi</h5>

                <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-primary">
                        Tandai Semua Dibaca
                    </button>
                </form>
            </div>

            <div class="card-body p-0">
                <ul class="list-group list-group-flush" id="notif-list">
                    @forelse ($notifikasi as $notif)
                        <li class="list-group-item d-flex justify-content-between align-items-start
                            {{ $notif->read_at ? '' : 'fw-bold' }}"
                            id="notif-{{ $notif->id }}">

                            <div>
                                {{ $notif->data['message'] }}
                                <br>
                                <small class="text-muted">
                                    {{ $notif->created_at->diffForHumans() }}
                                </small>
                            </div>

                            @if (is_null($notif->read_at))
                                <button class="btn btn-sm btn-outline-success btn-read" data-id="{{ $notif->id }}">
                                    Tandai dibaca
                                </button>
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">
                            Tidak ada notifikasi
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Jadwal Konseling</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Tempat</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalKonseling as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_disetujui)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_disetujui)->format('H:i') }} </td>
                                <td>{{ $item->tempat ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($item->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada jadwal</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            document.querySelectorAll('.btn-read').forEach(button => {
                button.addEventListener('click', function() {
                    const notifId = this.dataset.id;
                    const btn = this;

                    fetch(`/notifications/read/${notifId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const item = document.getElementById(`notif-${notifId}`);
                                item.classList.remove('fw-bold');
                                btn.remove();
                                location.reload();
                            }
                        });
                });
            });
        </script>
    @endsection
