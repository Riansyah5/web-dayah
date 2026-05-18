@extends('layouts.app')
@section('title', 'Laporan Kinerja Guru')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Laporan Kinerja Guru (Jurnal KBM)</h4>

    <form id="filterForm" class="d-flex gap-2">
      <select name="level" class="form-select" style="min-width: 120px;">
        <option value="Wustha" {{ request('level', 'Wustha') == 'Wustha' ? 'selected' : '' }}>Wustha</option>
        <option value="Ulya" {{ request('level') == 'Ulya' ? 'selected' : '' }}>Ulya</option>
      </select>
      <select name="month" class="form-select" style="min-width: 150px;">
        @for ($i = 1; $i <= 12; $i++)
          <option value="{{ sprintf('%02d', $i) }}" {{ $month == $i ? 'selected' : '' }}>
            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
          </option>
        @endfor
      </select>
      <select name="year" class="form-select">
        @php $currentYear = date('Y'); @endphp
        @for ($y = $currentYear - 2; $y <= $currentYear + 2; $y++)
          <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
      </select>
    </form>
  </div>
  <h2 class="fw-bold">Periode: {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }} <span class="badge bg-primary rounded">{{ $level }}</span></h2>
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">No</th>
              <th class="ps-4 text-nowrap">Nama Guru</th>
              <th class="text-center text-nowrap">Tatap Muka (Jam)</th>
              <th class="text-center text-nowrap">Badal (Jam)</th>
              <th class="text-center text-nowrap">Total Mengajar</th>
              <th class="text-center text-danger text-nowrap">Total Jam Terhitung<br><span class="text-muted fw-light fs-6">({{ $level }})</span></th>
              <th class="text-center text-primary text-nowrap">Total Semua</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($recap as $index => $row)
            <tr>
              <td class="ps-4 fw-bold">{{ $index + 1 }}</td>
              <td class="ps-4 fw-bold text-nowrap">{{ $row['teacher']->name }}</td>
              <td class="text-center">{{ $row['main_count'] }}</td>
              <td class="text-center text-success fw-bold">
                {{ $row['sub_count'] > 0 ? '+' . $row['sub_count'] : '-' }}
              </td>
              <td class="text-center fw-bold fs-5">{{ $row['total_count'] }}</td>
              <td class="text-center text-nowrap"> <span class="badge bg-danger bg-opacity-10 rounded-pill text-danger px-3 fs-5">{{ $row['total_hours'] ?: 'belum ditentukan' }}</span>
              </td>
              <td class="text-center text-nowrap"> <span class="badge bg-primary bg-opacity-10 rounded-pill text-primary px-3 fs-5">{{ $row['total_hours_all'] > 0 ? $row['total_hours_all'] : '0' }}</span>
              </td>
              <td class="text-center text-nowrap"> <a href="{{ route('academic.report.teacher.detail', ['teacher' => $row['teacher']->id, 'month' => $month, 'year' => $year, 'level' => $level]) }}" class="btn btn-sm btn-outline-secondary rounded-pill">Detail<i class="bi bi-box-arrow-up-right small ms-1"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script>
  // Menggunakan Event Delegation pada document agar tidak hilang saat HTML direplace
  document.addEventListener('change', function(e) {
    if (e.target.matches('#filterForm select')) {
      const form = document.getElementById('filterForm');
      const url = new URL(window.location.href);
      
      const formData = new FormData(form);
      for (const [key, value] of formData.entries()) {
        url.searchParams.set(key, value);
      }

      // Update URL di browser (history state) tanpa me-reload halaman
      window.history.pushState({}, '', url);

      // Beri efek loading visual (opsional)
      const tableBody = document.querySelector('tbody');
      if (tableBody) tableBody.style.opacity = '0.5';

      // Lakukan permintaan Fetch (AJAX)
      fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          
          // Replace hanya pada pembungkus utama (container), halaman tidak berkedip
          document.querySelector('.container').innerHTML = doc.querySelector('.container').innerHTML;
        })
        .catch(error => console.error('Error fetching data:', error));
    }
  });

  // Tangani reload otomatis jika user menekan tombol Back/Forward di browser
  window.addEventListener('popstate', () => window.location.reload());
</script>
@endpush
