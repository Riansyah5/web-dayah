@extends('layouts.app')
@section('title', 'Pengaturan Cetak Rapor')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Pengaturan Cetak Rapor</h4>
        <p class="text-muted small">Atur tanggal rapor dan penanda tangan untuk Tahun Ajaran:
          <strong>{{ $activeYear->name }} ({{ $activeYear->semester }})</strong></p>
      </div>
      <button onclick="document.getElementById('settingForm').submit()" class="btn btn-primary shadow-sm">
        <i class="bi bi-save me-2"></i> Simpan Pengaturan
      </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-0">
        <form action="{{ route('report.settings.store') }}" method="POST" id="settingForm">
          @csrf
          <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">

          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="ps-4">Jenjang</th>
                  <th width="20%">Kota TTD</th>
                  <th width="20%">Tanggal Rapor</th>
                  <th width="25%">Nama Kepala Sekolah</th>
                  <th width="20%">NIP / NIY</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($stages as $stage)
                  @php
                    $set = $settings->get($stage->id);
                  @endphp
                  <tr>
                    <td class="ps-4 fw-bold">
                      {{ $stage->name }}
                      <div class="small text-muted">{{ $stage->code }}</div>
                    </td>

                    {{-- Kota --}}
                    <td>
                      <input type="text" name="settings[{{ $stage->id }}][city]" class="form-control"
                        value="{{ $set->city ?? 'Kota Santri' }}" placeholder="Cth: Jakarta">
                    </td>

                    {{-- Tanggal --}}
                    <td>
                      <input type="date" name="settings[{{ $stage->id }}][report_date]" class="form-control"
                        value="{{ $set?->report_date ? $set->report_date->format('Y-m-d') : '' }}" required>
                    </td>

                    {{-- Kepala Sekolah --}}
                    <td>
                      <select name="settings[{{ $stage->id }}][headmaster_name]" class="form-select" required>
                        <option value="">-- Pilih Kepala Sekolah --</option>
                        @foreach ($employees as $employee)
                          <option value="{{ $employee->nama }}" @selected(old('settings.' . $stage->id . '.headmaster_name', $set->headmaster_name ?? '') == $employee->nama)>
                            {{ $employee->nama }}
                          </option>
                        @endforeach
                      </select>
                    </td>

                    {{-- NIP --}}
                    <td>
                      <input type="text" name="settings[{{ $stage->id }}][headmaster_nip]" class="form-control"
                        value="{{ $set->headmaster_nip ?? '' }}" placeholder="Opsional">
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </form>
      </div>
      <div class="card-footer bg-white py-3 text-muted small">
        <i class="bi bi-info-circle me-1"></i> Data ini akan muncul otomatis di bagian tanda tangan (footer) PDF Rapor.
      </div>
      
    </div>
  </div>
@endsection
@push('scripts')
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
    @endif
  </script>
@endpush
