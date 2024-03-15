<?php

namespace App\Http\Livewire\UnionCrossJoin;

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

class CrossJoin extends Component
{
    public
        $exercise, // store exercise data
        $schedule, // store the schedule data
        $nextQuestionUrl, // to handle the url for the next exercise
        $tables, // stores the list of tables used in the exercise
        $activeAccordion, // handle state accordion
        $active_step = 1, // initialize the stepper
        $selectedColumn = [], // stores the column selected by the user
        $selectedDataKey = [], // stores the user selected data key
        $pairedData = [], // store paired data
        $selectedData = [], // stores the data selected by the user
        $answer = [], // stores the final user's answer
        $steps = [ // stores the stepper data
            1 => [
                "status" => "active",
                "filled" => false
            ],
            2 => [
                "status" => "",
                "filled" => false
            ],
        ];

    public function mount()
    {
        $db_name = (new Database)->findOrFail($this->exercise->database_id)->name; // get the database name used
        $databaseController = new DatabaseController; // initialize DatabaseController
        $table_name = $databaseController->getSelectedTable($this->exercise->database_id, $this->exercise->answer['queries']); // get the table used
        foreach ($table_name as $table) { // get data for each table used
            $data = $databaseController->getTableData($db_name, $table); // removes the first element from the array and returns the value of that element
            $firstElement = array_shift($data); // combine the array elements returned from array_shift() with the remaining array elements
            $arr = array_merge($firstElement, $data); // save data to table_data variable
            $this->tables[] = $arr; // store table name to table_name variable
        }
    }
    public function render(): Factory|View|Application
    {
        return view('livewire.union-cross-join.cross-join');
    }

    public function toggleSelectedColumn($value, $step, $removeOnly = false)
    {
        $selectedColumn = $this->selectedColumn; // get selected column
        $selectedColumn = array_filter($selectedColumn); // filter array to remove false value
        if ($removeOnly) { // check if it's remove the column only
            $selectedColumn = array_diff($selectedColumn, [$value]);
            $selectedColumn = array_values($selectedColumn);
        }
        count($selectedColumn) == 0 // check if selected column is empty
            ? $this->toggleStepFill($step, false) // set step to not filled
            : $this->toggleStepFill($step); // set step to filled
        $this->selectedColumn = $selectedColumn; // set selected column
    }

    public function toggleSelectedData($value, $step)
    {
        $joinCount = count($this->tables); // get table count
        if (count($this->selectedDataKey) == 0) { // check if selected data key is empty
            $this->selectedDataKey[] = [$value]; // set selected data key
        } else { // check if selected data key is not empty
            $lastKey = count($this->selectedDataKey) - 1; // get last key of selected data key
            if (count($this->selectedDataKey[$lastKey]) == $joinCount) { // check if selected data key is equal to table count
                $this->selectedDataKey[] = [$value]; // set selected data key
            } else { // check if selected data key is not equal to table count
                if ($joinCount == 2) { // check if table count is equal to 2
                    foreach ($this->selectedDataKey[$lastKey] as $index => $data) { // loop through selected data key
                        if ($data == $value) { // check if data is equal to value
                            if (count($this->selectedDataKey[$lastKey]) == 1) { // check if selected data key is equal to 1
                                unset($this->pairedData[$lastKey]); // remove paired data
                                unset($this->selectedDataKey[$lastKey]); // remove selected data key
                            } else { // check if selected data key is not equal to 1
                                unset($this->pairedData[$index]); // remove paired data
                                unset($this->selectedDataKey[$lastKey][$index]); // remove selected data key
                            }
                        } else { // check if data is not equal to value
                            $this->selectedDataKey[$lastKey][] = $value; // set selected data key
                        }
                    }
                } else { // check if table count is not equal to 2
                    $this->selectedDataKey[$lastKey][] = $value; // set selected data key
                }
            }
        }
        (count($this->pairedData) == $joinCount) ? $this->pairedData = [] : null; // check if paired data is equal to table count then reset paired data
        $this->getSelectedData(); // get selected data from selected data key
        count($this->selectedDataKey) == 0 && count($this->pairedData) == 0 // check if selected data key and paired data is empty
            ? $this->toggleStepFill($step, false) // set step to not filled
            : $this->toggleStepFill($step); // set step to filled
    }

    public function getSelectedData()
    {
        $this->selectedData = []; // reset selected data
        foreach ($this->selectedDataKey as $row) { // loop through selected data key
            $data = []; // initialize data
            foreach ($row as $rowData) { // loop through row
                if ($rowData != null) { // check if row data is not null
                    [$tableIndex, $dataIndex] = explode('-', $rowData); // get table index and data index
                    $rowData = $this->tables[$tableIndex]['data'][$dataIndex]; // get row data
                    foreach ($rowData as $key => $value) { // loop through row data
                        if (!array_key_exists($key, $data)) { // check if key is not exist in data
                            $data[$key] = $value; // set data
                        }
                    }
                }
            }
            $this->selectedData[] = $data; // set selected data
        }
    }

    public function removeAnswerData($index)
    {
        $selectedDataKey = $this->selectedDataKey; // get selected data key by index
        unset($selectedDataKey[$index]); // remove selected data key by index
        $selectedDataKey = array_values($selectedDataKey); // reset array index of selected data key
        $this->selectedDataKey = $selectedDataKey; // set selected data key
        $this->pairedData = []; // reset paired data
        $this->getSelectedData(); // get selected data from selected data key
        if (count($this->selectedDataKey) == 0) { // check if selected data key is empty
            $this->toggleStepFill(2, false); // set step to not filled
        }
    }

    public function setNullData($index)
    {
        $this->pairedData = []; // reset paired data
        $this->selectedDataKey[$index][] = null; // set null data
        $this->getSelectedData(); // get selected data from selected data key
    }

    public function updateAnswerOrder($sortedData)
    {
        $data = []; // initialize data
        foreach ($sortedData as $sort) { // loop through sorted data
            $data[] = $this->selectedDataKey[$sort['value']]; // set data
        }
        $this->selectedDataKey = $data; // set selected data key
        $this->getSelectedData(); // get selected data from selected data key
    }

    private function toggleStepFill($step, $value = true)
    {
        $this->steps[$step]['filled'] = $value; // set step filled
    }

    public function nextStep()
    {
        $steps = $this->steps; // get steps
        $active_step = $this->active_step; // get active step
        if ($steps[$active_step]['filled']) { // check if the current active step is filled
            $steps[$active_step]['status'] = "complete"; // set current active step stepper to complete
            ++$active_step; // increase step by one
            $steps[$active_step]['status'] = "active"; // set current active step to active
        }
        $this->steps = $steps; // assign the steps
        $this->active_step = $active_step; // assign the active step
        $this->activeAccordion = ''; // reset active accordion
    }

    public function previousStep()
    {
        $steps = $this->steps; // get steps
        $active_step = $this->active_step; // get active step
        $steps[$active_step]['status'] = ""; // set current active step stepper to complete
        --$active_step; // decrease step by one
        $steps[$active_step]['status'] = "active"; // set current active step to active
        $this->steps = $steps; // assign the steps
        $this->active_step = $active_step; // assign the active step
    }

    public function resetSelected($step)
    {
        switch ($step) { // check step
            case 1: // if step 1
                $this->activeAccordion = ''; // reset active accordion
                $this->selectedColumn = []; // reset selected column
                break;
            case 2: // if step 2
                $this->activeAccordion = ''; // reset active accordion
                $this->selectedDataKey = []; // reset selected data key
                break;
            default:
                break;
        }
        $this->toggleStepFill($step, false); // disable next button
    }

    public function submitAnswer()
    {
        $databaseController = new DatabaseController; // initialize database controller
        $correctAnswer = $databaseController->getAnswer($this->exercise->database_id, $this->exercise->answer['queries']); // get correct answer
        $correctAnswer = json_decode(json_encode($correctAnswer), true); // convert object to array
        $this->answer = []; // reset answer
        foreach ($this->selectedData as $row) { // loop through selected data
            $data = []; // initialize empty array
            foreach ($this->selectedColumn as $column) { // loop through selected column
                $data[$column] = isset($row[$column]) ? $row[$column] : null; // set data to selected column value or null if not exist
            }
            $this->answer[] = $data; // add data to answer
        }

        //get the confident tag
        // $conTag = $this->confidentTag($value);

        $isCorrect = $this->compareAnswerArrays($correctAnswer, $this->answer); // check if answer is correct or not by comparing with correct answer
        $logExercise = new LogExercise(); // initialize log exercise model instance to save log exercise
        $logExercise->student_id = Student::where('user_id', Auth::user()->id)->first()->id; // set student id from user id of logged in user (student)
        $logExercise->schedule_id = $this->schedule->id; // set schedule id from schedule id of current schedule
        $logExercise->exercise_id = $this->exercise->id; // set exercise id from exercise id of current exercise
        $logExercise->time = 0; // set time to 0 because this exercise is not timed
        $logExercise->answer = json_encode($this->answer); // set answer to json encoded answer array
        if ($isCorrect) { // check if answer is correct
            $logExercise->status = 'correct'; // set status to correct
            // $logExercise->confident= $conTag; // set confident to yakin
            $logExercise->save(); // save log exercise
            // insert score
            Score::updateOrCreate([
                'student_id' => (new Student)->where('user_id', Auth::user()->id)->first()->id,
                'schedule_id' => $this->schedule->id,
            ], [
                'score' => DB::raw('score + 1')
            ]);
            if (isset($this->nextQuestionUrl)) { // check if next question url is set
                $this->dispatchBrowserEvent('showSuccessToast', ['message' => 'Jawaban benar, mengarahkan ke soal berikutnya...']); // show success toast
                $this->redirect($this->nextQuestionUrl); // redirect to next question
            } else { // if next question url is not set
                $this->dispatchBrowserEvent('showDoneAlert', ['message' => 'Selamat, Kamu telah menyelesaikan seluruh latihan dengan benar']); // show done alert
            }
        } else { // if answer is incorrect
            $logExercise->status = 'incorrect'; // set status to incorrect
            // $logExercise->confident= $conTag; // set confident to tidak yakin
            $logExercise->save(); // save log exercise
            $this->dispatchBrowserEvent('showErrorToast', ['message' => 'Jawaban salah, silahkan periksa kembali']); // show error toast
        }
    }

    private function compareAnswerArrays($array1, $array2)
    {
        $array1_upper = $this->array_change_key_case_recursive($array1, CASE_UPPER); // change the array 1 key to uppercase
        $array2_upper = $this->array_change_key_case_recursive($array2, CASE_UPPER); // change the array 2 key to uppercase
        count($array1_upper) !== count($array2_upper) ? false : null; // check if the array 1 count is not equal to array 2 count
        foreach ($array1_upper as $key => $value) { // loop through array 1
            if (!isset($array2_upper[$key]) || $array2_upper[$key] !== $value) { // check if array 2 key is not set or array 2 value is not equal to array 1 value
                return false;
            }
        }
        return true;
    }

    private function array_change_key_case_recursive($array, $case = CASE_UPPER)
    {
        $newArray = array(); // assign the new array to empty array
        foreach ($array as $key => $value) { // loop the array
            if (is_array($value)) { // check if the value is array
                $newArray[($case === CASE_LOWER // check if the case is lower
                    ? strtolower($key) // assign the key to lower case
                    : strtoupper($key))] = $this->array_change_key_case_recursive($value, $case); // assign the key to upper case
            } else { // if the value is not array
                $newArray[($case === CASE_LOWER // check if the case is lower
                    ? strtolower($key) // assign the key to lower case
                    : strtoupper($key))] = $value; // assign the key to upper case
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
        }else {
            $conTag = "tidak yakin";
        }
        return $conTag;
    }
}
