@extends('layouts.app')
@section('title', 'Kalender Akademik')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Kalender Akademik</h4>
        <p class="text-muted small">
          Menampilkan Tahun: <strong>{{ $viewedYear->name }} ({{ $viewedYear->semester }})</strong>
          @if ($viewedYear->id == $activeYear->id)
            <span class="badge bg-success ms-2">Aktif Saat Ini</span>
          @else
            <span class="badge bg-secondary ms-2">Arsip / Riwayat</span>
          @endif
        </p>
      </div>

      <form action="{{ route('calendar.index') }}" method="GET" class="d-flex gap-2">
        <select name="year_id" class="form-select" onchange="this.form.submit()">
          @foreach ($allYears as $year)
            <option value="{{ $year->id }}" {{ $viewedYear->id == $year->id ? 'selected' : '' }}>
              {{ $year->name }} - {{ $year->semester }} {{ $year->is_active ? '(Aktif)' : '' }}
            </option>
          @endforeach
        </select>
        <a href="{{ route('calendar.agenda', ['year_id' => $viewedYear->id]) }}"
          class="btn btn-outline-secondary text-nowrap">
          <i class="bi bi-list-task"></i> Lihat List
        </a>
      </form>
    </div>
{{-- 
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
          <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0">Tambah ke: {{ $viewedYear->name }} ({{ $viewedYear->semester }})</h6>
          </div>
          <div class="card-body">
            <form action="{{ route('calendar.store') }}" method="POST">
              @csrf

              <input type="hidden" name="academic_year_id" value="{{ $viewedYear->id }}">

              <div class="mb-3">
                <label class="form-label small text-muted">Judul Kegiatan</label>
                <input type="text" name="title" class="form-control" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Simpan Agenda</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-4">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
    </div> --}}
  </div>
  <div class="container py-4">

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Tambah Agenda ke: {{ $viewedYear->name }} ({{ $viewedYear->semester }})</h6>
            {{-- <h6 class="fw-bold mb-0"><a href="{{ route('calendar.agenda') }}" class="btn btn-success">Lihat Agenda</a></h6> --}}
          </div>
          <div class="card-body">
            <form action="{{ route('calendar.store') }}" method="POST">
              @csrf
              <input type="hidden" name="academic_year_id" value="{{ $viewedYear->id }}">

              <div class="mb-3">
                <label class="form-label small text-muted">Judul Kegiatan</label>
                <input type="text" name="title" class="form-control" placeholder="Cth: Awal Ramadhan" required>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Kategori</label>
                <select name="category" class="form-select">
                  <option value="academic">Akademik (Biru)</option>
                  <option value="islamic">PHBI / Islam (Hijau)</option>
                  <option value="boarding">Keasramaan (Kuning)</option>
                  <option value="holiday">Libur (Merah)</option>
                </select>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label small text-muted">Mulai</label>
                  <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-6">
                  <label class="form-label small text-muted">Selesai</label>
                  <input type="date" name="end_date" class="form-control" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Hijriah (Opsional)</label>
                <input type="text" name="hijri_date" class="form-control" placeholder="Auto generate...">
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Keterangan (Opsional)</label>
                <textarea name="description" class="form-control" placeholder="Keterangan Acara/Agenda..."></textarea>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_holiday" id="checkLibur">
                <label class="form-check-label small" for="checkLibur">Liburkan KBM</label>
              </div>

              <button type="submit" class="btn btn-primary w-100">Simpan Agenda</button>
            </form>
          </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body">
            <h6 class="fw-bold mb-3 small">Keterangan Warna</h6>
            <div class="d-flex flex-wrap gap-2">
              <span class="badge bg-primary">Akademik</span>
              <span class="badge bg-success">PHBI/Islam</span>
              <span class="badge bg-warning text-dark">Asrama</span>
              <span class="badge bg-danger">Libur</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-4">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4 border-0">
        <div class="modal-header border-0 bg-light">
          <h5 class="modal-title fw-bold" id="modalTitle">Judul Kegiatan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-success d-flex align-items-center mb-3">
            <i class="bi bi-moon-stars-fill me-2"></i>
            <span id="modalHijri" class="fw-bold"></span>
          </div>

          <div class="mb-3">
            <small class="text-muted d-block">Tanggal Masehi</small>
            <span id="modalDate" class="fw-bold fs-5"></span>
          </div>

          <div class="row">
            <div class="col-6">
              <small class="text-muted d-block">Kategori</small>
              <span id="modalCategory" class="badge bg-secondary"></span>
            </div>
            <div class="col-6">
              <small class="text-muted d-block">Status KBM</small>
              <span id="modalHoliday" class="fw-bold text-danger"></span>
            </div>

          </div>
          <div class="mt-3">
            <small class="text-muted d-block">Deskripsi</small>
            <p id="modalDescription" class="mb-0 text-dark"></p>
          </div>
        </div>
        <div class="modal-footer border-0">
          <form id="deleteForm" action="" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
              <i class="bi bi-trash me-1"></i> Hapus Agenda
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <style>
    /* Sedikit styling agar kalender lebih cantik */
    /* Fix SweetAlert agar muncul di atas Modal Bootstrap */
    .swal2-container {
      z-index: 2000 !important;
    }

    :root {
      --fc-border-color: #f1f5f9;
      --fc-button-text-color: #475569;
      --fc-button-bg-color: #ffffff;
      --fc-button-border-color: #e2e8f0;
      --fc-button-hover-bg-color: #f8fafc;
      --fc-button-hover-border-color: #cbd5e1;
      --fc-button-active-bg-color: #0d6efd;
      --fc-button-active-border-color: #0d6efd;
      --fc-today-bg-color: rgba(13, 110, 253, 0.05);
    }

    #calendar {
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Toolbar Styling */
    .fc-toolbar-title {
      font-size: 1.5rem !important;
      font-weight: 700;
      color: #1e293b;
    }

    .fc-button {
      border-radius: 8px !important;
      font-weight: 600;
      text-transform: capitalize;
      padding: 0.5rem 1rem !important;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
      transition: all 0.2s ease;
    }

    .fc-button-primary {
      background-color: var(--fc-button-bg-color) !important;
      border-color: var(--fc-button-border-color) !important;
      color: var(--fc-button-text-color) !important;
    }

    .fc-button-primary:hover {
      background-color: var(--fc-button-hover-bg-color) !important;
      border-color: var(--fc-button-hover-border-color) !important;
      color: #1e293b !important;
    }

    .fc-button-active {
      background-color: var(--fc-button-active-bg-color) !important;
      border-color: var(--fc-button-active-border-color) !important;
      color: #fff !important;
    }

    /* Header & Grid */
    .fc-theme-standard th {
      border: none;
      padding: 8px 0;
      background-color: #f8fafc;
    }

    .fc-col-header-cell-cushion {
      text-decoration: none;
      color: #64748b;
      font-weight: 600;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .fc-scrollgrid {
      border: 1px solid #e2e8f0 !important;
      border-radius: 12px;
      overflow: hidden;
    }

    .fc-daygrid-day {
      transition: background-color 0.2s;
    }
    
    .fc-daygrid-day:hover {
      background-color: #f5f5f5ff;
    }

    .fc-event {
      cursor: pointer;
      border: none;
      border-radius: 6px;
      padding: 0 6px;
      font-size: 0.85rem;
      margin-bottom: 3px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      transition: transform 0.1s;
    }

    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .fc-daygrid-day-number {
      text-decoration: none;
      color: #475569;
      font-weight: 600;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      margin: 4px;
      font-size: 0.9rem;
    }

    .fc-day-today .fc-daygrid-day-number {
      background-color: #cf00baff;
      color: #fff;
      font-size: 1.2rem;
      font-weight: bold;
    }

    .fc-day-today {
      background-color: transparent !important;
    }

    /* Remove ugly borders */
    .fc-theme-standard td, .fc-theme-standard .fc-scrollgrid {
        border-color: #f1f5f9;
    }
  </style>
@endsection
@push('scripts')
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
  {{-- sweetAlert --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Notifikasi Sukses
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false
      });
    @elseif (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        html: '{{ session('error') }}',
        showConfirmButton: true
      });
    @endif
  </script>
  <script>
    // Konfirmasi Hapus dengan SweetAlert
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      Swal.fire({
        title: 'Hapus Agenda?',
        text: "Agenda ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,listMonth'
        },

        // UPDATE PENTING: Kirim parameter year_id ke API Feed
        events: {
          url: "{{ route('calendar.feed') }}",
          extraParams: {
            year_id: "{{ $viewedYear->id }}" // Kirim ID tahun yang dipilih dropdown
          }
        },

        @if ($viewedYear->id != $activeYear->id)
          // Jika melihat arsip, loncat ke tanggal arsip tersebut (misal: awal tahun ajaran)
          initialDate: "{{ $viewedYear->created_at->format('Y-m-d') }}",
        @endif
        // events: "{{ route('calendar.feed') }}", // Ambil data dari API

        // Saat Event Diklik -> Munculkan Modal
        eventClick: function(info) {
          var event = info.event;
          var props = event.extendedProps;

          // Isi data ke Modal
          document.getElementById('modalTitle').innerText = event.title;
          document.getElementById('modalHijri').innerText = props.hijri || '-';
          document.getElementById('modalCategory').innerText = props.category;
          document.getElementById('modalDescription').innerText = props.description || '-';

          // Format Tanggal Masehi
          var start = event.start.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          });
          document.getElementById('modalDate').innerText = start;

          // Status Libur
          var holidayText = props.is_holiday ? "KBM DILIBURKAN" : "Masuk Seperti Biasa";
          document.getElementById('modalHoliday').innerText = holidayText;

          // Set Action URL untuk Tombol Hapus
          var deleteUrl = "{{ route('calendar.destroy', ':id') }}";
          deleteUrl = deleteUrl.replace(':id', event.id);
          document.getElementById('deleteForm').action = deleteUrl;

          // Tampilkan Modal
          var myModal = new bootstrap.Modal(document.getElementById('eventModal'));
          myModal.show();
        }
      });

      calendar.render();
    });
  </script>
@endpush
