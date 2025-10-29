<div class="sidebar-menu">
    <ul class="menu">
        <li class="sidebar-item">
            <a href="/home" class='sidebar-link'>
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Guru BK --}}
        @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
            <li class="sidebar-item has-sub">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-folder-fill"></i>
                    <span>Data</span>
                </a>
                <ul class="submenu">
                    <li class="submenu-item"><a href="/tahun-akademik">Tahun Akademik</a></li>
                    <li class="submenu-item"><a href="/kelas">Kelas</a></li>
                    <li class="submenu-item"><a href="/guru">Guru</a></li>
                    <li class="submenu-item"><a href="/siswa">Siswa</a></li>
                    <li class="submenu-item"><a href="/kategori-konseling">Kategori Konseling</a></li>
                </ul>
            </li>

            <li class="sidebar-item">
                <a href="/permohonan-konseling" class='sidebar-link'>
                    <i class="bi bi-envelope-fill"></i>
                    <span>Permohonan Konseling</span>
                    @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                    @if ($unread > 0)
                        <span class="badge bg-danger ms-2">{{ $unread }}</span>
                    @endif
                </a>
            </li>

            <li class="sidebar-item">
                <a href="/jadwal-konseling" class='sidebar-link'>
                    <i class="bi bi-calendar-fill"></i>
                    <span>Jadwal Konseling</span>
                </a>
            </li>
        @endif

        {{-- Guru Wali Kelas → hanya bisa lihat Riwayat --}}
        @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'walikelas')
            <li class="sidebar-item">
                <a href="/permohonan-konseling" class='sidebar-link'>
                    <i class="bi bi-envelope-fill"></i>
                    <span>Permohonan Konseling</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="/jadwal-konseling" class='sidebar-link'>
                    <i class="bi bi-calendar-fill"></i>
                    <span>Jadwal Konseling</span>
                </a>
            </li>
        @endif

        <li class="sidebar-item">
            <a href="/riwayat-konseling" class='sidebar-link'>
                <i class="bi bi-clock-fill"></i>
                <span>Riwayat Konseling</span>
            </a>
        </li>

        {{-- Orang Tua → hanya Riwayat & Laporan --}}
        @if (auth()->user()->role === 'orangtua')
            <li class="sidebar-item">
                <a href="/riwayat-konseling" class='sidebar-link'>
                    <i class="bi bi-clock-fill"></i>
                    <span>Riwayat Konseling</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="/laporan" class='sidebar-link'>
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Laporan</span>
                </a>
            </li>
        @endif

        {{-- Siswa → bisa akses permohonan, jadwal, dan riwayat --}}
        @if (auth()->user()->role === 'siswa')
            <li class="sidebar-item">
                <a href="/permohonan-konseling" class='sidebar-link'>
                    <i class="bi bi-envelope-fill"></i>
                    <span>Permohonan Konseling</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="/jadwal-konseling" class='sidebar-link'>
                    <i class="bi bi-calendar-fill"></i>
                    <span>Jadwal Konseling</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="/riwayat-konseling" class='sidebar-link'>
                    <i class="bi bi-clock-fill"></i>
                    <span>Riwayat Konseling</span>
                </a>
            </li>
        @endif

        {{-- Kepala Sekolah → bisa lihat semua laporan --}}
        @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'kepala_sekolah')
            <li class="sidebar-item">
                <a href="/laporan" class='sidebar-link'>
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Laporan</span>
                </a>
            </li>
        @endif

        @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
            <li class="sidebar-item">
                <a href="/laporan" class='sidebar-link'>
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Laporan</span>
                </a>
            </li>
        @endif

        {{-- Profile untuk semua user --}}
        <li class="sidebar-item">
            <a href="{{ route('profile.edit') }}" class='sidebar-link'>
                <i class="bi bi-person-fill"></i>
                <span>Profile</span>
            </a>
        </li>
    </ul>
</div>
