<div class="sidebar-menu shadow-sm" style="min-height: 100vh;">
    <ul class="menu list-unstyled mt-3">
        {{-- Dashboard --}}
        <li class="sidebar-item {{ request()->is('home') ? 'active' : '' }}">
            <a href="/home" class='sidebar-link d-flex align-items-center gap-2'>
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Guru BK --}}
        @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'bk')
            <li
                class="sidebar-item has-sub {{ request()->is('tahun-akademik', 'kelas', 'guru', 'siswa', 'kategori-konseling') ? 'active' : '' }}">
                <a href="#" class='sidebar-link d-flex align-items-center gap-2'>
                    <i class="bi bi-folder-fill"></i>
                    <span>Data</span>
                </a>
                <ul class="submenu list-unstyled ps-3">
                    <li class="submenu-item {{ request()->is('tahun-akademik') ? 'active' : '' }}"><a
                            href="/tahun-akademik">Tahun Akademik</a></li>
                    <li class="submenu-item {{ request()->is('kelas') ? 'active' : '' }}"><a href="/kelas">Kelas</a>
                    </li>
                    <li class="submenu-item {{ request()->is('guru') ? 'active' : '' }}"><a href="/guru">Guru</a></li>
                    <li class="submenu-item {{ request()->is('siswa') ? 'active' : '' }}"><a href="/siswa">Siswa</a>
                    </li>
                    <li class="submenu-item {{ request()->is('kategori-konseling') ? 'active' : '' }}"><a
                            href="/kategori-konseling">Kategori Konseling</a></li>
                </ul>
            </li>

            <li class="sidebar-item {{ request()->is('permohonan-konseling') ? 'active' : '' }}">
                <a href="/permohonan-konseling" class='sidebar-link d-flex align-items-center gap-2'>
                    <i class="bi bi-envelope-fill"></i>
                    <span>Permohonan Konseling</span>
                    @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                    @if ($unread > 0)
                        <span class="badge bg-danger ms-2">{{ $unread }}</span>
                    @endif
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('jadwal-konseling') ? 'active' : '' }}">
                <a href="/jadwal-konseling" class='sidebar-link d-flex align-items-center gap-2'>
                    <i class="bi bi-calendar-fill"></i>
                    <span>Jadwal Konseling</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('laporan') ? 'active' : '' }}">
                <a href="/laporan" class='sidebar-link d-flex align-items-center gap-2'>
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Laporan</span>
                </a>
            </li>
        @endif

        {{-- Guru Wali Kelas --}}
        @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'walikelas')
            <li class="sidebar-item {{ request()->is('permohonan-konseling') ? 'active' : '' }}">
                <a href="/permohonan-konseling" class='sidebar-link d-flex align-items-center gap-2'>
                    <i class="bi bi-envelope-fill"></i>
                    <span>Permohonan Konseling</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->is('jadwal-konseling') ? 'active' : '' }}">
                <a href="/jadwal-konseling" class='sidebar-link d-flex align-items-center gap-2'>
                    <i class="bi bi-calendar-fill"></i>
                    <span>Jadwal Konseling</span>
                </a>
            </li>
        @endif

        {{-- Riwayat Konseling --}}
        <li class="sidebar-item {{ request()->is('riwayat-konseling') ? 'active' : '' }}">
            <a href="/riwayat-konseling" class='sidebar-link d-flex align-items-center gap-2'>
                <i class="bi bi-clock-fill"></i>
                <span>Riwayat Konseling</span>
            </a>
        </li>

        {{-- Orang Tua --}}
        @if (auth()->user()->role === 'orangtua')
            <li class="sidebar-item {{ request()->is('laporan') ? 'active' : '' }}">
                <a href="/laporan" class='sidebar-link d-flex align-items-center gap-2'>
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Laporan</span>
                </a>
            </li>
        @endif

        {{-- Siswa --}}
        @if (auth()->user()->role === 'siswa')
            <li class="sidebar-item {{ request()->is('permohonan-konseling') ? 'active' : '' }}">
                <a href="/permohonan-konseling" class='sidebar-link d-flex align-items-center gap-2'>
                    <i class="bi bi-envelope-fill"></i>
                    <span>Permohonan Konseling</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->is('jadwal-konseling') ? 'active' : '' }}">
                <a href="/jadwal-konseling" class='sidebar-link d-flex align-items-center gap-2'>
                    <i class="bi bi-calendar-fill"></i>
                    <span>Jadwal Konseling</span>
                </a>
            </li>
        @endif

        {{-- Kepala Sekolah --}}
        @if (auth()->user()->role === 'guru' && auth()->user()->guru && auth()->user()->guru->role_guru === 'kepala_sekolah')
            <li class="sidebar-item {{ request()->is('laporan') ? 'active' : '' }}">
                <a href="/laporan" class='sidebar-link d-flex align-items-center gap-2'>
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Laporan</span>
                </a>
            </li>
        @endif

        {{-- Profile --}}
        <li class="sidebar-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <a href="{{ route('profile.edit') }}" class='sidebar-link d-flex align-items-center gap-2'>
                <i class="bi bi-person-fill"></i>
                <span>Profile</span>
            </a>
        </li>
    </ul>
</div>
