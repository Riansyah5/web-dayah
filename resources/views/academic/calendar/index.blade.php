@extends('layouts.app')
@section('title', 'Kalender Akademik')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="row g-4">

      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Tambah Agenda</h6>
            <h6 class="fw-bold mb-0"><a href="{{ route('calendar.agenda') }}" class="btn btn-success">Lihat Agenda</a></h6>
          </div>
          <div class="card-body">
            <form action="{{ route('calendar.store') }}" method="POST">
              @csrf
              <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">

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
            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus agenda ini?')">
              <i class="bi bi-trash me-1"></i> Hapus Agenda
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <style>
    /* Sedikit styling agar kalender lebih cantik */
    #calendar {
      font-family: sans-serif;
    }

    .fc-event {
      cursor: pointer;
      border: none;
    }

    .fc-daygrid-day-number {
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }

    .fc-col-header-cell-cushion {
      text-decoration: none;
      color: #555;
    }

    .fc-toolbar-title {
      font-size: 1.25rem !important;
      font-weight: bold;
    }

    .fc-button-primary {
      background-color: #0d6efd !important;
      border-color: #0d6efd !important;
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
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id', // Bahasa Indonesia
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,listMonth' // Bisa switch ke tampilan List
        },
        events: "{{ route('calendar.feed') }}", // Ambil data dari API

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
