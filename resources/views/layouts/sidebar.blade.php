@php
// Jika yang login adalah Superadmin, lewati pengecekan dan tampilkan semua menu.
// Jika bukan, ambil pengaturan dari database.
if (Auth::check() && Auth::user()->role == 'Superadmin') {
$sidebar = []; // Dengan array kosong, semua pengecekan `?? true` akan menghasilkan true.
} else {
$sidebar = \App\Models\SidebarSetting::pluck('is_active', 'menu_key');
}
@endphp

<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="{{ route('dashboard') }}" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        <img src="{{ asset('assets/images/logo-mataqu.svg') }}" alt="" class="logo" />
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item">
          <a href="{{ route('dashboard') }}" class="pc-link"><span class="pc-micon"><i class="ti ti-dashboard"></i></span><span class="pc-mtext">Dashboard</span></a>
        </li>
        <li class="pc-item">
          <a href="{{ route('user.show', Auth::user()->id) }}" class="pc-link"><span class="pc-micon"><i class="ti ti-lock"></i></span><span class="pc-mtext">Akun</span></a>
        </li>

        {{-- menu pegawai --}}
        <li class="pc-item">
          <a href="{{ route('pegawai.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span>
            <span class="pc-mtext">Pegawai</span>
          </a>
        </li>

        {{-- kalender akademik --}}
        <li class="pc-item">
          <a class="pc-link" href="{{ route('calendar.index') }}">
            <span class="pc-micon"><i class="ti ti-calendar"></i></span>
            <span class="pc-mtext">Kalender Akademik</span>
          </a>
        </li>

        {{-- menu KBM --}}
        @if($sidebar['menu_kbm'] ?? true)
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-id"></i></span><span class="pc-mtext">KBM</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('academic.journal.dashboard') }}">Absensi</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('academic.permission.index') }}">Riwayat Izin</a></li>
          </ul>
        </li>
        @endif

        @if($sidebar['menu_santri'] ?? true)
        {{-- menu santri --}}
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-school"></i></span><span class="pc-mtext">Santri</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('students.index') }}">Santri Aktif</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('alumni.index') }}">Alumni</a></li>
          </ul>
        </li>
        @endif

        @if($sidebar['menu_akademik'] ?? true)
        {{-- menu akademik --}}
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-book"></i></span><span class="pc-mtext">Akademik</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('classrooms.index') }}">Kelas</a></li>
            <li class="pc-item pc-hasmenu">
              <a href="#!" class="pc-link"><span class="pc-mtext">Rapor</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
              <ul class="pc-submenu">
                <li class="pc-item"><a class="pc-link" href="{{ route('grading.plotting.index') }}">Mapel & Guru</a>
                </li>
                <li class="pc-item"><a class="pc-link" href="{{ route('grading.teacher.index') }}">Input Nilai</a>
                </li>
                <li class="pc-item"><a class="pc-link" href="{{ route('report.settings.index') }}">Pengaturan
                    Rapor</a>
                </li>
                <li class="pc-item"><a class="pc-link" href="{{ route('grading.homeroom.index') }}">Leger &
                    Cetak</a>
                </li>
              </ul>
            </li>
            <li class="pc-item"><a class="pc-link" href="{{ route('graduation.index') }}">Kelulusan Massal</a></li>
          </ul>
        </li>
        @endif

        @if($sidebar['menu_tahfizh'] ?? true)
        {{-- menu tahfizh --}}
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-id"></i></span><span class="pc-mtext">Tahfizh</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.halaqah.index') }}">Halaqah</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.journal.dashboard') }}">Absensi</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.permission.create') }}">Pengajuan Izin</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.permission.index') }}">Riwayat Izin</a></li>
          </ul>
        </li>
        @endif
      
        @if($sidebar['menu_pengasuhan'] ?? true)
        {{-- pengasuhan --}}
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-home"></i></span><span class="pc-mtext">Pengasuhan</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('students.rooms') }}">Asrama</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('assignments.create') }}">Penempatan Kamar</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('permissions.index') }}">Perizinan</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('violations.dashboard') }}">Kedisiplinan</a></li>
          </ul>
        </li>
        @endif

        @if($sidebar['menu_cbt'] ?? true)
        {{-- CBT Admin--}}
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-home"></i></span><span class="pc-mtext">CBT Admin</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('admin.cbt.accounts.index') }}">Manajemen Akun</a></li>
            {{-- <li class="pc-item"><a class="pc-link" href="{{ route('admin.cbt.questions.index') }}">Manajemen Soal</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('admin.cbt.exams.index') }}">Manajemen Ujian</a></li> --}}
          </ul>
        </li>
        @endif


      {{-- master data, hanya untuk admin --}}
      @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Superadmin')
      <li class="pc-item pc-caption">
        <label>Master Data</label>
        <i class="ti ti-database"></i>
      </li>

      @if($sidebar['menu_kbm_admin'] ?? true)
      {{-- menu KBM --}}
      <li class="pc-item pc-hasmenu">
        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-id"></i></span>
          <span class="pc-mtext">KBM</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
        <ul class="pc-submenu">
          <li class="pc-item"><a class="pc-link" href="{{ route('academic.schedule.index') }}">Jadwal Pelajaran</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('academic.picket.index') }}">Monitoring</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('academic.report.teacher') }}">Rekap Absensi Guru</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('academic.report.student_subject') }}">Rekap Absensi Santri</a></li>
        </ul>
      </li>
      @endif

      @if($sidebar['menu_tahfizh_admin'] ?? true)
      {{-- menu tahfizh --}}
      <li class="pc-item pc-hasmenu">
        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-id"></i></span>
          <span class="pc-mtext">Tahfizh</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
        <ul class="pc-submenu">
          <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.admin.schedules.index') }}">Jadwal Halaqah</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.admin.monitoring.index') }}">Monitoring</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.admin.reports.teacher') }}">Rekap Absensi Guru</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.admin.reports.student') }}">Rekap Absensi Santri</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.setting.index') }}">Setting Rapor</a></li>
        </ul>
      </li>
      @endif

      @if($sidebar['menu_pegawai_admin'] ?? true)
      {{-- master data pegawai --}}
      <li class="pc-item pc-hasmenu">
        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-id"></i></span><span class="pc-mtext">Pegawai</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
        <ul class="pc-submenu">
          <li class="pc-item"><a class="pc-link" href="{{ route('kategori.index') }}">Kategori</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('jabatan.index') }}">Jabatan</a></li>
        </ul>
      </li>
      @endif
      {{-- end master data pegawai --}}

      @if(Auth::user()->role == 'Superadmin')
      {{-- master data User --}}
      <li class="pc-item pc-hasmenu">
        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-settings"></i></span><span class="pc-mtext">Akun</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
        <ul class="pc-submenu">
          <li class="pc-item"><a class="pc-link" href="{{ route('user.index') }}">Manajemen Akun</a></li>
        </ul>
      </li>
      {{-- end master data User --}}
      @endif

      @if($sidebar['menu_pengasuhan_admin'] ?? true)
      {{-- master data pengasuhan --}}
      <li class="pc-item pc-hasmenu">
        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-bed"></i></span><span class="pc-mtext">Pengasuhan</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
        <ul class="pc-submenu">
          <li class="pc-item"><a class="pc-link" href="{{ route('dorms.index') }}">Asrama</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('rooms.index') }}">Kamar</a></li>
        </ul>
      </li>
      {{-- end master data pengasuhan --}}
      @endif

      @if($sidebar['menu_akademik_admin'] ?? true)
      {{-- master data sekolah --}}
      <li class="pc-item pc-hasmenu">
        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-certificate"></i></span><span class="pc-mtext">Akademik</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
        <ul class="pc-submenu">
          <li class="pc-item"><a class="pc-link" href="{{ route('master.index') }}">Data Master</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('promotion.index') }}">Migrasi</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('promotion.promote_to_senior') }}">Lanjut SMA</a></li>
        </ul>
      </li>
      {{-- end master data sekolah --}}
      @endif

      @if(Auth::user()->role == 'Superadmin')
      {{-- Maintenance --}}
      <li class="pc-item pc-hasmenu">
        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-certificate"></i></span><span class="pc-mtext">Maintenance Sistem</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
        <ul class="pc-submenu">
          <li class="pc-item"><a class="pc-link" href="{{ route('system.maintenance.index') }}">Cleanup Akademik</a></li>
          <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.admin.cleanup.index') }}">Cleanup Tahfizh</a></li>
        </ul>
      </li>
      {{-- end Maintenance --}}

      {{-- Pengaturan Sidebar --}}
      <li class="pc-item pc-hasmenu">
        <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-settings"></i></span><span class="pc-mtext">Pengaturan</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
        <ul class="pc-submenu">
          <li class="pc-item"><a class="pc-link" href="{{ route('sidebar-settings.index') }}">Pengaturan Sidebar</a></li>
        </ul>
      </li>
      @endif
      @endif
      </ul>
      <div class="w-100 text-center">
        <div class="badge theme-version badge rounded-pill bg-light text-dark f-12"></div>
      </div>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->
