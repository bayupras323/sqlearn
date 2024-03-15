<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Classroom;
use App\Models\ClassroomSchedule;
use App\Models\LogExercise;
use App\Models\Package;
use App\Models\Schedule;
use App\Models\Score;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $classrooms = Classroom::all();
        $packages = Package::all();
        $schedules = Schedule::with(['package', 'classrooms'])
            ->when($request->has('schedule-keyword'), function ($query) use ($request) {
                $query->whereHas('package', function ($query) use ($request) {
                    $query->where('schedules.name', 'like', '%' . $request->input('schedule-keyword') . '%')
                        ->orWhere('packages.name', 'like', '%' . $request->input('schedule-keyword') . '%')
                        ->orWhere('type', 'like', '%' . $request->input('schedule-keyword') . '%');
                });
            })
            ->orderBy('end_date', 'desc')
            ->paginate(10);

        $timenow = Carbon::now('Asia/Jakarta');
        $now = $timenow->toDateTime();
        return view('schedules.index', compact(['schedules', 'classrooms', 'packages', 'now']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScheduleRequest $request)
    {
        $schedule = Schedule::create([
            'name' => $request['name'],
            'type' => $request['type'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'package_id' => $request['package_id']
        ]);

        if ($request['classroom'] != null) {
            $schedule->classrooms()->sync($request['classroom']);

        }

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScheduleRequest $request, $id)
    {
        $schedule = Schedule::find($id);
        $schedule->update([
            'name' => $request->name,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'package_id' => $request->package_id
        ]);

        if ($request->classroom != null) {
            $schedule->classrooms()->sync($request->classroom);
        }
        return redirect()->route('schedules.index')->with('success', 'Jadwal Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete all log exercises in this schedule
        LogExercise::where('schedule_id', $id)->delete();
        // delete all scores in this schedule
        Score::where('schedule_id', $id)->delete();
        // delete all classrooms scheduled in this schedule
        ClassroomSchedule::where('schedule_id', $id)->delete();
        // delete schedule
        $schedules = Schedule::find($id);
        $schedules->delete();
        return redirect()->route('schedules.index')->with('success', 'Jadwal Berhasil Dihapus');
    }
}
