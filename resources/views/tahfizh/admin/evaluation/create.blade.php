@extends('layouts.app')
@section('title', 'Generate Evaluasi Bulanan Guru Tahfizh')
@push('link')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Generate Evaluasi: {{ $month->translatedFormat('F Y') }}</h4>
    <a href="{{ route('tahfizh.admin.evaluations.index', ['month' => $month->format('Y-m')]) }}" class="btn btn-light rounded-pill border">Batal</a>
  </div>

  <form action="{{ route('tahfizh.admin.evaluations.store') }}" method="POST" id="evaluationForm">
    @csrf
    <input type="hidden" name="month_str" value="{{ $month->format('Y-m-d') }}">

    <div class="card border-0 shadow-sm rounded-4 mb-4">
      <div class="card-header bg-warning bg-opacity-10 text-warning-emphasis py-3">
        <i class="bi bi-info-circle me-2"></i>
        Data di bawah ini dikalkulasi otomatis dari Jurnal. <strong>Anda dapat mengubah angka secara manual</strong> jika diperlukan.
      </div>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">Guru</th>
              <th class="text-center" width="12%">Hadir (Hari)</th>
              <th class="text-center" width="12%">Badal (Kali)</th>
              <th class="text-center" width="12%">Izin (Hari)</th>
              <th class="text-center" width="12%">Alpha (Hari)</th>
              <th>Catatan (Opsional)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($previewData as $data)
            <tr>
              <td class="ps-4 fw-bold">{{ $data['name'] }}</td>

              <td>
                <input type="number" name="evaluations[{{ $data['teacher_id'] }}][hadir]" class="form-control text-center" value="{{ $data['hadir'] }}" min="0">
              </td>
              <td>
                <input type="number" name="evaluations[{{ $data['teacher_id'] }}][badal]" class="form-control text-center border-primary" value="{{ $data['badal'] }}" min="0">
              </td>
              <td>
                <input type="number" name="evaluations[{{ $data['teacher_id'] }}][izin]" class="form-control text-center" value="{{ $data['izin'] }}" min="0">
              </td>
              <td>
                <input type="number" name="evaluations[{{ $data['teacher_id'] }}][alpha]" class="form-control text-center border-danger" value="{{ $data['alpha'] }}" min="0">
              </td>
              <td>
                <input type="text" name="evaluations[{{ $data['teacher_id'] }}][notes]" class="form-control" placeholder="Keterangan...">
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="d-grid justify-content-end">
      <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
        <i class="bi bi-save me-2"></i> Simpan Evaluasi (Draft)
      </button>
    </div>
  </form>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.getElementById('evaluationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Simpan Evaluasi?',
      text: "Pastikan data yang diinput sudah benar. Data akan disimpan sebagai Draft.",
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Simpan!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        this.submit();
      }
    });
  });
</script>
@endpush
