@extends('layouts.app')
@section('title', 'Atur Silabus Mata Pelajaran')
@push('link')
@endpush
@push('styles')
@endpush
@section('content')
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3><a href="{{ route('master.index', ['active_tab' => 'tab-subjects']) }}" class="btn btn-outline-secondary btn-sm rounded text-decoration-none"><i
            class="bi bi-arrow-left"></i> Kembali</a></h3>
        <h4 class="fw-bold mb-0">Atur Materi: {{ $subject->name }}</h4>
        <small class="text-muted">Kode: {{ $subject->code }} | Kelompok: {{ $subject->group }}</small>
      </div>
      <button onclick="document.getElementById('syllabusForm').submit()" class="btn btn-primary shadow-sm">
        <i class="bi bi-save me-2"></i> Simpan Materi
      </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-0">
        <form action="{{ route('master.syllabus.store', $subject->id) }}" method="POST" id="syllabusForm">
          @csrf
          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
              <thead class="bg-light text-center">
                <tr>
                  <th style="width: 15%;">Tingkat Kelas</th>
                  <th>Materi Semester Ganjil</th>
                  <th>Materi Semester Genap</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($levels as $level)
                  @php
                    // Helper ambil data lama
                    $levelSyllabus = $existingSyllabi[$level->id] ?? collect();
                    $ganjil = $levelSyllabus->where('semester', 'Ganjil')->first()?->topics ?? '';
                    $genap = $levelSyllabus->where('semester', 'Genap')->first()?->topics ?? '';
                  @endphp
                  <tr>
                    <td class="bg-light fw-bold text-center">
                      <small class="text-muted d-block">{{ $level->stage->code }}</small>
                      {{ $level->name }}
                    </td>
                    <td class="p-3">
                      <textarea name="syllabus[{{ $level->id }}][Ganjil]" class="form-control border-0 bg-light" rows="3"
                        placeholder="Cth: Thaharah, Wudhu, Sholat...">{{ $ganjil }}</textarea>
                    </td>
                    <td class="p-3">
                      <textarea name="syllabus[{{ $level->id }}][Genap]" class="form-control border-0 bg-light" rows="3"
                        placeholder="Cth: Puasa, Zakat, Haji...">{{ $genap }}</textarea>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </form>
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
