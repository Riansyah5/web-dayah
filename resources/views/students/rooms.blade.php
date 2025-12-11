@extends('layouts.app')
@section('title', 'Dorms')
@push('link')
@endpush
@push('styles')
  <style>
    /* Styling Tabs Modern */
    .nav-pills .nav-link {
      color: #6b7280;
      background-color: white;
      border: 1px solid #e5e7eb;
      margin-right: 10px;
      margin-bottom: 10px;
      border-radius: 50rem;
      /* Pill shape */
      padding: 0.5rem 1.25rem;
      font-weight: 500;
      transition: all 0.2s;
    }

    .nav-pills .nav-link.active {
      background-color: #4f46e5;
      /* Primary Color */
      color: white;
      border-color: #4f46e5;
      box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }

    .nav-pills .nav-link:hover:not(.active) {
      background-color: #f3f4f6;
      color: #111827;
    }

    /* Room Card Styling */
    .room-card {
      border: none;
      border-radius: 16px;
      background: white;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s, box-shadow 0.2s;
      height: 100%;
    }

    .room-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Header Kamar */
    .room-header {
      background: linear-gradient(to right, #f9fafb, #fff);
      border-bottom: 1px solid #f3f4f6;
      padding: 1rem;
      border-radius: 16px 16px 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* List Santri */
    .student-list-item {
      padding: 0.5rem 1rem;
      border-bottom: 1px solid #f3f4f6;
      display: flex;
      align-items: center;
      font-size: 0.9rem;
    }

    .student-list-item:last-child {
      border-bottom: none;
    }

    /* Small Avatar in List */
    .avatar-xs {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      color: white;
      font-size: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      margin-right: 10px;
      flex-shrink: 0;
    }

    .badge-count {
      background-color: #e0e7ff;
      color: #4338ca;
      font-size: 0.75rem;
      padding: 4px 8px;
      border-radius: 6px;
      font-weight: 600;
    }
  </style>
@endpush
@section('content')
  <div class="container py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
      <div>
        <h4 class="fw-bold text-dark mb-1">Denah Kamar Santri</h4>
        <p class="text-muted small mb-0">Memantau penempatan santri berdasarkan Asrama dan Kamar.</p>
      </div>
      <button class="btn btn-outline-secondary btn-sm mt-3 mt-md-0" onclick="window.print()">
        <i class="bi bi-printer me-2"></i>Cetak Laporan
      </button>
    </div>

    @if ($dormitories->isEmpty())
      <div class="text-center py-5">
        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="80" class="opacity-50 mb-3">
        <p class="text-muted">Belum ada data asrama/kamar yang terisi.</p>
      </div>
    @else
      <ul class="nav nav-pills mb-4 justify-content-center justify-content-md-start" id="pills-tab" role="tablist">
        @foreach ($dormitories as $dormName => $rooms)
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="pills-{{ Str::slug($dormName) }}-tab"
              data-bs-toggle="pill" data-bs-target="#pills-{{ Str::slug($dormName) }}" type="button" role="tab">
              <i class="bi bi-building me-2"></i>{{ $dormName }}
              <span
                class="badge bg-white text-primary ms-2 rounded-pill shadow-sm">{{ $rooms->flatten()->count() }}</span>
            </button>
          </li>
        @endforeach
      </ul>

      <div class="tab-content" id="pills-tabContent">
        @foreach ($dormitories as $dormName => $rooms)
          <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="pills-{{ Str::slug($dormName) }}"
            role="tabpanel">

            <div class="row g-4">
              @foreach ($rooms as $roomNumber => $students)
                <div class="col-md-6 col-lg-4 col-xl-3">
                  <div class="room-card">
                    <div class="room-header">
                      <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-2 text-primary">
                          <i class="bi bi-door-closed-fill"></i>
                        </div>
                        <div>
                          <h6 class="fw-bold text-dark mb-0">Kamar {{ $roomNumber }}</h6>
                        </div>
                      </div>
                      <span class="badge-count">{{ $students->count() }} Org</span>
                    </div>

                    <div class="py-2">
                      @foreach ($students as $student)
                        <div class="student-list-item">
                          @php
                            $colors = ['#4F46E5', '#059669', '#D97706', '#DC2626', '#7C3AED'];
                            $bg = $colors[rand(0, 4)];
                            $initial = substr($student->name, 0, 1);
                          @endphp
                          <div class="avatar-xs" style="background-color: {{ $bg }}">
                            {{ $initial }}
                          </div>
                          <div class="overflow-hidden">
                            <div class="text-truncate fw-medium text-dark">{{ $student->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                              {{ $student->class_group ?? 'Tanpa Kelas' }}
                              @if ($student->education_level)
                                &bull; {{ $student->education_level }}
                              @endif
                            </div>
                          </div>
                          <a href="{{ route('students.show', $student->id) }}"
                            class="ms-auto text-muted opacity-50 hover-opacity-100">
                            <i class="bi bi-box-arrow-up-right"></i>
                          </a>
                        </div>
                      @endforeach
                    </div>

                    <div class="px-3 py-2 bg-light rounded-bottom-4 border-top border-light">
                      <small class="text-muted d-block text-end">
                        Asrama {{ $dormName }}
                      </small>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

          </div>
        @endforeach
      </div>

    @endif
  </div>

  <style media="print">
    .btn,
    .nav-pills {
      display: none !important;
    }

    .tab-content {
      display: block !important;
    }

    .tab-pane {
      display: block !important;
      opacity: 1 !important;
    }

    .room-card {
      break-inside: avoid;
      border: 1px solid #ccc;
      box-shadow: none;
      margin-bottom: 20px;
    }
  </style>
@endsection
@push('scripts')
@endpush
