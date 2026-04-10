@php
    // Pengaturan On/Off fitur dari database tetap berjalan
    $sidebar = \App\Models\SidebarSetting::pluck('is_active', 'menu_key');
@endphp

<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="{{ route('dashboard') }}" class="b-brand text-primary">
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

        @can('kelola-user-pegawai')
        <li class="pc-item">
          <a href="{{ route('pegawai.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span><span class="pc-mtext">Pegawai</span>
          </a>
        </li>
        @endcan

        @canany(['lihat-jadwal-pelajaran', 'kelola-jadwal-pelajaran'])
        <li class="pc-item">
          <a class="pc-link" href="{{ route('calendar.index') }}">
            <span class="pc-micon"><i class="ti ti-calendar"></i></span><span class="pc-mtext">Kalender Akademik</span>
          </a>
        </li>
        @endcanany

        @if($sidebar['menu_kbm'] ?? true)
            @canany(['isi-jurnal-guru', 'ajukan-izin-guru'])
            <li class="pc-item pc-hasmenu">
              <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-id"></i></span><span class="pc-mtext">KBM</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
              <ul class="pc-submenu">
                @can('isi-jurnal-guru')
                <li class="pc-item"><a class="pc-link" href="{{ route('academic.journal.dashboard') }}">Absensi</a></li>
                @endcan
                @can('ajukan-izin-guru')
                <li class="pc-item"><a class="pc-link" href="{{ route('academic.permission.index') }}">Riwayat Izin</a></li>
                @endcan
              </ul>
            </li>
            @endcanany
        @endif

        @if($sidebar['menu_santri'] ?? true)
            @canany(['lihat-data-santri', 'kelola-data-santri'])
            <li class="pc-item pc-hasmenu">
              <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-school"></i></span><span class="pc-mtext">Santri</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
              <ul class="pc-submenu">
                <li class="pc-item"><a class="pc-link" href="{{ route('students.index') }}">Santri Aktif</a></li>
                <li class="pc-item"><a class="pc-link" href="{{ route('alumni.index') }}">Alumni</a></li>
              </ul>
            </li>
            @endcanany
        @endif

        @if($sidebar['menu_akademik'] ?? true)
            @canany(['isi-nilai-mapel', 'kelola-leger-rapor', 'kelola-data-santri', 'lihat-kelas', 'kelola-kelas'])
            <li class="pc-item pc-hasmenu">
              <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-book"></i></span><span class="pc-mtext">Akademik</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
              <ul class="pc-submenu">
                <li class="pc-item"><a class="pc-link" href="{{ route('classrooms.index') }}">Kelas</a></li>
                <li class="pc-item pc-hasmenu">
                  <a href="#!" class="pc-link"><span class="pc-mtext">Rapor</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                  <ul class="pc-submenu">
                    @can('kelola-jadwal-pelajaran')
                    <li class="pc-item"><a class="pc-link" href="{{ route('grading.plotting.index') }}">Mapel & Guru</a></li>
                    @endcan
                    @can('isi-nilai-mapel')
                    <li class="pc-item"><a class="pc-link" href="{{ route('grading.teacher.index') }}">Input Nilai</a></li>
                    @endcan
                    @can('kelola-setting-rapor')
                    <li class="pc-item"><a class="pc-link" href="{{ route('report.settings.index') }}">Pengaturan Rapor</a></li>
                    @endcan
                    @can('kelola-leger-rapor')
                    <li class="pc-item"><a class="pc-link" href="{{ route('grading.homeroom.index') }}">Leger & Cetak</a></li>
                    @endcan
                  </ul>
                </li>
                @can('kelola-kelas')
                <li class="pc-item"><a class="pc-link" href="{{ route('graduation.index') }}">Kelulusan Massal</a></li>
                @endcan
              </ul>
            </li>
            @endcanany
        @endif

        @if($sidebar['menu_tahfizh'] ?? true)
            @canany(['kelola-halaqah', 'isi-jurnal-tahfizh', 'ajukan-izin-guru'])
            <li class="pc-item pc-hasmenu">
              <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-id"></i></span><span class="pc-mtext">Tahfizh</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
              <ul class="pc-submenu">
                @canany(['lihat-halaqah', 'kelola-halaqah'])
                <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.halaqah.index') }}">Halaqah</a></li>
                @endcan
                @can('isi-jurnal-tahfizh')
                <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.journal.dashboard') }}">Absensi</a></li>
                @endcan
                @can('ajukan-izin-guru')
                {{-- <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.permission.create') }}">Pengajuan Izin</a></li> --}}
                <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.permission.index') }}">Riwayat Izin</a></li>
                @endcan
              </ul>
            </li>
            @endcanany
        @endif

        @if($sidebar['menu_pengasuhan'] ?? true)
            @canany(['lihat-asrama', 'kelola-asrama-kamar', 'kelola-perizinan-santri', 'kelola-pelanggaran'])
            <li class="pc-item pc-hasmenu">
              <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-home"></i></span><span class="pc-mtext">Pengasuhan</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
              <ul class="pc-submenu">
                @canany(['lihat-asrama', 'kelola-asrama-kamar'])
                <li class="pc-item"><a class="pc-link" href="{{ route('students.rooms') }}">Asrama</a></li>
                @endcan
                @can('kelola-asrama-kamar')
                <li class="pc-item"><a class="pc-link" href="{{ route('assignments.create') }}">Penempatan Kamar</a></li>
                @endcan
                @can('kelola-perizinan-santri')
                <li class="pc-item"><a class="pc-link" href="{{ route('permissions.index') }}">Perizinan</a></li>
                @endcan
                @can('kelola-pelanggaran')
                <li class="pc-item"><a class="pc-link" href="{{ route('violations.dashboard') }}">Kedisiplinan</a></li>
                @endcan
              </ul>
            </li>
            @endcanany
        @endif

        @if($sidebar['menu_cbt_guru'] ?? true)
            @canany(['kelola-bank-soal', 'koreksi-hasil-ujian'])
            <li class="pc-item pc-hasmenu">
              <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-device-laptop"></i></span><span class="pc-mtext">CBT Guru</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
              <ul class="pc-submenu">
                @can('kelola-bank-soal')
                <li class="pc-item"><a class="pc-link" href="{{ route('teacher.cbt.banks.index') }}">Bank Soal</a></li>
                @endcan
                @can('koreksi-hasil-ujian')
                <li class="pc-item"><a class="pc-link" href="{{ route('teacher.cbt.results.index') }}">Rekap Nilai & Koreksi</a></li>
                @endcan
              </ul>
            </li>
            @endcanany
        @endif

        @canany(['kelola-master-data', 'kelola-user-pegawai', 'kelola-jadwal-pelajaran', 'pantau-tahfizh-admin', 'kelola-akun-cbt', 'kelola-pengaturan-sistem'])
        <li class="pc-item pc-caption">
          <label>Admin Panel</label>
          <i class="ti ti-database"></i>
        </li>

            @if($sidebar['menu_kbm_admin'] ?? true)
                @canany(['kelola-jadwal-pelajaran', 'kelola-piket-badal'])
                <li class="pc-item pc-hasmenu">
                  <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-id"></i></span><span class="pc-mtext">Admin KBM</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                  <ul class="pc-submenu">
                    <li class="pc-item"><a class="pc-link" href="{{ route('academic.schedule.index') }}">Jadwal Pelajaran</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('academic.picket.index') }}">Monitoring Piket</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('academic.report.teacher') }}">Rekap Absensi Guru</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('academic.report.student_subject') }}">Rekap Absensi Santri</a></li>
                  </ul>
                </li>
                @endcanany
            @endif

            @if($sidebar['menu_tahfizh_admin'] ?? true)
                @canany(['kelola-jadwal-tahfizh', 'pantau-tahfizh-admin'])
                <li class="pc-item pc-hasmenu">
                  <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-book"></i></span><span class="pc-mtext">Admin Tahfizh</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                  <ul class="pc-submenu">
                    @can('kelola-jadwal-tahfizh')
                    <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.admin.schedules.index') }}">Jadwal Halaqah</a></li>
                    @endcan
                    @can('pantau-tahfizh-admin')
                    <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.admin.monitoring.index') }}">Monitoring</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.admin.reports.teacher') }}">Rekap Absen Guru</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.admin.reports.student') }}">Rekap Absen Santri</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.setting.index') }}">Setting Rapor</a></li>
                    @endcan
                  </ul>
                </li>
                @endcanany
            @endif

            @if($sidebar['menu_cbt_admin'] ?? true)
                @canany(['kelola-akun-cbt', 'kelola-jadwal-ujian-cbt', 'pantau-ujian-cbt'])
                <li class="pc-item pc-hasmenu">
                  <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-server"></i></span><span class="pc-mtext">Admin CBT</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                  <ul class="pc-submenu">
                    @can('kelola-akun-cbt')
                    <li class="pc-item"><a class="pc-link" href="{{ route('admin.cbt.accounts.index') }}">Manajemen Akun</a></li>
                    @endcan
                    @canany(['kelola-jadwal-ujian-cbt', 'pantau-ujian-cbt'])
                    <li class="pc-item"><a class="pc-link" href="{{ route('admin.cbt.exams.index') }}">Jadwal Ujian</a></li>
                    @endcanany
                  </ul>
                </li>
                @endcanany
            @endif

            @if($sidebar['menu_pegawai_admin'] ?? true)
                @can('kelola-user-pegawai')
                <li class="pc-item pc-hasmenu">
                  <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-users"></i></span><span class="pc-mtext">Data Pegawai</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                  <ul class="pc-submenu">
                    <li class="pc-item"><a class="pc-link" href="{{ route('kategori.index') }}">Kategori</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('jabatan.index') }}">Jabatan</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('user.index') }}">Manajemen Akun</a></li>
                  </ul>
                </li>
                @endcan
            @endif

            @if($sidebar['menu_pengasuhan_admin'] ?? true)
                @can('kelola-asrama-kamar')
                <li class="pc-item pc-hasmenu">
                  <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-bed"></i></span><span class="pc-mtext">Data Pengasuhan</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                  <ul class="pc-submenu">
                    <li class="pc-item"><a class="pc-link" href="{{ route('dorms.index') }}">Asrama</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('rooms.index') }}">Kamar</a></li>
                  </ul>
                </li>
                @endcan
            @endif

            @if($sidebar['menu_akademik_admin'] ?? true)
                @can('kelola-master-data')
                <li class="pc-item pc-hasmenu">
                  <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-certificate"></i></span><span class="pc-mtext">Master Sekolah</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                  <ul class="pc-submenu">
                    <li class="pc-item"><a class="pc-link" href="{{ route('master.index') }}">Data Master</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('promotion.index') }}">Migrasi Kelas</a></li>
                    <li class="pc-item"><a class="pc-link" href="{{ route('promotion.promote_to_senior') }}">Lanjut SMA</a></li>
                  </ul>
                </li>
                @endcan
            @endif

            @can('kelola-pengaturan-sistem')
            <li class="pc-item pc-hasmenu">
              <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-settings"></i></span><span class="pc-mtext">Sistem</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
              <ul class="pc-submenu">
                <li class="pc-item"><a class="pc-link" href="{{ route('sidebar-settings.index') }}">Pengaturan Sidebar</a></li>
                <li class="pc-item"><a class="pc-link" href="{{ route('system.maintenance.index') }}">Cleanup Akademik</a></li>
                <li class="pc-item"><a class="pc-link" href="{{ route('tahfizh.admin.cleanup.index') }}">Cleanup Tahfizh</a></li>
              </ul>
            </li>
            @endcan

        @endcanany

      </ul>
    </div>
  </div>
</nav>