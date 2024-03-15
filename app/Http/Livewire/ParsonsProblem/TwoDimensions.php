<?php

namespace App\Http\Livewire\ParsonsProblem;

use App\Helpers\SQLHelper;
use App\Models\Database;
use App\Services\ParsonsProblemService;
use App\Http\Controllers\Exercises\DatabaseController;
use App\Models\LogExercise;
use App\Models\LogParsonsProblem;
use App\Models\Score;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TwoDimensions extends Component
{
    public $currentPage;

    public $exercise, $nextQuestionUrl, $schedule, $encodeAnswer, $status;

    // query = mengambil query yang ada di database
    // query_table_name = mengambil nama tabel untuk diolah menjadi soal
    // query_table = mengambil data yang sesuai dengan query table

    public $query, $query_table_name, $query_table, $query_table_filtered;

    public $active_step = 1;

    public $db_name;

    public $steps = [
        1 => [
            "status" => "active",
            "filled" => false
        ],
        2 => [
            "status" => "",
            "filled" => false
        ]
    ];

    public $storeActivityLogs;

    public $selectedColumnQuery = [];
    public $selectedDataQuery = [];

    public $correct_log;
    public $correct_log_count;
    public $answer = ''; // Properti jawaban

    protected $listeners = [
        'updateAnswer' => 'handleUpdateAnswer',
        'storeActivityLogs' => 'handleUpdateLogs'
    ];

    public function handleUpdateAnswer($newAnswer)
    {
        $db = new DatabaseController();
        
        $correctAnswerQuery = $this->exercise->answer['queries'];
        $correctAnswerQuery = str_replace("\\n", '', $correctAnswerQuery);
        $correctAnswerQuery = $db->convertData($correctAnswerQuery);

        // create instance of Log Exercise, initialize common values
        $logExercise = new LogExercise();
        $logExercise->student_id = Student::where('user_id', Auth::user()->id)->first()->id;
        $logExercise->schedule_id = $this->schedule->id;
        $logExercise->exercise_id = $this->exercise->id;
        $logExercise->time = 0;
        $logExercise->answer = json_encode([$newAnswer]);

        // compare answare
        $correctAnswerQuery = str_replace(' ', '', $correctAnswerQuery);
        $newAnswer = str_replace(' ', '', $newAnswer);

        // corect
        if (trim($correctAnswerQuery) === trim($newAnswer)) {
              // set the status to correct, confident tag and save the log data
              $this->status = 'correct';
              $logExercise->status = $this->status;
              $logExercise->confident = "yakin";
              $logExercise->save();
  
              // insert score
              Score::updateOrCreate([
                  'student_id' => (new Student)->where('user_id', Auth::user()->id)->first()->id,
                  'schedule_id' => $this->schedule->id,
              ], [
                  'score' => DB::raw('score + 1')
              ]);
  
              // if there is any question left, redirect to the page. If not, return to home
              if (isset($this->nextQuestionUrl)) {
                  $this->dispatchBrowserEvent('showSuccessToast', ['message' => 'Jawaban benar, silahkan lanjut soal berikutnya...']);
              } else {
                  $this->dispatchBrowserEvent('showDoneAlert', ['message' => 'Selamat, Kamu telah menyelesaikan seluruh latihan dengan benar']);
              }

        } else {
            // set the status to incorrect, confident tag and save the log data
            $this->status = 'incorrect';
            $logExercise->status = $this->status;
            $logExercise->confident = "yakin";
            $logExercise->save();

            $this->dispatchBrowserEvent('showErrorToast', ['message' => 'Jawaban salah, silahkan periksa kembali']);
        }
    }

    // simpan log aktivitas
    public function handleUpdateLogs($activityLogs)
    {
        $logParsonsProblem = new LogParsonsProblem();
        $logParsonsProblem->student_id = Student::where('user_id', Auth::user()->id)->first()->id;
        $logParsonsProblem->schedule_id = $this->schedule->id;
        $logParsonsProblem->exercise_id = $this->exercise->id;
        $logParsonsProblem->time = 0;      
        $logParsonsProblem->log = $activityLogs;
        $logParsonsProblem->type = 'pp2d';
        $logParsonsProblem->status = $this->status;
        $logParsonsProblem->save();
    }
    
    // proses mengambil data untuk ditampilkan saat klik mulai ujian
    public function mount(ParsonsProblemService $parsonsProblemService)
    {
        // check history correct log
        $this->correct_log = LogExercise::where('student_id', (new Student)->where('user_id', Auth::user()->id)->first()->id)
            ->where('schedule_id', $this->schedule->id)
            ->where('exercise_id', $this->exercise->id)
            ->where('status', 'correct')->first();

        $this->correct_log_count = LogExercise::where('student_id', (new Student)->where('user_id', Auth::user()->id)->first()->id)
            ->where('schedule_id', $this->schedule->id)
            ->where('status', 'correct')->count();

        $this->query = $this->exercise->answer['queries'];

        $this->query_table_name = SQLHelper::getTableNames($this->query)[0];

        // mengambil nama database yang tersimpan di exercise
        $this->db_name = Database::findOrFail($this->exercise->database_id)->name;

        // crete instance of Database Controller to get the related tables data
        $db = new DatabaseController();

        $this->query_table = $db->getTableData($this->db_name, $this->query_table_name);
        
        $this->encodeAnswer = $parsonsProblemService->encodeJsParsosnPpTwoDimension($this->exercise->answer['queries']);
        
     }

    public function toggleSelectedColumn($value, $step, $removeOnly = false)
    {
        //determine if it's outer or inner query
        $selectedColumnQuery = $this->selectedColumnQuery;

        // filter array to remove false value
        $selectedColumnQuery = array_filter($selectedColumnQuery);

        //check if it's remove the data only
        if ($removeOnly) {
            $selectedColumnQuery = array_diff($selectedColumnQuery, [$value]);
        }

        //enable/disable next button
        if (count($selectedColumnQuery) == 0) {
            //disable next button
            $this->toggleStepFill($step, false);
        } else {
            //enable next button
            $this->toggleStepFill($step);
        }

        // re-assign the global variables with the new value
        $this->selectedColumnQuery = $selectedColumnQuery;
    }

    public function toggleSelectedData($value, $step, $removeOnly = false)
    {
        //determine if it's outer or inner query
        $selectedDataQuery = $this->selectedDataQuery;

        // filter array to remove false value
        $selectedDataQuery = array_filter($selectedDataQuery, function ($value) {
            return ($value !== NULL && $value !== FALSE && $value !== '');
        });

        //check if it's remove the data only
        if ($removeOnly) {
            $selectedDataQuery = array_diff($selectedDataQuery, [$value]);
        }

        //enable/disable next button
        if (count($selectedDataQuery) == 0) {
            //disable next button
            $this->toggleStepFill($step, false);
        } else {
            //enable next button
            $this->toggleStepFill($step);
        }

        // re-assign the global variables with the new value
        $this->selectedDataQuery = $selectedDataQuery;
    }


    private function toggleStepFill($step, $value = true)
    {
        $this->steps[$step]['filled'] = $value;
    }

    public function nextStep()
    {
        $steps = $this->steps;
        $active_step = $this->active_step;

        if ($steps[$active_step]['filled']) {
            // set current active step stepper to complete
            $steps[$active_step]['status'] = "complete";
            // increase step by one
            ++$active_step;
            // set current active step to active
            $steps[$active_step]['status'] = "active";
        }

        $this->steps = $steps;
        $this->active_step = $active_step;

        // filter selected query table
        $this->filterQueryTable();
    }

    public function previousStep()
    {
        $steps = $this->steps;
        $active_step = $this->active_step;

        // set current active step stepper to empty
        $steps[$active_step]['status'] = "";
        // decrease step by one
        $active_step;
        // set current active step to active
        $steps[$active_step]['status'] = "active";

        $this->steps = $steps;
        $this->active_step = $active_step;
    }


    private function filterQueryTable()
    {
        if ($this->active_step === 2) {
            $query_table = $this->query_table[0]['data'];
            $selected_columns = $this->selectedColumnQuery;

            $filtered_tables = array_map(fn ($value) => array_intersect_key($value, array_flip($selected_columns)), $query_table);

            $this->query_table_filtered = $filtered_tables;
        }
    }

    public function render()
    { 
        return view('livewire.parsons-problem.two-dimensions');
    }

    public function resetSelected($step)
    {
        switch ($step) {
            case 1:
                $this->selectedColumnQuery = [];
                break;
            case 2:
                $this->selectedDataQuery = [];
                break;
            default:
                break;
        }

        $this->toggleStepFill($step, false);
    }

    public function submitAnswer($value)
    { 
        // create instance of Database Controller to get the correct answer result
        $db = new DatabaseController();
        $answerQuery = $this->exercise->answer['queries'];

        // get the correct & generated answer array
        $correctAnswer = $db->getQueryData($this->db_name, $answerQuery);
        $generatedAnswer = array_values(array_intersect_key($this->query_table_filtered, array_flip($this->selectedDataQuery)));

        //get the confident tag
        $conTag = $this->confidentTag($value);

        // create instance of Log Exercise, initialize common values
        $logExercise = new LogExercise();
        $logExercise->student_id = Student::where('user_id', Auth::user()->id)->first()->id;
        $logExercise->schedule_id = $this->schedule->id;
        $logExercise->exercise_id = $this->exercise->id;
        $logExercise->time = 0;
        $logExercise->answer = json_encode($generatedAnswer);

        // check whether the generated answer matches the correct answer
        $isCorrect = $this->compareAnswerArrays($correctAnswer, $generatedAnswer);

        // if the generated answer is correct, continue to next question or return to home. If not, throw error message
        if ($isCorrect) {
            // set the status to correct, confident tag and save the log data
            $logExercise->status = 'correct';
            $logExercise->confident = $conTag;
            $logExercise->save();

            // insert score
            Score::updateOrCreate([
                'student_id' => (new Student)->where('user_id', Auth::user()->id)->first()->id,
                'schedule_id' => $this->schedule->id,
            ], [
                'score' => DB::raw('score + 1')
            ]);

            // if there is any question left, redirect to the page. If not, return to home
            if (isset($this->nextQuestionUrl)) {
                $this->dispatchBrowserEvent('showSuccessToast', ['message' => 'Jawaban benar, silahkan lanjut soal berikutnya...']);
            } else {
                $this->dispatchBrowserEvent('showDoneAlert', ['message' => 'Selamat, Kamu telah menyelesaikan seluruh latihan dengan benar']);
            }
        } else {
            // set the status to incorrect, confident tag and save the log data
            $logExercise->status = 'incorrect';
            $logExercise->confident = $conTag;
            $logExercise->save();

            $this->dispatchBrowserEvent('showErrorToast', ['message' => 'Jawaban salah, silahkan periksa kembali']);
        }
    }

    private function compareAnswerArrays($array1, $array2)
    {
        $array1_upper = $this->array_change_key_case_recursive($array1, CASE_UPPER);
        $array2_upper = $this->array_change_key_case_recursive($array2, CASE_UPPER);

        if (count($array1_upper) !== count($array2_upper)) {
            return false;
        }
        foreach ($array1_upper as $key => $value) {
            if (!isset($array2_upper[$key]) || $array2_upper[$key] !== $value) {
                return false;
            }
        }
        return true;
    }

    private function array_change_key_case_recursive($array, $case = CASE_UPPER)
    {
        $newArray = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $newArray[($case === CASE_LOWER ? strtolower($key) : strtoupper($key))] = $this->array_change_key_case_recursive($value, $case);
            } else {
                $newArray[($case === CASE_LOWER ? strtolower($key) : strtoupper($key))] = $value;
            }
        }
        return $newArray;
    }

    public function showConfirm()
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Apakah Anda Yakin?',
            'icon' => 'question',
            'confirmButtonColor' => '#33cc33',
            'showDenyButton' => true,
            'confirmButtonText' => 'Ya',
            'denyButtonText' => 'Tidak',
        ]);
    }

    public function confidentTag($value)
    {
        if ($value == true) {
            $conTag = "yakin";
        } else {
            $conTag = "tidak yakin";
        }
        return $conTag;
    }
}
