<div class="card h-100 border-0 shadow-sm rounded-4 {{ $isBadal ? 'border-warning border-2' : '' }}">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between mb-3">
            <span class="badge {{ $isBadal ? 'bg-warning text-dark' : 'bg-light text-dark' }} rounded-pill">
                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
            </span>
            @if($isBadal)
                <span class="badge bg-dark">BADAL</span>
            @endif
        </div>

        <h5 class="fw-bold mb-1">{{ $schedule->subject->name }}</h5>
        <div class="mb-3 text-muted">
            Kelas: <strong>{{ $schedule->classroom->name }}</strong>
            @if($isBadal)
                <br><small class="text-danger">Menggantikan: {{ $schedule->teacher->name }}</small>
                @if(!empty($note)) <br><small class="fst-italic text-muted">"{{ $note }}"</small> @endif
            @endif
        </div>

        {{-- Logika Tombol --}}
        @php
            // Cek apakah sudah waktunya?
            $now = now();
            $start = \Carbon\Carbon::parse($schedule->start_time);
            $end = \Carbon\Carbon::parse($schedule->end_time);
            
            // Logic Sederhana: Tombol aktif 15 menit sebelum mulai sampai jam selesai
            $canEnter = $now->between($start->subMinutes(15), $end);
            
            // Cek apakah sudah absen (query ini sebaiknya di controller/eager load utk performa)
            $isDone = \App\Models\TeachingJournal::where('lesson_schedule_id', $schedule->id)->where('date', now()->format('Y-m-d'))->exists();
        @endphp

        @if($isDone)
            <button class="btn btn-success w-100 rounded-pill" disabled><i class="bi bi-check-circle me-2"></i> Sudah Absen</button>
        @elseif($canEnter)
            <a href="{{ route('academic.journal.create', $schedule->id) }}" class="btn btn-primary w-100 rounded-pill fw-bold">
                <i class="bi bi-camera me-2"></i> Masuk Kelas
            </a>
        @elseif($now > $end)
            <button class="btn btn-danger w-100 rounded-pill" disabled>Tidak Hadir (Alpha)</button>
        @else
            <button class="btn btn-secondary w-100 rounded-pill" disabled>Belum Waktunya</button>
        @endif
    </div>
</div>