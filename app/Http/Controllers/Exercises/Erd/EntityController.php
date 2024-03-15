<?php

namespace App\Http\Controllers\Exercises\Erd;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreErdEntity;
use App\Http\Requests\UpdateErdEntity;
use App\Http\Controllers\DatabaseController;
use App\Models\Exercise;
use App\Models\LogExercise;
use App\Models\Score;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Database;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class EntityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function newConnection($db_name)
    {
        $new_connection = 'database.connections.' . $db_name;
        Config::set($new_connection, [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $db_name,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ]);
    }
    public function get(Request $request)
    {
        $dbName = $request->db_name;
        $this->newConnection($dbName);
        $tables = [];
        foreach (DB::connection($dbName)->select('show tables') as $key => $value) {
            $value = json_decode(json_encode($value), true);
            $type = DB::connection($dbName)->select('describe ' . $value['Tables_in_' . $dbName]);
            $tables[] = [
                'key' => $key,
                'value' => $value['Tables_in_' . $dbName],
                'type' => $type,
            ];
        }
        return response()->json($tables, 200);
    }

    public function getData(Request $request)
    {
        $dbName = $request->db_name;
        $this->newConnection($dbName);
        $tables = [];
        foreach (DB::connection($dbName)->select('show tables') as $key => $value) {
            $value = json_decode(json_encode($value), true);
            $type = DB::connection($dbName)->select('describe ' . $value['Tables_in_' . $dbName]);
            $tables[] = [
                'key' => $key,
                'value' => $value['Tables_in_' . $dbName],
                'type' => $type,
            ];
        }
        $index = 0;
        $result = [];
        $nambah = 0;

        // Membuat entity shape rectangle
        $x = 0;
        $y = 300;
        foreach ($tables as $key_table => $t) {

            $nambah += 150;

            $result['cells'][$index]['type'] = 'standard.Rectangle';
            $result['cells'][$index]['position']['x'] = 75 + $nambah;
            $result['cells'][$index]['position']['y'] = 57;
            $result['cells'][$index]['size']['width'] = 100;
            $result['cells'][$index]['size']['height'] = 40;
            $result['cells'][$index]['id'] = "table_" . $key_table;
            $result['cells'][$index]['attrs']['body']['fill'] = 'red';
            $result['cells'][$index]['attrs']['body']['magnet'] = true;
            $result['cells'][$index]['attrs']['label']['fill'] = 'white';
            $result['cells'][$index]['attrs']['.outer']['fill'] = '#6777ef';
            $result['cells'][$index]['attrs']['.outer']['stroke'] = 'none';
            $result['cells'][$index]['attrs']['.outer']['filter']['name'] = 'dropShadow';
            $result['cells'][$index]['attrs']['.outer']['filter']['args']['dx'] = 0;
            $result['cells'][$index]['attrs']['.outer']['filter']['args']['dy'] = 2;
            $result['cells'][$index]['attrs']['.outer']['filter']['args']['blur'] = 1;
            $result['cells'][$index]['attrs']['.outer']['filter']['args']['color'] = '#333333';
            $result['cells'][$index]['attrs']['label']['text'] = $t['value'];
            // $result['cells'][$index]['attrs']['label']['magnet'] = true;
            // $result['cells'][$index]['ports']['groups']['bottom']['position']['name'] = 'bottom';
            // $result['cells'][$index]['ports']['groups']['bottom']['attrs']['portBody']['fill'] = 'white';
            // $result['cells'][$index]['ports']['groups']['bottom']['attrs']['portBody']['magnet'] = true;
            // $result['cells'][$index]['ports']['groups']['bottom']['attrs']['portBody']['r'] = 10;
            // $result['cells'][$index]['ports']['groups']['bottom']['attrs']['portBody']['stroke'] = 'blue';
            // $result['cells'][$index]['ports']['groups']['bottom']['label']['markup'][0]['tagName'] = 'text';
            // $result['cells'][$index]['ports']['groups']['bottom']['label']['markup'][0]['selector'] = 'label';
            // $result['cells'][$index]['ports']['groups']['bottom']['label']['markup'][0]['className'] = 'label-text';
            // $result['cells'][$index]['ports']['groups']['bottom']['markup'][0]['tagName'] = 'circle';
            // $result['cells'][$index]['ports']['groups']['bottom']['markup'][0]['selector'] = 'portBody';
            // $result['cells'][$index]['ports']['items'][0]['group'] = 'bottom';
            // $result['cells'][$index]['ports']['items'][0]['id'] = 'bottom';
            $index++;

            foreach ($t['type'] as $key => $value) {

                $result['cells'][$index]['type'] = 'standard.Ellipse';
                $result['cells'][$index]['position']['x'] = $x;
                $result['cells'][$index]['position']['y'] = $y;
                $result['cells'][$index]['size']['width'] = 100;
                $result['cells'][$index]['size']['height'] = 40;
                $result['cells'][$index]['id'] = "field_" . $key_table . $key;
                $result['cells'][$index]['attrs']['body']['fill'] = 'orange';
                $result['cells'][$index]['attrs']['label']['fill'] = 'black';
                $result['cells'][$index]['attrs']['.outer']['fill'] = '#6777ef';
                $result['cells'][$index]['attrs']['.outer']['stroke'] = 'none';
                $result['cells'][$index]['attrs']['.outer']['filter']['name'] = 'dropShadow';
                $result['cells'][$index]['attrs']['.outer']['filter']['args']['dx'] = 0;
                $result['cells'][$index]['attrs']['.outer']['filter']['args']['dy'] = 2;
                $result['cells'][$index]['attrs']['.outer']['filter']['args']['blur'] = 1;
                $result['cells'][$index]['attrs']['.outer']['filter']['args']['color'] = '#333333';
                $result['cells'][$index]['attrs']['label']['text'] = $value->Field;
                if ($result['cells'][$index]['attrs']['label']['text'] == 'id') {
                    $result['cells'][$index]['attrs']['label']['style']['textShadow'] = "1px 0 1px #333333";
                    $result['cells'][$index]['attrs']['label']['style']['textDecoration'] = "underline";
                }
                $index++;
                $x += 30;

                $result['cells'][$index]['type'] = 'standard.Link';
                $result['cells'][$index]['id'] = "Link_" . $key_table . $key;
                $result['cells'][$index]['source']['id'] = "table_" . $key_table;
                $result['cells'][$index]['target']['id'] = "field_" . $key_table . $key;
                $result['cells'][$index]['source']['magnet'] = 'portBody';
                // $result['cells'][$index]['attrs'] = new \stdClass();
                $result['cells'][$index]['attrs']['line']['targetMarker']['type'] = 'none';
                $index++;
            }
            $x += 30;
        }

        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(StoreErdEntity $request)
    {
        $validated = $request->validated();
        $validated['type'] = 'ERD';

        (new Exercise)->create($validated);
        $url = url('/') . '/packages/' . $request['package_id'] . '';
        return response()->json(['message' => 'success', 'url' => $url]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($exercise)
    {
        if ($exercise) {
            $result = json_decode(json_encode($exercise), true);
            $result['answer'] = json_decode($exercise->answer, true);
            $type = 'erd';
            $databaseSelected = Database::find($exercise->database_id);
            $oke = json_decode(json_encode($exercise), true);
            $test = json_decode($oke['answer'], true);
            $gas = $test;
            foreach ($gas['cells'] as $key => $value) {
                if ($value['type'] == 'standard.Link' || $value['type'] == 'standard.Ellipse' || $value['type'] == 'standard.Rectangle') {
                    unset($gas['cells'][$key]);
                }
            }
            $fixJson =  json_encode($result['answer']);
            return view(
                'packages.exercises.categories.ERD.show-entity',
                compact('type', 'databaseSelected', 'exercise', 'fixJson')
            );
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($exercise)
    {
        $databases = Database::all();
        if ($exercise) {
            $result = json_decode(json_encode($exercise), true);
            $result['answer'] = json_decode($exercise->answer, true);
            $type = 'erd';
            $databaseSelected = Database::find($exercise->database_id);
            $fixJson =  json_encode($result['answer']);
            return view(
                'packages.exercises.categories.ERD.edit-entity',
                compact('databases', 'type', 'fixJson', 'databaseSelected', 'exercise')
            );
        }
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateErdEntity $request)
    {
        $validated = $request->validated();
        $validated['type'] = 'ERD';
        Exercise::find($request->id)->update($validated);
        $url = url('/') . '/packages/' . $request['package_id'] . '';
        return response()->json(['message' => 'success', 'url' => $url]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        //
    }

    public function nextStep(Request $request)
    {
        try {
            $request->validate([
                'exercise_id'   => 'required',
                'schedule_id'   => 'required',
                'answer'        => 'required',
            ]);
            $exercs = Exercise::findOrFail($request->exercise_id);
            $exercise = json_decode($exercs->answer, true);
            $answers = json_decode($request->answer, true);
            $status = array();

            // cek jumlah cell
            if(count($answers['cells']) > count($exercise['cells'])){
                $status[0] = 0;
                $message_error = 'Jumlah shape Anda Kelebihan!';
                // return response()->json(['status' => 'incorrect', 'message' => 'Jumlah shape Anda Kelebihan!', 'url' => route('dashboard.user')], 200);
            } else if(count($answers['cells']) < count($exercise['cells'])){
                $status[0] = 0;
                $message_error = 'Jumlah shape Anda Kurang!';
                // return response()->json(['status' => 'incorrect', 'message' => 'Jumlah shape Anda kurang!', 'url' => route('dashboard.user')], 200);
            } else {
                $message_error = 'Jawaban Anda Masih Salah, Coba Periksa Lagi!';
            }

            if (count($exercise['cells']) != count($answers['cells'])) {
                $status[0] = 0;
            } else {
                $next = 0;
                foreach ($answers['cells'] as $key => $answer) {
                    if ($answer['type'] == 'standard.Link' || $answer['type'] == 'link' ) {
                        $status[$key] = 1;
                        continue;
                    } else {
                        foreach ($exercise['cells'] as $exercises) {
                            if ($answer['type'] == $exercises['type']) {
                                if ($answer['attrs']['label']['text'] == $exercises['attrs']['label']['text']) {
                                    $next = 1;
                                    // cek id
                                    if ($answer['attrs']['label']['text'] == 'id') {
                                        if (array_key_exists('textDecoration', $answer['attrs']['label']['style'])) {
                                            if ($answer['attrs']['label']['style']['textDecoration'] == 'underline') {
                                                $next = 1;
                                            }
                                        } else {
                                            $next = 0;
                                        }
                                    }
                                    // cek jenis attribute
                                    if($answer['attrs']['body']['fill'] != $exercises['attrs']['body']['fill']){
                                        $message_error = 'Terdapat attribute yang salah, Coba Periksa Lagi!';
                                        $next = 0;
                                    }
                                    break;
                                } else {
                                    $next = 0;
                                }
                            }
                        }
                    }
                    $status[$key] = $next;
                }
            }

            $studentId = Student::firstWhere('user_id', auth()->user()->id)->id;
            $lastLog = LogExercise::where('student_id', $studentId)->where('exercise_id', $exercs->id)->where('schedule_id', $request->schedule_id)->latest('id')->first();
            $lastLogCorrect = LogExercise::where([['student_id', $studentId], ['exercise_id', $exercs->id], ['schedule_id', $request->schedule_id], ['status', 'correct']])->first();
            if (!$lastLog || $lastLog->answer != $request->answer) {
                if($lastLogCorrect == null){
                    LogExercise::create([
                        'student_id' => $studentId,
                        'exercise_id' => $exercs->id,
                        'schedule_id' => $request->schedule_id,
                        'time' => Carbon::now(),
                        'answer' => $request->answer,
                        'status' => in_array(0, $status) ? 'incorrect' : 'correct',
                    ]);
                }
            }

            $finalLog = LogExercise::where('student_id', $studentId)->where('exercise_id', $exercs->id)->where('schedule_id', $request->schedule_id)->latest()->first();
            if ($finalLog->status == 'incorrect'){
                return response()->json(['status' => 'incorrect', 'message' => $message_error, 'url' => route('dashboard.user')], 200);
            }

            $totalquestions = Exercise::where('package_id', $exercs->package_id)->count();
            if ($totalquestions == 1 || $totalquestions == $request->question) {
                $benar = LogExercise::where('student_id', $studentId)->where('schedule_id', $request->schedule_id)->where('status', 'correct')->count();
                $score = 100 / $totalquestions;
                $score = $benar * $score;
                Score::create([
                    'student_id' => $studentId,
                    'schedule_id' => $request->schedule_id,
                    'score' => $benar
                ]);
                return response()->json(['status' => 'correct', 'url' => route('dashboard.user')], 200);
            } else {
                return response()->json(['status' => 'correct', 'url' => route('dashboard.user.exercise', ['schedule' => $request->schedule_id, 'question' => $request->question + 1])], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}