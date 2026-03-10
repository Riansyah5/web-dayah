@extends('layouts.app')
@section('title', 'Rekap Nilai & Koreksi Essay')
@push('link')
@endpush
@push('styles')

@endpush
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">Rekap Nilai: <span class="text-primary">{{ $exam->name }}</span></h4>
      <div class="text-muted small">Mata Pelajaran: {{ $exam->questionBank->subject_name }}</div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('teacher.cbt.results.export.excel', $exam->id) }}" class="btn btn-success rounded-pill shadow-sm">
        <i class="bi bi-file-earmark-excel me-1"></i> Excel
      </a>
      <a href="{{ route('teacher.cbt.results.export.pdf', $exam->id) }}" class="btn btn-danger rounded-pill shadow-sm">
        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
      </a>
      <a href="{{ route('teacher.cbt.results.index') }}" class="btn btn-light rounded-pill border shadow-sm ms-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  @if($groupedExams->isEmpty())
  <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
    <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
    <h6 class="text-muted">Belum ada santri yang mengumpulkan ujian ini.</h6>
  </div>
  @else
  <div class="accordion shadow-sm rounded-4" id="accordionClasses">
    @foreach($groupedExams as $className => $students)
    <div class="accordion-item border-0 mb-3 rounded-4 overflow-hidden">
      <h2 class="accordion-header" id="heading{{ Str::slug($className) }}">
        <button class="accordion-button fw-bold {{ $loop->first ? '' : 'collapsed' }} bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($className) }}">
          <i class="bi bi-diagram-3-fill text-primary me-2"></i> {{ $className }}
          <span class="badge bg-secondary rounded-pill ms-auto me-3">{{ $students->count() }} Santri</span>
        </button>
      </h2>
      <div id="collapse{{ Str::slug($className) }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#accordionClasses">
        <div class="accordion-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="ps-4" width="5%">No</th>
                  <th>Nama Santri</th>
                  <th>Status</th>
                  <th class="text-center">Nilai Akhir</th>
                  <th class="text-end pe-4">Koreksi Essay</th>
                </tr>
              </thead>
              <tbody>
                @foreach($students as $index => $se)
                <tr>
                  <td class="ps-4">{{ $index + 1 }}</td>
                  <td class="fw-bold">{{ $se->cbtAccount->student->name }}</td>
                  <td>
                    @if($se->status == 'finished')
                    <span class="badge bg-success rounded-pill">Selesai</span>
                    @else
                    <span class="badge bg-warning text-dark rounded-pill">Mengerjakan</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @php $scoreColor = $se->score >= 70 ? 'text-success' : 'text-danger'; @endphp
                    <div class="fs-5 fw-bold {{ $scoreColor }}">{{ round($se->score) }}</div>
                  </td>
                  <td class="text-end pe-4">
                    @if($se->status == 'finished')
                    <a href="{{ route('teacher.cbt.results.correct', $se->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                      <i class="bi bi-pencil-square me-1"></i> Koreksi
                    </a>
                    @else
                    <span class="text-muted small fst-italic">Menunggu Selesai</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</div>
@endsection
@push('scripts')
@endpush
