<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Traits\HijriConverter; // Panggil Trait tadi

class AcademicCalendarController extends Controller
{
    use HijriConverter;

    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // Ambil data untuk ditampilkan di kalender (JSON format nanti)
        // Tapi untuk sekarang kita tampilkan tabel list dulu
        $events = AcademicCalendar::where('academic_year_id', $activeYear->id)
            ->orderBy('start_date')
            ->get();

        return view('academic.calendar.index', compact('activeYear', 'events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required|date',
            'category' => 'required',
            'academic_year_id' => 'required',
            'description' => 'nullable',
        ]);

        $data = $request->all();

        // LOGIC HIJRIAH:
        // Jika user tidak isi manual, kita hitung otomatis
        if (empty($request->hijri_date)) {
            $data['hijri_date'] = $this->convertToHijriString($request->start_date);
        }

        // Checkbox handling
        $data['is_holiday'] = $request->has('is_holiday');

        AcademicCalendar::create($data);

        return back()->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function destroy(AcademicCalendar $calendar)
    {
        $calendar->delete();
        return back()->with('success', 'Agenda dihapus.');
    }

    public function feed(Request $request)
    {
        // Ambil tahun ajaran aktif
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return response()->json([]);
        }

        $events = \App\Models\AcademicCalendar::where('academic_year_id', $activeYear->id)
            ->get()
            ->map(function ($event) {
                // Format sesuai standar FullCalendar
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d'),

                    // Logika End Date: FullCalendar bersifat "Exclusive" untuk end date.
                    // Jadi kalau acara tgl 1-2, end date harus diset tgl 3 agar tgl 2 tetap terarsir.
                    'end' => $event->end_date ? $event->end_date->addDay()->format('Y-m-d') : $event->start_date->format('Y-m-d'),

                    'backgroundColor' => $event->color, // Dari Accessor Model yg kita buat di Tahap 1
                    'borderColor' => $event->color,

                    // Data tambahan untuk Modal Detail
                    'extendedProps' => [
                        'hijri' => $event->hijri_date,
                        'category' => ucfirst($event->category),
                        'description' => $event->description ?? '-',
                        'is_holiday' => $event->is_holiday
                    ]
                ];
            });

        return response()->json($events);
    }

    public function agenda(Request $request)
    {
        // 1. Ambil Tahun Ajaran Aktif
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->firstOrFail();

        // 2. Filter Input (Bulan & Kategori)
        $startMonth = $request->start_month;
        $endMonth = $request->end_month;
        $filterCategory = $request->category; // academic, holiday, etc

        // 3. Query Data
        $query = \App\Models\AcademicCalendar::where('academic_year_id', $activeYear->id)
            ->orderBy('start_date', 'asc');

        // Filter Range Bulan
        if ($startMonth && $endMonth) {
            if ($startMonth <= $endMonth) {
                $query->whereMonth('start_date', '>=', $startMonth)
                      ->whereMonth('start_date', '<=', $endMonth);
            } else {
                $query->where(function($q) use ($startMonth, $endMonth) {
                    $q->whereMonth('start_date', '>=', $startMonth)
                      ->orWhereMonth('start_date', '<=', $endMonth);
                });
            }
        } elseif ($startMonth) {
            $query->whereMonth('start_date', $startMonth);
        }

        // Jika user memilih kategori tertentu
        if ($filterCategory) {
            $query->where('category', $filterCategory);
        }

        $events = $query->get();

        // 4. Grouping Data per Bulan (Agar tampilan rapi: "Agustus", "September"...)
        // Kita group berdasarkan format "Y-m" (Tahun-Bulan) agar urut
        $groupedEvents = $events->groupBy(function ($event) {
            return $event->start_date->format('Y-m');
        });

        return view('academic.calendar.agenda', compact('activeYear', 'groupedEvents', 'startMonth', 'endMonth', 'filterCategory'));
    }
}
