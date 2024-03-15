<?php

namespace App\Http\Controllers;

use App\Exports\ScoresExport;
use App\Models\Classroom;
use App\Models\LogExercise;
use App\Models\Schedule;
use App\Models\Score;
use App\Models\Student;
use App\Models\Exercise;
use BeyondCode\QueryDetector\Outputs\Log;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;

class ScoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:scores.index')->only('index');
        $this->middleware('permission:scores.show')->only('show');
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request): Application|Factory|View
    {
        // for filtering data
        $all_classrooms = Classroom::all();

        $schedules = Schedule::with(['package', 'scores', 'classrooms', 'classrooms.students'])
            ->when($request->has('schedule-keyword'), function ($query) use ($request) {
                if (!empty($request->get('schedule-keyword'))) {
                    return $query->where('name', 'LIKE', '%'.$request->get('schedule-keyword').'%');
                }
            })
            ->when($request->has('classroom-keyword'), function ($query) use ($request) {
                if (!empty($request->get('classroom-keyword'))) {
                    return $query->whereRelation('classrooms', 'classroom_id', $request->get('classroom-keyword'));
                }
            })
            ->where('start_date', '<=', Carbon::now())
            ->when(!$request->has('schedule-keyword'), function ($query) {
                return $query->orWhere('end_date', '<=', Carbon::now());
            })
            ->orderBy('end_date', 'desc')
            ->paginate(10);

        return view('scores.index', compact( 'schedules', 'all_classrooms'));
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Schedule $schedule): View|Factory|Application
    {
        $schedule = Schedule::with(['classrooms', 'classrooms.students', 'classrooms.students.user' => function ($query) {
                $query->orderBy('name');
            }])
            ->where('id', $schedule->id)
            ->first();

        $scores = Score::with('students')->where('schedule_id', $schedule->id)->get();
        return view('scores.show', compact('schedule', 'scores'));
    }

    public function log($schedule, $student): View|Factory|Application
    {
        $logs = LogExercise::with(['students', 'schedules','exercises' => function ($query) {
            // $query->orderBy('name');
        }])
            ->where('student_id', $student)
            ->where('schedule_id', $schedule)
            ->orderby('id',"asc")
            ->get();

        $percobaan = LogExercise::with(['students', 'schedules','exercises'])
            ->where('student_id', $student)
            ->where('schedule_id', $schedule)
            ->groupBy('exercise_id')
            ->selectRaw('COUNT(exercise_id)as jumlah, log_exercises.exercise_id')
            ->get();

        // dd($percobaan);
        
        $scores = Score::with('students')->where('schedule_id', $schedule)->get();
        return view('scores.log', compact('schedule', 'scores','student','logs','percobaan'));
    }

    public function summary(Schedule $schedule, Classroom $classroom)
    {

        $students = Student::with(['classrooms', 'user' => function ($query) {
            $query->orderBy('name');
        }])
            ->where('classrooms_id', $classroom->id)
            ->get();

        $scores = Score::with(['students', 'schedules' => function ($query) use ($classroom) {
            $query->where('classroom_id', $classroom->id)
                ->whereHas('classrooms');
        }])
            ->where('schedule_id', $schedule->id)
            ->get();

        $logs = LogExercise::with(['students', 'schedules','exercise' => function ($query) {
            // $query->orderBy('name');
        }])
            ->where('schedule_id', $schedule->id)
            ->orderby('student_id')
            ->get();
        // dd($logs);
        return view('scores.summary', compact('scores', 'students', 'schedule', 'classroom','logs'));
    }

    public function export(Schedule $schedule, Classroom $classroom)
    {
        $now = Carbon::now();
        return Excel::download(new ScoresExport($schedule, $classroom), strtolower("sqlearn_nilai_mahasiswa_{$schedule->name}_{$classroom->name}_{$now}.xlsx"));
    }
}
