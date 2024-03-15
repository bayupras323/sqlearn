<?php

namespace App\Http\Livewire\InnerOuterJoin;

use App\Http\Controllers\Exercises\DatabaseController;
use App\Models\Database;
use App\Models\LogExercise;
use App\Models\Score;
use App\Models\Student;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InnerOuterJoin extends Component
{
    public $exercise; // menyimpan data exercise
    public $currentPage;
    public $schedule; // menyimpan data schedule
    public $nextQuestionUrl; // untuk handle url latihan selanjutnya
    public $tables; // menyimpan daftar tabel yang digunakan dalam exercise
    public $selectedColumn = []; // menyimpan kolom yang dipilih user
    public $selectedDataKey = []; // menyimpan key data yang dipilih user
    public $pairedData = []; // menyimpan data yang berpasangan
    public $selectedData = []; // menyimpan data yang dipilih user
    public $answer = []; // menyimpan jawaban final user
    public $activeAccordion; // handle state accordion
    // inisiasi stepper
    public $active_step = 1;
    public $steps = [
        1 => [
            "status" => "active",
            "filled" => false
        ],
        2 => [
            "status" => "",
            "filled" => false
        ],
    ];

    public $correct_log;
    public $correct_log_count;

    public function mount()
    {
        // check history correct log
        $this->correct_log = LogExercise::where('student_id', (new Student)->where('user_id', Auth::user()->id)->first()->id)
            ->where('schedule_id', $this->schedule->id)
            ->where('exercise_id', $this->exercise->id)
            ->where('status', 'correct')->first();

        $this->correct_log_count = LogExercise::where('student_id', (new Student)->where('user_id', Auth::user()->id)->first()->id)
            ->where('schedule_id', $this->schedule->id)
            ->where('status', 'correct')->count();

        // mendapatkan nama database yang digunakan
        $db_name = (new Database)->findOrFail($this->exercise->database_id)->name;
        // inisiasi DatabaseController
        $databaseController = new DatabaseController;
        // mendapatkan tabel yang digunakan
        $table_name = $databaseController->getSelectedTable($this->exercise->database_id, $this->exercise->answer['queries']);
        // mendapatkan data tiap tabel yang digunakan
        foreach ($table_name as $table) {
            $data = $databaseController->getTableData($db_name, $table);
            // menghapus elemen pertama dari array dan mengembalikan nilai elemen tersebut
            $firstElement = array_shift($data);
            // menggabungkan elemen array yang dikembalikan dari array_shift() dengan elemen array yang tersisa
            $arr = array_merge($firstElement, $data);
            // simpan data ke variabel table_data
            $this->tables[] = $arr;
        }
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.inner-outer-join.inner-outer-join');
    }

    public function toggleSelectedColumn($value, $step, $removeOnly = false)
    {
        $selectedColumn = $this->selectedColumn;
        // filter array to remove false value
        $selectedColumn = array_filter($selectedColumn);

        //check if it's remove the data only
        if ($removeOnly) {
            $selectedColumn = array_diff($selectedColumn, [$value]);
            $selectedColumn = array_values($selectedColumn);
        }

        //enable/disable next button
        if (count($selectedColumn) == 0) {
            //disable next button
            $this->toggleStepFill($step, false);
        } else {
            //enable next button
            $this->toggleStepFill($step);
        }

        $this->selectedColumn = $selectedColumn;
    }

    public function toggleSelectedData($value, $step)
    {
        // mendapatkan jumlah tabel yang digunakan dalam join
        $joinCount = count($this->tables);

        // menyimpan data yang dipilih secara berpasangan
        if (count($this->selectedDataKey) == 0) {
            $this->selectedDataKey[] = [$value];
        } else {
            $lastKey = count($this->selectedDataKey) - 1;
            if (count($this->selectedDataKey[$lastKey]) == $joinCount) {
                $this->selectedDataKey[] = [$value];
            } else {
                if ($joinCount == 2) {
                    foreach ($this->selectedDataKey[$lastKey] as $index => $data) {
                        if ($data == $value) {
                            if (count($this->selectedDataKey[$lastKey]) == 1) {
                                unset($this->pairedData[$lastKey]);
                                unset($this->selectedDataKey[$lastKey]);
                            } else {
                                unset($this->pairedData[$index]);
                                unset($this->selectedDataKey[$lastKey][$index]);
                            }
                        } else {
                            $this->selectedDataKey[$lastKey][] = $value;
                        }
                    }
                } else {
                    $this->selectedDataKey[$lastKey][] = $value;
                }
            }
        }

        // reset array pairedData
        if(count($this->pairedData) == $joinCount) {
            $this->pairedData = [];
        }

        $this->getSelectedData();

        //enable/disable next button
        if (count($this->selectedDataKey) == 0 && count($this->pairedData) == 0) {
            //disable next button
            $this->toggleStepFill($step, false);
        } else {
            //enable next button
            $this->toggleStepFill($step);
        }
    }

    public function getSelectedData()
    {
        // reset array selectedData
        $this->selectedData = [];

        // mendapatkan data yang dipilih
        foreach($this->selectedDataKey as $row) {
            $data = [];
            foreach ($row as $rowData) {
                if ($rowData != null) {
                    [$tableIndex, $dataIndex] = explode('-', $rowData);
                    $rowData = $this->tables[$tableIndex]['data'][$dataIndex];
                    foreach ($rowData as $key => $value) {
                        if (!array_key_exists($key, $data)) {
                            $data[$key] = $value;
                        }
                    }
                }
            }
            $this->selectedData[] = $data;
        }
    }

    public function removeAnswerData($index)
    {
        $selectedDataKey = $this->selectedDataKey;
        unset($selectedDataKey[$index]);
        $selectedDataKey = array_values($selectedDataKey);

        $this->selectedDataKey = $selectedDataKey;
        $this->pairedData = [];

        $this->getSelectedData();

        // disable submit button
        if (count($this->selectedDataKey) == 0) {
            $this->toggleStepFill(2, false);
        }
    }

    public function setNullData($index)
    {
        $this->pairedData = [];
        $this->selectedDataKey[$index][] = null;

        $this->getSelectedData();
    }

    public function updateAnswerOrder($sortedData)
    {
        $data = [];
        foreach ($sortedData as $sort) {
            $data[] = $this->selectedDataKey[$sort['value']];
        }

        $this->selectedDataKey = $data;

        $this->getSelectedData();
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
        $this->activeAccordion = '';
    }

    public function previousStep()
    {
        $steps = $this->steps;
        $active_step = $this->active_step;

        // set current active step stepper to empty
        $steps[$active_step]['status'] = "";
        // decrease step by one
        --$active_step;
        // set current active step to active
        $steps[$active_step]['status'] = "active";

        $this->steps = $steps;
        $this->active_step = $active_step;
    }

    public function resetSelected($step)
    {
        switch ($step) {
            case 1:
                $this->activeAccordion = '';
                $this->selectedColumn = [];
                break;
            case 2:
                $this->activeAccordion = '';
                $this->selectedDataKey = [];
                break;
            default:
                break;
        }

        $this->toggleStepFill($step, false);
    }

    public function submitAnswer()
    {
        // inisiasi DatabaseController
        $databaseController = new DatabaseController;

        // mendapatkan jawaban benar
        $correctAnswer = $databaseController->getAnswer($this->exercise->database_id, $this->exercise->answer['queries']);
        $correctAnswer = json_decode(json_encode($correctAnswer), true);

        // mengubah array key kunci jawaban ke lowercase
        $correct = [];
        foreach ($correctAnswer as $answer) {
            $correct[] = array_change_key_case($answer,CASE_LOWER);
        }
        $correctAnswer = $correct;

        // reset array answer
        $this->answer = [];
        // mendapatkan data yang dipilih
        foreach($this->selectedData as $row) {
            $data = [];
            foreach ($this->selectedColumn as $column) {
                if (isset($row[$column])) {
                    $data[strtolower($column)] = $row[$column];
                } else {
                    $data[strtolower($column)] = null;
                }
            }
            $this->answer[] = $data;
        }

        $isCorrect = $this->compareAnswerArrays($correctAnswer, $this->answer);

        if ($isCorrect) {
            $correct_log = LogExercise::where('student_id', (new Student)->where('user_id', Auth::user()->id)->first()->id)
                ->where('schedule_id', $this->schedule->id)
                ->where('exercise_id', $this->exercise->id)
                ->where('status', 'correct')->first();

            if ($correct_log == null) {
                LogExercise::create([
                    'student_id' => (new Student)->where('user_id', Auth::user()->id)->first()->id,
                    'schedule_id' => $this->schedule->id,
                    'exercise_id' => $this->exercise->id,
                    'time' => 0,
                    'answer' => json_encode($this->answer),
                    'status' => 'correct'
                ]);

                // insert score
                Score::updateOrCreate([
                    'student_id' => (new Student)->where('user_id', Auth::user()->id)->first()->id,
                    'schedule_id' => $this->schedule->id,
                ], [
                    'score' => DB::raw('score + 1')
                ]);
            }


            // if there is any question left, redirect to the page. If not, return to home
            if (isset($this->nextQuestionUrl)) {
                $this->dispatchBrowserEvent('showSuccessToast', ['message' => 'Jawaban benar, silahkan lanjut soal berikutnya...']);
            } else {
                $this->dispatchBrowserEvent('showDoneAlert', ['message' => 'Selamat, Kamu telah menyelesaikan seluruh latihan dengan benar']);
            }
        } else {
            // set the status to incorrect and save the log data
            LogExercise::create([
                'student_id' => (new Student)->where('user_id', Auth::user()->id)->first()->id,
                'schedule_id' => $this->schedule->id,
                'exercise_id' => $this->exercise->id,
                'time' => 0,
                'answer' => json_encode($this->answer),
                'status' => 'incorrect'
            ]);

            $this->dispatchBrowserEvent('showErrorToast', ['message' => 'Jawaban salah, silahkan periksa kembali']);
        }
    }

    private function compareAnswerArrays($array1, $array2): bool
    {
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
}