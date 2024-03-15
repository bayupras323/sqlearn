<?php

namespace App\Http\Livewire\DDL;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Database;
use App\Models\Exercise;
use App\Http\Controllers\Exercises\DatabaseController;
use App\Models\LogExercise;
use App\Models\Score;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class AlterDropColumn extends Component
{
    public $exercise, $nextQuestionUrl, $totalExercise, $schedule;
    public $previews, $numRows, $answerTable;
    public $userAnswer = [];
    public $answer, $answerTableName;

    public function mount($exercise)
    {
        $exercise = Exercise::find($exercise->id);
        $database = (new Database)->find($exercise->database_id);
        $databaseController = new DatabaseController;

        // get preview data
        $preview = $databaseController->getPreviewTableDDL($database->id, $exercise);

        $tableName = array_merge($preview['tableName']['desc'], $preview['tableName']['additional']);
        $columnName = array_merge($preview['columnName']['desc'], $preview['columnName']['additional']);
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

        // get answer data
        $this->answerTableName = $exercise->answer['table'];
        $this->answer = $exercise->answer['column'];

        $this->exercise = $exercise;
        $this->previews = $previews;
        $this->numRows = $numRows;
    }

    public function render()
    {
        return view('livewire.ddl.alter-drop-column');
    }

    public function setAnswerTable($i)
    {
        $this->answerTable = $this->previews['tableName'][$i] ?? '';
    }

    public function addAnswer($i)
    {
        $index = count($this->userAnswer);
        $this->userAnswer[$index] = $this->previews['columnName'][$i] ?? '';
    }

    public function deleteAnswer($i)
    {
        array_splice($this->userAnswer, $i, 1);
    }

    public function resetAnswer()
    {
        $this->userAnswer = [];
    }

    public function submitAnswer()
    {
        // create array to store table name & column answer arrays
        $logExerciseAnswer = array(
            'name' => $this->answerTableName,
            'column' => $this->userAnswer
        );
        
        // create instance of Log Exercise, initialize common values
        $logExercise = new LogExercise();
        $logExercise->student_id = Student::where('user_id', Auth::user()->id)->first()->id;
        $logExercise->schedule_id = $this->schedule->id;
        $logExercise->exercise_id = $this->exercise->id;
        $logExercise->time = 0;
        $logExercise->answer = json_encode($logExerciseAnswer);
        
        //get the confident tag
        // $conTag = $this->confidentTag($value);

        // check whether the generated answer matches the correct answer
        $isTableNameCorrect = strcasecmp($this->answerTableName, $this->answerTable);
        $isCorrect = $this->compareAnswerArrays($this->answer, $this->userAnswer);

        if ($isCorrect && $isTableNameCorrect == 0) {
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
            $array1[$key] = strtoupper($value);
        }

        foreach ($array2 as $key => $value) {
            $array2[$key] = strtoupper($value);
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