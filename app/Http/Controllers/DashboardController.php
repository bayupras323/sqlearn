<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exercises\DatabaseController;
use App\Models\Classroom;
use App\Models\Database;
use App\Models\Exercise;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboardUser()
    {
        $user = Student::where('user_id', Auth::user()->id)->firstOrFail();

        if (!$user) {
            return view('errors.404', ['message' => 'Anda tidak memiliki kelas']);
        }

        $classroom = $user->classrooms()->first();

        if (!$classroom) {
            return view('errors.404', ['message' => 'Anda tidak memiliki kelas']);
        }

        $schedules = Schedule::with([
                'classrooms',
                'scores' => function ($query) use ($user) {
                    $query->where('student_id', $user->id);
                },
                'log_exercises' => function ($query) use ($user) {
                    $query->where('student_id', $user->id)->where('status', 'correct');
                },
                'package' => function ($query) {
                    $query->withCount('exercises as total_exercises');
                },
            ])
            ->whereHas('classrooms', function ($query) use ($user) {
                $query->where('classrooms.id', $user->classrooms->id);
            })
            ->orderByDesc('end_date')
            ->paginate(10);

        return view('dashboard.user.home', compact('schedules'));
    }

    public function startExam($id)
    {
        $schedule = Schedule::with(['classrooms'])
            ->where('id', $id)
            ->firstOrFail();

        // check status schedule
        if (Carbon::now() > $schedule->end_date) {
            return redirect()->route('dashboard.user');
        }

        // check user permission
        $user = Student::where('user_id', Auth::user()->id)->first();
        if (!$schedule->classrooms->contains($user->classrooms_id)) {
            return redirect()->route('dashboard.user');
        }

        $exerciseNum = Exercise::where('package_id', $schedule->package_id)->count();
        $tipe = $schedule->type === 'exam' ? 'Ujian' : 'Latihan';
        return view('dashboard.user.starts', compact('schedule', 'exerciseNum', 'tipe'));
    }

    // untuk menyesuaikan jadwal dengan paket soal yang dikerjakan
    //sesuai dengan jadwal yang dikerjakan (package dan tipe soal)
    public function exercise(Schedule $schedule)
    {
        // check status schedule
        if (Carbon::now() > $schedule->end_date) {
            return redirect()->route('dashboard.user');
        }

        // check user permission
        $user = Student::where('user_id', Auth::user()->id)->first();
        if (!$schedule->classrooms->contains($user->classrooms_id)) {
            return redirect()->route('dashboard.user');
        }

        $package_id = Schedule::findOrFail($schedule->id)
            ->package_id;

        $totalExercise = Exercise::where('package_id', $package_id)->count();

        $exercises = Exercise::where('package_id', $package_id)
            ->paginate(1, $columns = ['*'], $pageName = 'question');
 
        return view('dashboard.user.exercise', compact('exercises', 'totalExercise', 'schedule'));

    }
}
