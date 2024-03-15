<?php

namespace App\Http\Livewire\Erd;

use App\Helpers\SQLHelper;
use App\Http\Controllers\Exercises\DatabaseController;
use App\Models\Database;
use App\Models\Exercise;
use Livewire\Component;
use App\Models\Student;
use App\Models\LogExercise;
use Session;
use Illuminate\Support\Facades\Auth;

class EntityAttribute extends Component
{
    public $exercise;
    public $schedule;
    public $totalexercise;
    public $questionNumber;
    public function render()
    {
        $student = auth()->user()->students;
        $logExercise = LogExercise::where('student_id', $student->id)->where('exercise_id', $this->exercise->id)->where('schedule_id', $this->schedule->id)->latest()->first();
        $exercise = [];
        if (!$logExercise) {
            $fixJson = json_encode(['cells' => []]);
        } else {
            $fixJson = $logExercise->answer;
        }
        $done = '0';
        $bypass = '0';
        $questionNumberMust = null;
        $fixJsonDef = $fixJson;

        // cek apakah mahasiswa bypass soal
        $exercise_all = Exercise::where('package_id', $this->exercise->package_id)->pluck('id')->toArray();
        $log_exercise_id = LogExercise::where([['student_id', $student->id], ['schedule_id', $this->schedule->id], ['status', 'correct']])->distinct()->pluck('exercise_id');
        $nomor_soal = count($log_exercise_id) + 1;
        $posisi_soal = array_search($this->exercise->id, $exercise_all);
        if($this->questionNumber > $nomor_soal){
            $bypass = 1;
        }

        return view('livewire.erd.entity-attribute', compact('fixJson', 'done', 'bypass', 'questionNumberMust', 'fixJsonDef'));
    }
}
