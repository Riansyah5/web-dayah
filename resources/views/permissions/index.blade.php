@extends('layouts.app')
@section('title', 'Perizinan Santri')
@push('link')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold text-dark">Perizinan Santri</h4>
      <a href="{{ route('permissions.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
        <i class="bi bi-plus-lg me-2"></i>Buat Izin Baru
      </a>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-danger text-white rounded-4">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-0 text-white">Sedang Di Luar</h6>
                <h1 class="fw-bold mb-0 text-white">{{ $activePermissions->count() }}</h1>
              </div>
              <i class="bi bi-box-arrow-right fs-1 opacity-75"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item">
        <button class="nav-link active rounded-pill px-4" id="pills-active-tab" data-bs-toggle="pill"
          data-bs-target="#pills-active" type="button">
          Sedang Izin ({{ $activePermissions->count() }})
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link rounded-pill px-4" id="pills-history-tab" data-bs-toggle="pill"
          data-bs-target="#pills-history" type="button">
          Riwayat Izin
        </button>
      </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">

      <div class="tab-pane fade show active" id="pills-active">
        @php
          // Kelompokkan data izin berdasarkan asrama dari relasi student
          $groupedActivePermissions = $activePermissions->groupBy(function($perm) {
              return $perm->student->dormitory ?? 'Tanpa Asrama';
          })->sortKeys();
        @endphp

        <div class="mb-3">
          <input type="text" id="searchActivePermission" class="form-control rounded-pill px-4 shadow-sm border-0 py-2" placeholder="Cari nama santri yang sedang izin...">
        </div>

        <div class="accordion" id="accordionActivePermissions">
          @forelse($groupedActivePermissions as $dormitory => $perms)
            <div class="accordion-item border-0 shadow-sm mb-3 rounded-4 overflow-hidden">
              <h2 class="accordion-header" id="heading-{{ Str::slug($dormitory) }}">
                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} bg-white fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($dormitory) }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse-{{ Str::slug($dormitory) }}">
                  {{ $dormitory }} ({{ $perms->count() }} Santri)
                </button>
              </h2>
              <div id="collapse-{{ Str::slug($dormitory) }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading-{{ Str::slug($dormitory) }}" data-bs-parent="#accordionActivePermissions">
                <div class="accordion-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                      <thead class="bg-light">
                        <tr>
                          <th class="ps-4">Nama Santri</th>
                          <th>Kamar</th>
                          <th>Jenis</th>
                          <th>Petugas</th>
                          <th>Waktu Keluar</th>
                          <th>Batas Kembali</th>
                          <th>Sisa Waktu</th>
                          <th class="text-end pe-4">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($perms as $perm)
                          <tr>
                            <td class="ps-4">
                              <div class="fw-bold text-dark">{{ $perm->student->name }}</div>
                              <div class="small text-muted">{{ $perm->student->class_group ?? '-' }}</div>
                            </td>
                            <td>{{ $perm->student->room ?? '-' }}</td>
                            <td>
                              <span class="badge bg-warning text-dark">{{ ucfirst($perm->type) }}</span>
                            </td>
                            <td>
                              <strong>{{ $perm->user->name ?? '-' }}</strong>
                            </td>
                            <td>{{ $perm->start_date->locale('id')->translatedFormat('d M H:i') }}</td>
                            <td>
                              <div class="fw-bold text-danger">{{ $perm->end_date->locale('id')->translatedFormat('d M H:i') }}</div>
                            </td>
                            <td>
                              @if (now()->gt($perm->end_date))
                                <span class="text-danger fw-bold">Telat
                                  {{ $perm->end_date->locale('id')->diffForHumans(null, true) }}</span>
                              @else
                                <span class="text-success">Sisa
                                  {{ $perm->end_date->locale('id')->diffForHumans(null, true) }}</span>
                              @endif
                            </td>
                            <td class="text-end pe-4">
                              <a href="{{ route('permissions.print', $perm->id) }}" target="_blank"
                                class="btn btn-sm btn-outline-secondary me-1" title="Lihat dan Cetak Surat">
                                <i class="bi bi-printer"></i>
                              </a>
                              <a href="{{ route('permissions.downloadpdf', $perm->id) }}" target="_blank"
                                class="btn btn-sm btn-outline-warning me-1" title="Download PDF Surat Izin">
                                <i class="bi bi-download"></i>
                              </a>
                              <form action="{{ route('permissions.return', $perm->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-success text-white btn-return"
                                  data-name="{{ $perm->student->name }}">
                                  <i class="bi bi-check-lg me-1"></i> Kembali
                                </button>
                              </form>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="card border-0 shadow-sm rounded-4">
              <div class="card-body">
                <div class="text-center py-5 text-muted">Tidak ada santri yang sedang izin di luar.</div>
              </div>
            </div>
          @endforelse
        </div>
      </div>

      <div class="tab-pane fade" id="pills-history">
        <div class="row g-2 mb-3">
          <div class="col-md-8">
            <div class="input-group">
              <span class="input-group-text bg-white border-0 shadow-sm"><i class="bi bi-search"></i></span>
              <input type="text" id="searchHistory" class="form-control border-0 shadow-sm py-2" 
                     placeholder="Cari nama santri, alasan, atau petugas...">
            </div>
          </div>
          <div class="col-md-4">
            <input type="month" id="filterMonthHistory" class="form-control border-0 shadow-sm py-2" 
                   title="Filter Bulan & Tahun">
          </div>
        </div>

        <div id="history-container">
          <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="bg-light">
                    <tr>
                      <th class="ps-4">Nama Santri</th>
                      <th>Jenis</th>
                      <th>Petugas</th>
                      <th>Alasan</th>
                      <th>Tgl Keluar</th>
                      <th>Tgl Kembali</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($historyPermissions as $perm)
                      <tr>
                        <td class="ps-4 fw-medium">{{ $perm->student->name }}</td>
                        <td>{{ ucfirst($perm->type) }}</td>
                        <td><strong>{{ $perm->user->name ?? '-' }}</strong></td>
                        <td>{{ Str::limit($perm->reason, 30) }}</td>
                        <td>{{ $perm->start_date->format('d/m/y H:i') }}</td>
                        <td>{{ $perm->returned_at ? $perm->returned_at->format('d/m/y H:i') : '-' }}</td>
                        <td>
                          @if ($perm->status == 'late')
                            <span class="badge bg-danger">Terlambat</span>
                          @elseif($perm->status == 'returned')
                            <span class="badge bg-success">Tepat Waktu</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Data riwayat izin tidak ditemukan.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <div class="p-3">
                {{ $historyPermissions->appends(request()->query())->links('pagination::bootstrap-5') }}
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
@push('scripts')
  {{-- sweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // 1. Notifikasi Sukses (Toast Top-Center)
    @if (session('success'))
      const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });

      Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}'
      });
    @endif

    // 2. SweetAlert Konfirmasi Kembali
    document.querySelectorAll('.btn-return').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const name = this.getAttribute('data-name');

        Swal.fire({
          title: 'Konfirmasi Kembali',
          html: `Apakah santri <strong>${name}</strong> sudah kembali ke asrama?`,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#198754',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Sudah',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // 3. Simpan dan Pulihkan Tab Aktif Menggunakan LocalStorage
    document.addEventListener("DOMContentLoaded", function() {
      let activeTab = localStorage.getItem('activeTab');
      if (activeTab) {
        let tabElement = document.querySelector(`button[data-bs-target="${activeTab}"]`);
        if (tabElement) {
          tabElement.click(); 
        }
      }

      let tabButtons = document.querySelectorAll('button[data-bs-toggle="pill"]');
      tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function (e) {
          let target = e.target.getAttribute('data-bs-target');
          localStorage.setItem('activeTab', target);
        });
      });
    });

    // 4. Live Search untuk Santri yang Sedang Izin (Tab 1)
    const searchInput = document.getElementById('searchActivePermission');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const accordions = document.querySelectorAll('#accordionActivePermissions .accordion-item');

        accordions.forEach(accordion => {
          const rows = accordion.querySelectorAll('tbody tr');
          let hasVisibleRow = false;

          rows.forEach(row => {
            const nameElement = row.querySelector('.fw-bold.text-dark');
            if (nameElement) {
              const name = nameElement.textContent.toLowerCase();
              if (name.includes(filter)) {
                row.style.display = '';
                hasVisibleRow = true;
              } else {
                row.style.display = 'none';
              }
            }
          });
          accordion.style.display = hasVisibleRow ? '' : 'none';
        });
      });
    }

    // =========================================================
    // 5. AJAX Search, Filter Bulan & Pagination (Riwayat Izin)
    // =========================================================
    let searchDebounceTimer;
    const searchHistoryInput = document.getElementById('searchHistory');
    const filterMonthInput = document.getElementById('filterMonthHistory');
    const historyContainer = document.getElementById('history-container');

    // Fungsi Utama Fetch Data
    function fetchHistoryData(pageUrl = null) {
      const searchQuery = searchHistoryInput ? searchHistoryInput.value : '';
      const monthQuery = filterMonthInput ? filterMonthInput.value : '';

      let url = new URL(pageUrl || window.location.href);

      // Set/Remove Parameter Search
      if (searchQuery) url.searchParams.set('search_history', searchQuery);
      else url.searchParams.delete('search_history');

      // Set/Remove Parameter Month
      if (monthQuery) url.searchParams.set('month_history', monthQuery);
      else url.searchParams.delete('month_history');

      if (historyContainer) {
        historyContainer.style.transition = 'opacity 0.2s ease';
        historyContainer.style.opacity = '0.5';
      }

      fetch(url.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => response.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        const newContent = doc.getElementById('history-container').innerHTML;
        if(historyContainer) {
            historyContainer.innerHTML = newContent;
            historyContainer.style.opacity = '1';
        }

        window.history.pushState({}, "", url.toString());
      })
      .catch(error => {
        console.error('Error fetching history:', error);
        if(historyContainer) historyContainer.style.opacity = '1';
      });
    }

    // Trigger Saat Mengetik Search (dengan Debounce 400ms)
    if (searchHistoryInput) {
      searchHistoryInput.addEventListener('input', function() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => {
          fetchHistoryData();
        }, 400);
      });
    }

    // Trigger Saat Memilih Bulan
    if (filterMonthInput) {
      filterMonthInput.addEventListener('change', function() {
        fetchHistoryData();
      });
    }

    // Trigger Saat Klik Pagination
    document.addEventListener('click', function(e) {
      let paginationLink = e.target.closest('#history-container .pagination a');
      if (paginationLink) {
        e.preventDefault();
        fetchHistoryData(paginationLink.href);
      }
    });
  </script>
@endpush
