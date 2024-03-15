<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\Classroom;
use App\Models\Exercise;
use App\Models\Schedule;
use App\Models\LogParsonsProblem;
use Illuminate\Support\Facades\DB;


class LoggingController extends Controller
{
    public function index(Request $request): Application|Factory|View
    {
        $all_classrooms = Classroom::all();

        $exercises_pp2d = Exercise::where('type', 'pp2d')->get();
        $exercises_pp2d_package_ids = $exercises_pp2d->pluck('package_id');

        $schedules = Schedule::whereIn('package_id', $exercises_pp2d_package_ids)
        ->with('classrooms')
        ->paginate(10);

        return view('log.index', compact( 'schedules', 'all_classrooms'));
    }

    public function show(Classroom $classroom): Application|Factory|View
    {
        $studentIds = $classroom->students()->pluck('id');
        $logs = LogParsonsProblem::whereIn('student_id', $studentIds)->get();
        $processedData = $this->processLogsData($logs);
        // dd($classroom);

        // Ambil semua user yang memiliki student berdasarkan log
        $studentLogSummary = DB::table('log_parsons_problem')
            ->join('students', 'log_parsons_problem.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('students.classrooms_id', $classroom->id) // Filter berdasarkan classroom_id
            ->select('users.id as user_id', 'users.name', 'log_parsons_problem.status', DB::raw('count(*) as total'))
            ->groupBy('users.id', 'users.name', 'log_parsons_problem.status')
            ->get();

        
        $usersData = [];
        foreach ($studentLogSummary as $log) {
            if (!isset($usersData[$log->user_id])) {
                $usersData[$log->user_id] = [
                    'name' => $log->name,
                    'correct' => 0,
                    'incorrect' => 0
                ];
            }

            if ($log->status === 'correct') {
                $usersData[$log->user_id]['correct'] = $log->total;
            } else {
                $usersData[$log->user_id]['incorrect'] = $log->total;
            }
        }

        $logs = DB::table('log_parsons_problem')
        ->join('students', 'log_parsons_problem.student_id', '=', 'students.id')
        ->join('users', 'students.user_id', '=', 'users.id')
        ->where('students.classrooms_id', $classroom->id)
        ->groupBy('log_parsons_problem.exercise_id')
        ->select(
            'log_parsons_problem.exercise_id',
            DB::raw('SUM(case when log_parsons_problem.status = "incorrect" then 1 else 0 end) as total_errors')
        )
        ->get();

        // Menyiapkan data untuk grafik
        $exerciseIds = $logs->pluck('exercise_id');
        $totalErrors = $logs->pluck('total_errors');


        return view('log.show', compact('processedData',  'usersData', 'exerciseIds', 'totalErrors'));
    }

    protected function processLogsData($logs)
    {
        $result = [];

        foreach ($logs as $log) {
            $exerciseId = $log->exercise_id;

            if (!isset($result[$exerciseId])) {
                $result[$exerciseId] = [
                    'total_steps' => 0,
                    'errors' => 0,
                    'attempts' => 0
                ];
            }

            // Hitung jumlah step dalam log
            $steps = count($log->log); // Asumsikan $log->log adalah array
            $result[$exerciseId]['total_steps'] += $steps;

            // Hitung kesalahan
            if ($log->status === 'incorrect') {
                $result[$exerciseId]['errors'] += 1;
            }

            // Hitung percobaan
            $result[$exerciseId]['attempts'] += 1;
        }

        // Menghitung soal yang paling sering salah
        $mostErrors = 0;
        $exerciseMostErrors = null;
        foreach ($result as $exerciseId => $data) {
            if ($data['errors'] > $mostErrors) {
                $mostErrors = $data['errors'];
                $exerciseMostErrors = $exerciseId;
            }
        }

        if ($exerciseMostErrors !== null) {
            $result['exercise_most_errors'] = $exerciseMostErrors;
        }

        return $result;
    }

}
