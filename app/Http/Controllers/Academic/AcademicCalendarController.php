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

    public function index(Request $request)
    {
        // 1. Ambil Tahun Aktif (Default)
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->firstOrFail();

        // 2. Ambil List Semua Tahun (Untuk Dropdown Filter)
        $allYears = \App\Models\AcademicYear::orderBy('id', 'desc')->get();

        // 3. Tentukan Tahun Mana yang Mau Dilihat
        // Jika user memilih dari dropdown, pakai itu. Jika tidak, pakai yang aktif.
        $selectedYearId = $request->get('year_id', $activeYear->id);
        $viewedYear = \App\Models\AcademicYear::findOrFail($selectedYearId);

        return view('academic.calendar.index', compact('activeYear', 'viewedYear', 'allYears'));
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

    // Update juga untuk Feed (Agar kalender visualnya berubah)
    public function feed(Request $request)
    {
        // Terima parameter year_id dari request AJAX FullCalendar
        $yearId = $request->get('year_id');

        // Jika tidak ada parameter, cari yang aktif
        if (!$yearId) {
            $active = \App\Models\AcademicYear::where('is_active', true)->first();
            $yearId = $active->id ?? 0;
        }

        $events = \App\Models\AcademicCalendar::where('academic_year_id', $yearId)
            ->get()
            ->map(function ($event) {
                // ... (Mapping data sama seperti sebelumnya) ...
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d'),
                    'end' => $event->end_date ? $event->end_date->addDay()->format('Y-m-d') : $event->start_date->format('Y-m-d'),
                    'backgroundColor' => $event->color,
                    'borderColor' => $event->color,
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
        // 2. Ambil List Semua Tahun (Untuk Dropdown Filter)
        $allYears = \App\Models\AcademicYear::orderBy('id', 'desc')->get();

        // Logic Filter Tahun
        $selectedYearId = $request->get('year_id', $activeYear->id);
        $viewedYear = \App\Models\AcademicYear::findOrFail($selectedYearId);

        // 2. Filter Input (Bulan & Kategori)
        $startMonth = $request->start_month;
        $endMonth = $request->end_month;
        $filterCategory = $request->category; // academic, holiday, etc

        // 3. Query Data
        $query = \App\Models\AcademicCalendar::where('academic_year_id', $viewedYear->id)
            ->orderBy('start_date', 'asc');

        // Filter Range Bulan
        if ($startMonth && $endMonth) {
            if ($startMonth <= $endMonth) {
                $query->whereMonth('start_date', '>=', $startMonth)
                    ->whereMonth('start_date', '<=', $endMonth);
            } else {
                $query->where(function ($q) use ($startMonth, $endMonth) {
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

        return view('academic.calendar.agenda', compact('viewedYear', 'groupedEvents', 'startMonth', 'endMonth', 'filterCategory'));
    }
}
