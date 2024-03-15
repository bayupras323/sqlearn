<?php

namespace App\Exports;

use App\Models\Score;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ScoresExport implements FromView
{
    private $schedule;
    private $classroom;

    public function __construct($schedule, $classroom)
    {
        $this->schedule = $schedule;
        $this->classroom = $classroom;
    }

    public function view(): View
    {
        return view('scores.export', [
            'schedule' => $this->schedule,
            'classroom' => $this->classroom,
            'students' => Student::with(['classrooms', 'user' => function ($query) {
                $query->orderBy('name');
            }])
                ->where('classrooms_id', $this->classroom->id)
                ->orderBy('student_id_number', 'desc')
                ->get(),
            'scores' => Score::with(['students', 'schedules' => function ($query) {
                $query->where('classroom_id', $this->classroom->id)
                    ->whereHas('classrooms');
            }])
                ->where('schedule_id', $this->schedule->id)
                ->get()
        ]);
    }
}
