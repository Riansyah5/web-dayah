<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CbtResultExport implements FromView, ShouldAutoSize
{
    protected $exam;
    protected $groupedExams;

    public function __construct($exam, $groupedExams)
    {
        $this->exam = $exam;
        $this->groupedExams = $groupedExams;
    }

    public function view(): View
    {
        return view('cbt.teacher.results.export_excel', [
            'exam' => $this->exam,
            'groupedExams' => $this->groupedExams
        ]);
    }
}
