<?php

namespace App\Http\Livewire\UnionCrossJoin;

use App\Helpers\SQLHelper;
use App\Http\Controllers\Exercises\DatabaseController;
use App\Models\Database;
use App\Models\LogExercise;
use App\Models\Score;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use TypeError;

class UnionJoin extends Component
{

    public
        $exercise, // store the exercise data
        $schedule, // store the schedule data
        $nextQuestionUrl, // store the next question url
        $db_name, // store the database name
        $right_query, // store the right query
        $left_query, // store the left query
        $right_query_table_name, // store the right query table name
        $left_query_table_name, // store the left query table name
        $right_query_table, // store the right query table
        $right_query_table_filtered, // store the right query table filtered
        $right_query_table_filtered_with_addition, // store the right query table filtered with addition
        $right_query_table_filtered_modified,
        $right_query_aggregate, // store the right query aggregate
        $left_query_table, // store the left query table
        $left_query_table_filtered, // store the left query table filtered
        $left_query_table_filtered_with_addition, // store the left query table filtered with addition
        $left_query_aggregate, // store the left query aggregate
        $union_query_table_final,
        $active_step = 1, // initialize the active step
        $selectedColumnRightQuery = [], // store the selected column from right query
        $selectedColumnRightQueryWithAddition = [], // store the selected column from right query with addition
        $selectedDataRightQuery = [], // store the selected data from right query
        $selectedColumnLeftQuery = [], // store the selected column from left query
        $selectedColumnLeftQueryWithAddition = [], // store the selected column from left query with addition
        $selectedDataLeftQuery = [], // store the selected data from left query
        $selectedColumnUnionQuery = [],
        $selectedDataUnionQuery = [],
        $steps = [ // store the steps
            1 => [
                "status" => "active",
                "queryType" => "left",
                "additionColumns" => false,
                "aggregation" => false,
                "aggregationKeyword" => "",
                "filled" => false
            ],
            2 => [
                "status" => "",
                "queryType" => "left",
                "additionColumns" => false,
                "aggregation" => false,
                "aggregationKeyword" => "",
                "filled" => false
            ],
            3 => [
                "status" => "",
                "queryType" => "right",
                "additionColumns" => false,
                "aggregation" => false,
                "aggregationKeyword" => "",
                "filled" => false
            ],
            4 => [
                "status" => "",
                "queryType" => "right",
                "additionColumns" => false,
                "aggregation" => false,
                "aggregationKeyword" => "",
                "filled" => false
            ]
        ];



    public function mount()
    {
        $union = SQLHelper::splitUnion($this->exercise->answer['queries']); // split the union query from the answer
        $this->right_query = $union['right']; // assign the right query
        $this->left_query = $union['left']; // assign the left query
        $this->checkIfQueryContainAggregation($this->right_query, 4); // check if right query in step 4 contain aggregation
        $this->checkIfQueryContainAggregation($this->right_query, 3); // check if right query in step 3 contain aggregation
        $this->checkIfQueryContainAggregation($this->left_query, 2); // check if left query in step 2 contain aggregation
        $this->checkIfQueryContainAggregation($this->left_query, 1); // check if left query in step 1 contain aggregation
        $this->right_query_table_name = SQLHelper::getTableNames($this->right_query)[0]; // get the table name of the right query
        $this->left_query_table_name = SQLHelper::getTableNames($this->left_query)[0]; // get the table name of the left query
        $this->db_name = Database::findOrFail($this->exercise->database_id)->name; // get the database name from the exercise database id
        $db = new DatabaseController(); // create new database controller instance
        $this->right_query_table = $db->getTableData($this->db_name, $this->right_query_table_name); // get the right query table data
        $this->left_query_table = $db->getTableData($this->db_name, $this->left_query_table_name); // get the left query table data
    }


    public function render()
    {
        return view('livewire.union-cross-join.union-join');
    }

    private function checkIfQueryContainAggregation($query, $step)
    {
        $keyword = SQLHelper::findSQLAggregationKeywords($query); // find the aggregation keyword
        count($keyword) > 0 // check if the keyword is not empty
            ? ($this->steps[$step]['aggregation'] = true) // set the aggregation to true
            && ($this->steps[$step]['aggregationKeyword'] = $keyword[0]) // assign the aggregation keyword
            : null; // if the keyword is empty
    }

    public function toggleSelectAllColumn($step)
    {
        if ($this->steps[$step]['checkedAll']) {
            switch ($step) {
                case 1:
                    $this->selectedColumnLeftQuery = array_map(fn ($item) => $item["name"], $this->left_query_table[0]['columns']);
                    break;
                case 2:
                    $this->selectedDataLeftQuery = array_map('strval', array_keys($this->left_query_table_filtered));
                    if ($this->steps[$step]['aggregation']) {
                        $this->aggregateData($this->steps[$step]['aggregationKeyword'], $this->selectedDataLeftQuery);
                    }
                    break;
                case 3:
                    $this->selectedColumnRightQuery = array_map(fn ($item) => $item["name"], $this->right_query_table[0]['columns']);
                    break;
                case 4:
                    $this->selectedDataRightQuery = array_map('strval', array_keys($this->right_query_table_filtered));
                    if ($this->steps[$step]['aggregation']) {
                        $this->aggregateData($this->steps[$step]['aggregationKeyword'], $this->selectedDataRightQuery);
                    }
                    break;
                default:
                    break;
            }

            $this->toggleStepFill($step);
        } else {
            $this->resetSelected($step);
        }
    }

    public function toggleSelectedColumn($value, $queryType, $step, $removeOnly = false)
    {
        $selectedColumnQuery = ($queryType == 'right') // check if the query type is right
            ? $this->selectedColumnRightQuery // assign the right query selected column
            : $this->selectedColumnLeftQuery;  // assign the left query selected column
        $selectedColumnQuery = array_filter($selectedColumnQuery); // get the selected column from the query type
        $removeOnly // check if the remove only is true
            ? $selectedColumnQuery = array_diff($selectedColumnQuery, [$value]) // remove the value from the selected column query
            : null; // if the remove only is false
        $count = count($selectedColumnQuery); // get the count of the selected column query
        $this->toggleStepFill( // toggle the step fill
            $step, // pass the step
            $count == 0 // check if the count is 0
                ? false // if the count is 0, return false
                : true // if the count is not 0, return true
        );
        $queryType == 'right' // check if the query type is right
            ? $this->selectedColumnRightQuery = $selectedColumnQuery  // assign the right query selected column
            : $this->selectedColumnLeftQuery = $selectedColumnQuery; // assign the left query selected column
    }

    private function toggleStepFill($step, $value = true)
    {
        $this->steps[$step]['filled'] = $value; // set the step filled to true
    }

    public function toggleSelectedData($value, $queryType, $step, $removeOnly = false)
    {
        $selectedDataQuery = ($queryType == 'right') // check if the query type is right
            ? $this->selectedDataRightQuery // assign the right query selected data
            : $this->selectedDataLeftQuery; // assign the left query selected data
        $selectedDataQuery = array_filter($selectedDataQuery, function ($value) { // filter the selected data query
            return ($value !== NULL && $value !== FALSE && $value !== ''); // return the value if it's not null, false, or empty
        });
        $removeOnly // check if the remove only is true
            ? $selectedDataQuery = array_diff($selectedDataQuery, [$value]) // remove the value from the selected data query
            : null; // if the remove only is false
        $count = count($selectedDataQuery); // get the count of the selected data query
        $count == 0 // check if the count is 0
            ? $this->toggleStepFill($step, false) // if the count is 0, disable the next button
            : $this->toggleStepFill($step); // if the count is not 0, enable the next button
        $this->steps[$step]['aggregation'] // check if the step aggregation is true
            ? $this->aggregateData($this->steps[$step]['aggregationKeyword'], $selectedDataQuery) // aggregate the data
            : null; // if the step aggregation is false
        $queryType == 'right' // check if the query type is right
            ? $this->selectedDataRightQuery = $selectedDataQuery // assign the right query selected data
            : $this->selectedDataLeftQuery = $selectedDataQuery; // assign the left query selected data
    }

    private function addAdditionColumns()
    {
        if ($this->active_step === 2 || $this->active_step === 4) { // check if the active step is 2 or 4
            $query = $this->active_step === 2 // check if the active step is 2
                ? $this->left_query // assign the left query
                : $this->right_query; // assign the right query
            $selectedColumnQuery = $this->active_step === 2 // check if the active step is 2
                ? $this->selectedColumnLeftQuery // assign the left query selected column
                : $this->selectedColumnRightQuery; // assign the right query selected column
            $additionColumns = SQLHelper::getWhereAndOrderByClauseColumns($query); // get the addition columns from the query
            $mergeColumnQuery = array_unique(array_merge($selectedColumnQuery, $additionColumns), SORT_STRING | SORT_FLAG_CASE); // merge the selected column query and addition columns
            $this->active_step === 2 // check if the active step is 2
                ? $this->selectedColumnLeftQueryWithAddition = $mergeColumnQuery // assign the left query selected column with addition
                : $this->selectedColumnRightQueryWithAddition = $mergeColumnQuery; // assign the right query selected column with addition
            if (count($additionColumns) > 0) { // check if the addition columns is not empty
                if ($this->active_step === 2) { // check if the active step is 2
                    $this->steps[1]['additionColumns'] = true; // set step 1 addition columns to true
                    $this->steps[2]['additionColumns'] = true; // set step 2 addition columns to true
                } else { // if the active step is not 2
                    $this->steps[3]['additionColumns'] = true; // set step 3 addition columns to true
                    $this->steps[4]['additionColumns'] = true; // set step 4 addition columns to true
                }
            }
        }
    }

    public function nextStep()
    {
        $steps = $this->steps; // get the steps
        $active_step = $this->active_step; // get the active step
        if ($steps[$active_step]['filled']) { // check if the current active step is filled
            $steps[$active_step]['status'] = "complete"; // set current active step stepper to complete
            ++$active_step; // increase step by one
            $steps[$active_step]['status'] = "active"; // set current active step to active
        }
        $this->steps = $steps; // assign the steps
        $this->active_step = $active_step; // assign the active step
        $this->addAdditionColumns(); // add addition columns to the selected column query
        $this->filterQueryTable(); // filter the query table
        $this->copyLeftQueryToRight();
    }

    public function previousStep()
    {
        $steps = $this->steps; // get the steps
        $active_step = $this->active_step; // get the active step
        $steps[$active_step]['status'] = ""; // set current active step stepper to empty
        --$active_step; // decrease step by one
        $steps[$active_step]['status'] = "active"; // set current active step to active
        $this->steps = $steps; // assign the steps
        $this->active_step = $active_step; // assign the active step
    }

    private function copyLeftQueryToRight()
    {
        if ($this->active_step === 3) {
            $this->selectedColumnUnionQuery = $this->selectedColumnLeftQuery;
        } else if ($this->active_step === 4) {
            $this->selectedDataUnionQuery = $this->selectedDataLeftQuery;
        }
    }

    private function filterQueryTable()
    {
        if ($this->active_step === 2 || $this->active_step === 4) { // check if the active step is 2 or 4
            $query_table = $this->active_step === 2 // check if the active step is 2
                ? $this->left_query_table[0]['data'] // assign the left query table
                : $this->right_query_table[0]['data']; // assign the right query table
            $selected_columns = $this->active_step === 2 // check if the active step is 2
                ? $this->selectedColumnLeftQuery // assign the left query selected column
                : $this->selectedColumnRightQuery; // assign the right query selected column
            $selected_columns_with_addition = $this->active_step === 2 // check if the active step is 2
                ? $this->selectedColumnLeftQueryWithAddition // assign the left query selected column with addition
                : $this->selectedColumnRightQueryWithAddition; // assign the right query selected column with addition
            $filtered_tables = array_map(fn ($value) => array_intersect_key($value, array_flip($selected_columns)), $query_table); // filter the query table
            $filtered_tables_with_addition = array_map( // filter the query table with addition
                function ($value) use ($selected_columns_with_addition) { // use the selected column with addition
                    $result = array(); // create an empty array
                    foreach ($selected_columns_with_addition as $column) { // loop the selected column with addition
                        if (isset($value[$column])) { // check if the column is set
                            $result[$column] = $value[$column]; // assign the column to the result
                        }
                    }
                    return $result; // return the result
                },
                $query_table // assign the query table
            );
            $this->active_step === 2 // check if the active step is 2
                ? $this->left_query_table_filtered = $filtered_tables // assign the left query table filtered
                : $this->right_query_table_filtered = $filtered_tables; // assign the right query table filtered
            $this->active_step === 2 // check if the active step is 2
                ? $this->left_query_table_filtered_with_addition = $filtered_tables_with_addition // assign the left query table filtered with addition
                : $this->right_query_table_filtered_with_addition = $filtered_tables_with_addition; // assign the right query table filtered with addition
        }
    }

    private function aggregateData($keyword, $row)
    {
        if ($this->active_step === 2 || $this->active_step === 4) { // check if the active step is 2 or 4
            $filtered_data = $this->active_step === 2 // check if the active step is 2
                ? $this->left_query_table_filtered // assign the left query table filtered
                : $this->right_query_table_filtered; // assign the right query table filtered
            $aggregate_value = null; // set the aggregate value to null
            $aggregate_table = array_map(fn ($value) => reset($value), array_intersect_key($filtered_data, $row)); // get the aggregate table
            switch ($keyword) { // switch the keyword
                case 'COUNT': // if the keyword is count
                    $aggregate_value = count($row); // assign the aggregate value to count the row
                    break;
                case 'SUM': // if the keyword is sum
                    try { // try to sum the aggregate table
                        $aggregate_value = array_sum($aggregate_table); // assign the aggregate value to sum the aggregate table
                    } catch (TypeError $e) { // if the aggregate table is empty
                        $aggregate_value = 0; // assign the aggregate value to 0
                    }
                    break;
                case 'MAX': // if the keyword is max
                    if (!empty($aggregate_table)) { // check if the aggregate table is not empty
                        $aggregate_value = max($aggregate_table); // assign the aggregate value to max the aggregate table
                    } else { // if the aggregate table is empty
                        $aggregate_value = null; // assign the aggregate value to null
                    }
                    break;
                case 'MIN': // if the keyword is min
                    if (!empty($aggregate_table)) { // check if the aggregate table is not empty
                        $aggregate_value = min($aggregate_table); // assign the aggregate value to min the aggregate table
                    } else { // if the aggregate table is empty
                        $aggregate_value = null; // assign the aggregate value to null
                    }
                    break;
                case 'AVG': // if the keyword is avg
                    try { // try to avg the aggregate table
                        if (count($row) > 0) { // check if the row is not empty
                            $aggregate_value = round(array_sum($aggregate_table) / count($row), 4); // assign the aggregate value to avg the aggregate table
                        } else { // if the row is empty
                            $aggregate_value = 0; // assign the aggregate value to 0
                        }
                    } catch (TypeError $e) { // if the aggregate table is empty
                        $aggregate_value = 0; // assign the aggregate value to 0
                    }
                    break;
                default:
                    break;
            }

            $this->active_step === 2 // check if the active step is 2
                ? $this->left_query_aggregate = $aggregate_value // assign the left query aggregate value
                : $this->right_query_aggregate = $aggregate_value; // assign the right query aggregate value
        }
    }

    public function resetSelected($step)
    {
        switch ($step) { // switch the step
            case 1: // if the step is 1
                $this->selectedColumnLeftQuery = []; // reset the left query selected column
                break;
            case 2: // if the step is 2
                $this->selectedDataLeftQuery = []; // reset the left query selected data
                break;
            case 3: // if the step is 3
                $this->selectedColumnRightQuery = []; // reset the right query selected column
                break;
            case 4: // if the step is 4
                $this->selectedDataRightQuery = []; // reset the right query selected data
                break;
            default:
                break;
        }
        $this->toggleStepFill($step, false); // toggle the step fill to false
    }



    private function modifyRightQueryKeys()
    {
        if (count($this->selectedColumnLeftQuery) === count($this->selectedColumnRightQuery)) {
            $selected_column_left_query = $this->selectedColumnLeftQuery;

            $this->right_query_table_filtered_modified = array_map(function ($item) use ($selected_column_left_query) {
                $values = array_values($item);
                return array_combine($selected_column_left_query, $values);
            }, $this->right_query_table_filtered);
        } else {
            $this->dispatchBrowserEvent('showErrorToast', ['message' => 'Jawaban salah, silahkan periksa kembali']); // show error toast
        }
    }

    private function generateFinalUnionAnswer()
    {
        $this->modifyRightQueryKeys();
        $this->union_query_table_final = array_merge($this->left_query_table_filtered, $this->right_query_table_filtered_modified);
    }

    public function submitAnswer()
    {
        $this->generateFinalUnionAnswer();

        $db = new DatabaseController(); // create instance of Database Controller
        $answerLeftQuery = $this->left_query; // assign the left query answer
        $answerUnion = $this->exercise->answer['queries']; // assign the union answer
        $logExerciseAnswer = array( // create array of log exercise answer
            'left_query' => "",
            'union' => ""
        );
        //get the confident tag
        // $conTag = $this->confidentTag($value);
        $logExercise = new LogExercise(); // create instance of Log Exercise
        $logExercise->student_id = Student::where('user_id', Auth::user()->id)->first()->id; // assign the student id
        $logExercise->schedule_id = $this->schedule->id;
        $logExercise->exercise_id = $this->exercise->id; // assign the exercise id
        $logExercise->time = 0; // assign the time
        $finalAnswerLeftQuery = $this->checkAnswer($db, $answerLeftQuery); // check the answer of left query
        $logExerciseAnswer['left_query'] = $finalAnswerLeftQuery['generatedAnswer']; // assign the log exercise answer left query
        if ($finalAnswerLeftQuery['isCorrect']) { // check if the left query is correct
            $finalAnswerUnion = $this->checkAnswer($db, $answerUnion, false); // check the answer of union
            $logExerciseAnswer['union'] = $finalAnswerUnion['generatedAnswer']; // assign the log exercise answer union
            if ($finalAnswerUnion['isCorrect']) { // check if the union is correct
                $logExercise->answer = json_encode($logExerciseAnswer); // assign the log exercise answer
                $logExercise->status = 'correct'; // assign the log exercise status
                // $logExercise->confident= $conTag; // set confident to yakin
                $logExercise->save(); // save the log exercise
                // insert score
                Score::updateOrCreate([
                    'student_id' => (new Student)->where('user_id', Auth::user()->id)->first()->id,
                    'schedule_id' => $this->schedule->id,
                ], [
                    'score' => DB::raw('score + 1')
                ]);
                if (isset($this->nextQuestionUrl)) { // check if the next question url is set
                    $this->dispatchBrowserEvent('showSuccessToast', ['message' => 'Jawaban benar, mengarahkan ke soal berikutnya...']); // show success toast
                    $this->redirect($this->nextQuestionUrl); // redirect to the next question url
                } else { // if the next question url is not set
                    $this->dispatchBrowserEvent('showDoneAlert', ['message' => 'Selamat, Kamu telah menyelesaikan seluruh latihan dengan benar']); // show done alert
                }
                return;
            }
        }
        $logExercise->answer = json_encode($logExerciseAnswer); // assign the log exercise answer
        $logExercise->status = 'incorrect'; // assign the log exercise status
        // $logExercise->confident= $conTag; // set confident to tidak yakin
        $logExercise->save(); // save the log exercise
        $this->dispatchBrowserEvent('showErrorToast', ['message' => 'Jawaban salah, silahkan periksa kembali']); // show error toast
    }

    private function checkAnswer($db, $correctAnswerQuery, $leftQuery = true)
    {
        $correctAnswer = $db->getQueryData($this->db_name, $correctAnswerQuery); // get the correct answer
        $generatedAnswer = null; // assign the generated answer to null
        $step = $leftQuery // check if the query is left query
            ? 2 // assign the step to 2
            : 4; // assign the step to 4
        $selectedColumnQuery = $leftQuery // check if the query is left query
            ? $this->selectedColumnLeftQuery // assign the selected column query to left query selected column query
            : $this->selectedColumnRightQuery; // assign the selected column query to right query selected column query
        $queryTableColumns = $leftQuery
            ? $this->left_query_table[0]['columns']
            : $this->right_query_table[0]['columns'];
        $query_table_filtered = $leftQuery // check if the query is left query
            ? $this->left_query_table_filtered // assign the query table filtered to left query table filtered
            : $this->union_query_table_final; // assign the query table filtered to right query table filtered
        $query_aggregate = $leftQuery // check if the query is left query
            ? $this->left_query_aggregate // assign the query aggregate to left query aggregate
            : $this->right_query_aggregate; // assign the query aggregate to right query aggregate
        if ($this->steps[$step]['aggregation']) { // check if the step aggregation is true
            $query_aggregate_key = $this->steps[$step]['aggregationKeyword'] . (count($selectedColumnQuery) == count($queryTableColumns)
                ? "(*)"
                : "(" . implode(', ', $selectedColumnQuery) . ")");
            $generatedAnswer = [ // assign the generated answer
                [
                    $query_aggregate_key => strval($query_aggregate) // assign the query aggregate key to query aggregate
                ]
            ];
        } else { // if the step aggregation is false
            $generatedAnswer = $query_table_filtered; // assign the generated answer to query table filtered with selected data query
        }

        $isCorrect = $this->compareAnswerArrays($correctAnswer, $generatedAnswer); // compare the correct answer and generated answer
        return [
            'isCorrect' => $isCorrect,
            'generatedAnswer' => $generatedAnswer
        ];
    }

    private function compareAnswerArrays($array1, $array2)
    {
        $array1_upper = $this->array_change_key_case_recursive($array1, CASE_UPPER); // change the array 1 key to uppercase
        $array2_upper = $this->array_change_key_case_recursive($array2, CASE_UPPER); // change the array 2 key to uppercase
        sort($array1_upper); // sort the array 1
        sort($array2_upper); // sort the array 2
        count($array1_upper) !== count($array2_upper) ? false : null; // check if the array 1 count is not equal to array 2 count
        foreach ($array1_upper as $key => $value) { // loop the array 1
            if (!isset($array2_upper[$key]) || $array2_upper[$key] != $value) { // check if the array 2 key is not set or array 2 key is not equal to array 1 key
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