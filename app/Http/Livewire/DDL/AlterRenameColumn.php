<?php

namespace App\Http\Livewire\DDL;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Database;
use App\Models\Exercise;
use App\Http\Controllers\Exercises\DatabaseController;
use App\Models\Score;
use App\Models\LogExercise;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class AlterRenameColumn extends Component
{
    public $exercise, $nextQuestionUrl, $totalExercise, $schedule;
    public $previews, $numRows, $answerTable;
    public $userAnswer = [];
    public $old_name = [];
    public $new_name = [];
    public $show = [];
    public $answer, $answerTableName;

    public function mount($exercise)
    {
        $exercise = Exercise::find($exercise->id);
        $database = (new Database)->find($exercise->database_id);
        $databaseController = new DatabaseController;

        // get answer data
        $this->answerTableName = $exercise->answer['table'];
        $this->answer = $exercise->answer['columns'];

        // get preview data
        $previews = $databaseController->getPreviewTableDDL($database->id, $exercise);

        $tableName = array_merge($previews['tableName']['desc'], $previews['tableName']['additional']);
        $columnName = array_merge($previews['columnName']['desc'], $previews['columnName']['additional']);
        // shuffle data
        shuffle($tableName);
        shuffle($columnName);
        // set preview table
        $previews = [
            'tableName' => $tableName,
            'columnName' => $columnName,
        ];
        // search the longest array
        $numRows = max(
            count($tableName),
            count($columnName),
        );

        $this->exercise = $exercise;
        $this->previews = $previews;
        $this->numRows = $numRows;
        // $this->new_name = [$numRows];
        $this->old_name = $previews['columnName'];
        // $this->show = [$numRows];
    }

    public function render()
    {
        return view('livewire.ddl.alter-rename-column');
    }

    public function collapseShow($i)
    {
        if (!isset($this->show[$i]) || $this->show[$i] == '') {
            $this->show[$i] = 'show';
        } else {
            $this->show[$i] = '';
        }
    }

    public function setAnswerTable($i)
    {
        $this->answerTable = $this->previews['tableName'][$i] ?? '';
    }

    public function addAnswer($i)
    {
        $index = count($this->userAnswer);
        $answer = [
            'old_name' => $this->old_name[$i],
            'new_name' => $this->new_name[$i]
        ];
        $this->userAnswer[$index] = $answer;
        // $this->userAnswer['old_name'][$index] = $this->old_name[$i] ?? '';
        // $this->userAnswer['new_name'][$index] = $this->new_name[$i] ?? '';
    }

    public function deleteAnswer($i)
    {
        array_splice($this->userAnswer, $i, 1);
    }

    public function resetAnswer()
    {
        $this->userAnswer = [];
    }
    
    public function updateAnswerOrder($sortedData)
    {
        $data = [];
        foreach ($sortedData as $sort) {
            $data[] = $this->userAnswer[$sort['value']];
        }

        $this->userAnswer = $data;
    }

    public function submitAnswer()
    {
        // create array to store table name & column answer arrays
        $logExerciseAnswer = array(
            'name' => $this->answerTableName,
            'column' => $this->userAnswer
        );

        //get the confident tag
        // $conTag = $this->confidentTag($value);
        // create instance of Log Exercise, initialize common values
        $logExercise = new LogExercise();
        $logExercise->student_id = Student::where('user_id', Auth::user()->id)->first()->id;
        $logExercise->schedule_id = $this->schedule->id;
        $logExercise->exercise_id = $this->exercise->id;
        $logExercise->time = 0;
        $logExercise->answer = json_encode($logExerciseAnswer);

        // check whether the generated answer matches the correct answer
        $isTableNameCorrect = strcasecmp($this->answerTableName, $this->answerTable);
        $isCorrect = $this->compareAnswerArrays($this->answer, $this->userAnswer);


        if ($isCorrect && $isTableNameCorrect==0) {
            // set the status to correct, confident tag and save the log data
            $logExercise->status = 'correct';
            // $logExercise->confident= $conTag;
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
                $this->dispatchBrowserEvent('showSuccessToast', ['message' => 'Jawaban benar, mengarahkan ke soal berikutnya...']);
                $this->redirect($this->nextQuestionUrl);
            } else {
                $this->dispatchBrowserEvent('showDoneAlert', ['message' => 'Selamat, Kamu telah menyelesaikan seluruh latihan dengan benar']);
            }
        } else {
            // set the status to incorrect, confident tag and save the log data
            $logExercise->status = 'incorrect';
            // $logExercise->confident= $conTag;
            $logExercise->save();

            $this->dispatchBrowserEvent('showErrorToast', ['message' => 'Jawaban salah, silahkan periksa kembali']);
        }
    }

    private function compareAnswerArrays($array1, $array2)
    {
        foreach ($array1 as $key => $value) {
            $temp_array = [
                'old_name' => strtoupper($value['old_name']),
                'new_name' => strtoupper($value['new_name'])
            ];
            $array1[$key] = $temp_array;
        }

        foreach ($array2 as $key => $value) {
            $temp_array = [
                'old_name' => strtoupper($value['old_name']),
                'new_name' => strtoupper($value['new_name'])
            ];
            $array2[$key] = $temp_array;
        }

        if (count($array1) !== count($array2)) {
            return false;
        }
        foreach ($array1 as $key => $value) {
            if (!isset($array2[$key]) || $array2[$key] !== $value) {
                return false;
            }
        }
        return true;
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
        }else {
            $conTag = "tidak yakin";
        }
        return $conTag;
    }
}
