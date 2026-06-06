@extends('layouts.app')
@section('title', 'Rekap Nilai & Koreksi Essay')

@push('link')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('styles')
<style>
    /* Premium UI Customization */
    .header-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    
    /* Accordion Customization */
    .accordion-custom .accordion-item {
        background-color: #ffffff;
        border: 1px solid rgba(0,0,0,0.05) !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s ease;
    }
    .accordion-custom .accordion-item:hover {
        transform: translateY(-2px);
    }
    .accordion-custom .accordion-button {
        border-radius: 16px !important;
        padding: 1.25rem 1.5rem;
    }
    .accordion-custom .accordion-button:focus {
        box-shadow: none;
        background-color: transparent;
    }
    .accordion-custom .accordion-button:not(.collapsed) {
        background-color: #f8faff;
        color: #0d6efd;
        box-shadow: inset 0 -1px 0 rgba(0,0,0,0.05);
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
    
    /* Table Styling */
    .table-premium thead th {
        background-color: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #edf2f7;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .table-premium tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s;
    }
    .table-premium tbody tr:hover {
        background-color: #fcfcfd;
    }
    .table-premium tbody td {
        vertical-align: middle;
        padding: 1rem 0.5rem;
    }
    
    /* Action Buttons */
    .btn-action {
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    
    <div class="header-card p-4 mb-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Rekap Nilai: <span class="text-primary">{{ $exam->name }}</span></h4>
            <div class="d-flex align-items-center text-muted small mt-2">
                <i class="bi bi-book text-secondary me-2"></i> Mata Pelajaran: 
                <span class="fw-bold text-dark ms-1">{{ $exam->questionBank->subject_name }}</span>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('teacher.cbt.results.export.excel', $exam->id) }}" class="btn btn-outline-success border-2 rounded-pill fw-medium px-4 shadow-sm btn-action">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Excel
            </a>
            <a href="{{ route('teacher.cbt.results.export.pdf', $exam->id) }}" class="btn btn-outline-danger border-2 rounded-pill fw-medium px-4 shadow-sm btn-action">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
            </a>
            <a href="{{ route('teacher.cbt.results.index') }}" class="btn btn-outline-secondary border-2 rounded-pill fw-medium px-4 shadow-sm btn-action ms-lg-2">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if($groupedExams->isEmpty())
    <div class="header-card py-5 text-center mt-4 border-0">
        <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
            <i class="bi bi-inbox fs-1 text-secondary"></i>
        </div>
        <h5 class="fw-bold text-dark">Data Kosong</h5>
        <p class="text-muted mb-0">Belum ada santri yang mulai atau mengumpulkan ujian ini.</p>
    </div>
    @else
    
    <div class="accordion accordion-custom" id="accordionClasses">
        @foreach($groupedExams as $className => $students)
        <div class="accordion-item mb-4">
            <h2 class="accordion-header" id="heading{{ Str::slug($className) }}">
                <button class="accordion-button fw-bold {{ $loop->first ? '' : 'collapsed' }} bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($className) }}">
                    <div class="d-flex align-items-center w-100 me-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-diagram-3-fill fs-5"></i>
                        </div>
                        <span class="fs-5 text-dark">{{ $className }}</span>
                        <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill ms-auto fw-medium fs-6">
                            <i class="bi bi-people-fill me-1"></i> {{ $students->count() }} Santri
                        </span>
                    </div>
                </button>
            </h2>
            <div id="collapse{{ Str::slug($className) }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#accordionClasses">
                <div class="accordion-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless table-premium align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4" width="5%">No</th>
                                    <th>Nama Santri</th>
                                    <th>Status</th>
                                    <th class="text-center">Nilai Akhir</th>
                                    <th class="text-end pe-4">Aksi Koreksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $se)
                                <tr>
                                    <td class="ps-4 text-muted fw-medium">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $se->cbtAccount->student->name }}</div>
                                    </td>
                                    <td>
                                        @if($se->status == 'finished')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fw-medium">
                                            <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                        </span>
                                        @else
                                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning px-3 py-2 rounded-pill fw-medium">
                                            <i class="bi bi-clock-history me-1"></i> Mengerjakan
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php 
                                            $scoreColor = $se->score >= 70 ? 'text-success' : 'text-danger'; 
                                            $bgOpacity = $se->score >= 70 ? 'bg-success' : 'bg-danger'; 
                                        @endphp
                                        <div class="d-inline-block px-3 py-1 rounded-3 {{ $bgOpacity }} bg-opacity-10 {{ $scoreColor }} fw-bold fs-5 border border-opacity-25 border-{{ $se->score >= 70 ? 'success' : 'danger' }}">
                                            {{ round($se->score) }}
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($se->status == 'finished')
                                        <a href="{{ route('teacher.cbt.results.correct', $se->id) }}" class="btn btn-sm btn-light text-primary border shadow-sm rounded-pill px-4 py-2 fw-medium btn-action">
                                            <i class="bi bi-pencil-square me-1"></i> Koreksi
                                        </a>
                                        @else
                                        <span class="badge bg-light text-secondary border px-4 py-2 rounded-pill">
                                            <i class="bi bi-lock-fill me-1"></i> Terkunci
                                        </span>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Konfigurasi SweetAlert Premium
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#0d6efd',
                confirmButtonText: 'Tutup',
                customClass: {
                    confirmButton: 'rounded-pill px-4'
                }
            });
        @endif
    });
</script>
@endpush