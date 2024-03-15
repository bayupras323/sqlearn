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

class AlterAddColumn extends Component
{
    public $exercise, $nextQuestionUrl, $totalExercise, $schedule;

    public $databaseController, $table, $preview, $numRows, $answerTable;

    public $tableName, $columnName, $dataType, $columnSize, $columnKey, $columnNullability, $columnDefault, $columnExtra;

    public $colName, $colType, $colSize, $colKey, $colNullability, $colDefault, $colExtra = [];

    public $show = [];

    public $userAnswer = [];

    public $answer, $answerTableName;

    public function mount($exercise)
    {
        $this->exercise = Exercise::find($exercise->id);
        $database = (new Database())->find($exercise->database_id);

        $databaseController = new DatabaseController;
        $preview = $databaseController->getPreviewTableDDL($database->id, $exercise);

        // generate answer
        $this->answerTableName = $exercise->answer['table'];
        $answerColumn = $exercise->answer['columns'];
        $columns = [];
        foreach ($answerColumn as $column) {
            // search only data type
            $type = preg_replace('/\((.*?)\)/', '', $column['type']);
            // search only size from data type
            preg_match('/\((.*?)\)/', $column['type'], $match);
            $length = $match[1] ?? '';
            // save answer to new array
            $columns[] = [
                'name' => $column['name'],
                'type' => $type,
                'length' => $length,
                'key' => $column['key'],
                'null' => $column['nullability'],
                'default' => $column['default'],
                'extra' => $column['extra']
            ];
        }
        $this->answer = $columns;

        // merge data
        $tableName = array_merge($preview['tableName']['desc'], $preview['tableName']['additional']);
        $columnName = array_merge($preview['columnName']['desc'], $preview['columnName']['additional']);
        $dataType = $preview['dataType'];
        $columnSize = array_merge($preview['columnSize']['desc'], $preview['columnSize']['additional']);
        $this->columnKey = $preview['columnKey'];
        $this->columnNullability = $preview['columnNullability'];
        $columnDefault = array_merge($preview['columnDefault']['desc'], $preview['columnDefault']['additional']);
        $this->columnExtra = $preview['columnExtra'];

        foreach ($columns as $column) {
            $columnName[] = $column['name'];
            if ($column['length'] != '') $columnSize[] = $column['length'];
            if ($column['default'] != '') $columnDefault[] = $column['default'];
        }

        // shuffle data
        shuffle($tableName);
        shuffle($columnName);
        // shuffle($dataType);
        shuffle($columnSize);
        shuffle($columnDefault);

        // shuffeled data to variable
        $this->tableName = $tableName;
        $this->columnName = $columnName;
        $this->dataType = $dataType;
        $this->columnSize = $columnSize;
        $this->columnDefault = $columnDefault;

        $numRows = max(
            count($tableName),
            count($columnName)
        );
        $this->exercise = $exercise;
        $this->preview = $preview;
        $this->numRows = $numRows;

        $this->colName = $this->columnName;
        // $this->show = [$numRows];
    }

    public function render()
    {
        return view('livewire.ddl.alter-add-column');
    }

    public function addAnswer($i)
    {
        $tipe = isset($this->colType[$i]) ? $this->colType[$i] : null;
        $size = isset($this->colSize[$i]) ? $this->colSize[$i]  : '';
        $cKey = isset($this->colKey[$i]) ? $this->colKey[$i]  : '';
        $cNull = $this->colNullability != null ? $this->colNullability : null;
        if ($this->colNullability != null) {
            if ($cNull == 'NOT NULL') {
                $cNull = 'NO';
            } else {
                $cNull = 'YES';
            }
        } else {
            $cNull = 'YES';
        }
        $default = $this->colDefault != null ? $this->colDefault  : null;
        $extra = $this->colExtra != null ? $this->colExtra  : '';
        $answer = [
            'name' => $this->colName[$i],
            'type' => $tipe,
            'length' => $size,
            'key' => $cKey,
            'null' => $cNull,
            'default' => $default,
            'extra' => $extra
        ];
        $index = count($this->userAnswer);

        $this->userAnswer[$index] = $answer;
        $this->colType = null;
        $this->colSize = null;
        $this->colKey = null;
        $this->colNullability = null;
        $this->colDefault = null;
        $this->colExtra = null;
    }

    public function collapseShow($i)
    {
        if (!isset($this->show[$i]) || $this->show[$i] == '') {
            $this->show[$i] = 'show';
        } else {
            $this->show[$i] = '';
        }
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

    public function setAnswerTable($i)
    {
        $this->answerTable = $this->tableName[$i] ?? '';
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
        // check whether the generated answer matches the correct answer
        $isTableNameCorrect = strcasecmp($this->answerTableName, $this->answerTable);
        $isCorrect = $this->compareAnswerArrays($this->answer, $this->userAnswer);
        
        //get the confident tag
        // $conTag = $this->confidentTag($value);

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
            $temp_array = [
                'name' => strtoupper($value['name']),
                'type' => strtoupper($value['type']),
                'length' => $value['length'],
                'key' => strtoupper($value['key']),
                'null' => strtoupper($value['null']),
                'default' => strtoupper($value['default']),
                'extra' => strtoupper($value['extra']),
            ];
            $array1[$key] = $temp_array;
        }

        foreach ($array2 as $key => $value) {
            $temp_array = [
                'name' => strtoupper($value['name']),
                'type' => strtoupper($value['type']),
                'length' => $value['length'],
                'key' => strtoupper($value['key']),
                'null' => strtoupper($value['null']),
                'default' => strtoupper($value['default']),
                'extra' => strtoupper($value['extra']),
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
