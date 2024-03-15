<?php

namespace App\Http\Livewire\Subquery;

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

class Subquery extends Component
{

    public $currentPage;

    public $exercise, $nextQuestionUrl, $schedule;

    public $db_name;

    public $outer_query, $inner_query, $outer_query_table_name, $inner_query_table_name, $outer_query_table, $outer_query_table_filtered, $outer_query_table_filtered_with_addition, $outer_query_aggregate, $inner_query_table, $inner_query_table_filtered, $inner_query_table_filtered_with_addition, $inner_query_aggregate;

    public $active_step = 1;

    public $steps = [
        1 => [
            "status" => "active",
            "queryType" => "inner",
            "additionColumns" => false,
            "aggregation" => false,
            "aggregationKeyword" => "",
            "filled" => false,
            "checkedAll" => false
        ],
        2 => [
            "status" => "",
            "queryType" => "inner",
            "additionColumns" => false,
            "aggregation" => false,
            "aggregationKeyword" => "",
            "filled" => false,
            "checkedAll" => false
        ],
        3 => [
            "status" => "",
            "queryType" => "outer",
            "additionColumns" => false,
            "aggregation" => false,
            "aggregationKeyword" => "",
            "filled" => false,
            "checkedAll" => false
        ],
        4 => [
            "status" => "",
            "queryType" => "outer",
            "additionColumns" => false,
            "aggregation" => false,
            "aggregationKeyword" => "",
            "filled" => false,
            "checkedAll" => false
        ]
    ];

    public $selectedColumnOuterQuery = [];
    public $selectedColumnOuterQueryWithAddition = [];
    public $selectedDataOuterQuery = [];

    public $selectedColumnInnerQuery = [];
    public $selectedColumnInnerQueryWithAddition = [];
    public $selectedDataInnerQuery = [];

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

        //get the subquery from answer
        $subquery = SQLHelper::splitSubquery($this->exercise->answer['queries']);

        //split the inner & outer query
        $this->outer_query = $subquery['outer'];
        $this->inner_query = $subquery['inner'];

        // check if query contain aggregation, and assign the steps variable
        $this->checkIfQueryContainAggregation($this->outer_query, 4);
        $this->checkIfQueryContainAggregation($this->outer_query, 3);
        $this->checkIfQueryContainAggregation($this->inner_query, 2);
        $this->checkIfQueryContainAggregation($this->inner_query, 1);

        //get the table name for each query
        $this->outer_query_table_name = SQLHelper::getTableNames($this->outer_query)[0];
        $this->inner_query_table_name = SQLHelper::getTableNames($this->inner_query)[0];

        // get the database name of the exercise to access the table later
        $this->db_name = Database::findOrFail($this->exercise->database_id)->name;

        // crete instance of Database Controller to get the related tables data
        $db = new DatabaseController();

        // get the related tables data
        $this->outer_query_table = $db->getTableData($this->db_name, $this->outer_query_table_name);
        $this->inner_query_table = $db->getTableData($this->db_name, $this->inner_query_table_name);
    }


    public function render()
    {
        return view('livewire.subquery.subquery');
    }

    private function checkIfQueryContainAggregation($query, $step)
    {
        $keyword = SQLHelper::findSQLAggregationKeywords($query);

        // assign aggregation attribute to true if the keyword available
        if (count($keyword) > 0) {
            $this->steps[$step]['aggregation'] = true;
            $this->steps[$step]['aggregationKeyword'] = $keyword[0];
        }
    }

    public function toggleSelectAllColumn($step)
    {
        if ($this->steps[$step]['checkedAll']) {
            switch ($step) {
                case 1:
                    $this->selectedColumnInnerQuery = array_map(fn ($item) => $item["name"], $this->inner_query_table[0]['columns']);
                    break;
                case 2:
                    $this->selectedDataInnerQuery = array_map('strval', array_keys($this->inner_query_table_filtered));
                    if ($this->steps[$step]['aggregation']) {
                        $this->aggregateData($this->steps[$step]['aggregationKeyword'], $this->selectedDataInnerQuery);
                    }
                    break;
                case 3:
                    $this->selectedColumnOuterQuery = array_map(fn ($item) => $item["name"], $this->outer_query_table[0]['columns']);
                    break;
                case 4:
                    $this->selectedDataOuterQuery = array_map('strval', array_keys($this->outer_query_table_filtered));
                    if ($this->steps[$step]['aggregation']) {
                        $this->aggregateData($this->steps[$step]['aggregationKeyword'], $this->selectedDataOuterQuery);
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
        //determine if it's outer or inner query
        $selectedColumnQuery = ($queryType == 'outer') ? $this->selectedColumnOuterQuery : $this->selectedColumnInnerQuery;

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
        if ($queryType == 'outer') {
            $this->selectedColumnOuterQuery = $selectedColumnQuery;
        } else {
            $this->selectedColumnInnerQuery = $selectedColumnQuery;
        }
    }

    private function toggleStepFill($step, $value = true)
    {
        $this->steps[$step]['filled'] = $value;
    }

    public function toggleSelectedData($value, $queryType, $step, $removeOnly = false)
    {
        //determine if it's outer or inner query
        $selectedDataQuery = ($queryType == 'outer') ? $this->selectedDataOuterQuery : $this->selectedDataInnerQuery;

        // filter array to remove false, null, & empty value
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

        if ($this->steps[$step]['aggregation']) {
            $this->aggregateData($this->steps[$step]['aggregationKeyword'], $selectedDataQuery);
        }

        // re-assign the global variables with the new value
        if ($queryType == 'outer') {
            $this->selectedDataOuterQuery = $selectedDataQuery;
        } else {
            $this->selectedDataInnerQuery = $selectedDataQuery;
        }
    }

    private function addAdditionColumns()
    {
        if ($this->active_step === 2 || $this->active_step === 4) {
            $query = $this->active_step === 2 ? $this->inner_query : $this->outer_query;
            $selectedColumnQuery = $this->active_step === 2 ? $this->selectedColumnInnerQuery : $this->selectedColumnOuterQuery;

            $additionColumns = SQLHelper::getWhereAndOrderByClauseColumns($query);

            $mergeColumnQuery = array_unique(array_merge($selectedColumnQuery, $additionColumns), SORT_STRING | SORT_FLAG_CASE);

            $this->active_step === 2 ? $this->selectedColumnInnerQueryWithAddition = $mergeColumnQuery : $this->selectedColumnOuterQueryWithAddition = $mergeColumnQuery;

            if (count($additionColumns) > 0) {
                if ($this->active_step === 2) {
                    $this->steps[1]['additionColumns'] = true;
                    $this->steps[2]['additionColumns'] = true;
                } else {
                    $this->steps[3]['additionColumns'] = true;
                    $this->steps[4]['additionColumns'] = true;
                }
            }
        }
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

        // add addition columns if available
        $this->addAdditionColumns();

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
        --$active_step;
        // set current active step to active
        $steps[$active_step]['status'] = "active";

        $this->steps = $steps;
        $this->active_step = $active_step;
    }

    private function filterQueryTable()
    {
        if ($this->active_step === 2 || $this->active_step === 4) {
            $query_table = $this->active_step === 2 ? $this->inner_query_table[0]['data'] : $this->outer_query_table[0]['data'];

            $selected_columns = $this->active_step === 2 ? $this->selectedColumnInnerQuery : $this->selectedColumnOuterQuery;
            $selected_columns_with_addition = $this->active_step === 2 ? $this->selectedColumnInnerQueryWithAddition : $this->selectedColumnOuterQueryWithAddition;

            $filtered_tables = array_map(fn ($value) => array_intersect_key($value, array_flip($selected_columns)), $query_table);
            $filtered_tables_with_addition = array_map(function ($value) use ($selected_columns_with_addition) {
                $result = array();
                foreach ($selected_columns_with_addition as $column) {
                    $result[$column] = $value[$column] ?? null;
                }
                return $result;
            }, $query_table);

            $this->active_step === 2 ? $this->inner_query_table_filtered = $filtered_tables : $this->outer_query_table_filtered = $filtered_tables;
            $this->active_step === 2 ? $this->inner_query_table_filtered_with_addition = $filtered_tables_with_addition : $this->outer_query_table_filtered_with_addition = $filtered_tables_with_addition;
        }
    }

    private function aggregateData($keyword, $row)
    {
        if ($this->active_step === 2 || $this->active_step === 4) {
            $filtered_data = $this->active_step === 2 ? $this->inner_query_table_filtered : $this->outer_query_table_filtered;

            $aggregate_value = null;

            $aggregate_table = array_map(fn ($value) => reset($value), array_intersect_key($filtered_data, $row));

            switch ($keyword) {
                case 'COUNT':
                    $aggregate_value = count($row);
                    break;
                case 'SUM':
                    try {
                        $aggregate_value = array_sum($aggregate_table);
                    } catch (TypeError $e) {
                        $aggregate_value = 0;
                    }
                    break;
                case 'MAX':
                    if (!empty($aggregate_table)) {
                        $aggregate_value = max($aggregate_table);
                    } else {
                        $aggregate_value = null;
                    }
                    break;
                case 'MIN':
                    if (!empty($aggregate_table)) {
                        $aggregate_value = min($aggregate_table);
                    } else {
                        $aggregate_value = null;
                    }
                    break;
                case 'AVG':
                    try {
                        if (count($row) > 0) {
                            $aggregate_value = round(array_sum($aggregate_table) / count($row), 4);
                        } else {
                            $aggregate_value = 0;
                        }
                    } catch (TypeError $e) {
                        $aggregate_value = 0;
                    }
                    break;
                default:
                    break;
            }

            $this->active_step === 2 ? $this->inner_query_aggregate = $aggregate_value : $this->outer_query_aggregate = $aggregate_value;
        }
    }

    public function resetSelected($step)
    {
        switch ($step) {
            case 1:
                $this->selectedColumnInnerQuery = [];
                break;
            case 2:
                $this->selectedDataInnerQuery = [];
                break;
            case 3:
                $this->selectedColumnOuterQuery = [];
                break;
            case 4:
                $this->selectedDataOuterQuery = [];
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
        $answerInnerQuery = $this->inner_query;
        $answerSubquery = $this->exercise->answer['queries'];

        // create array to store inner & full subquery answer arrays
        $logExerciseAnswer = array(
            'inner_query' => "",
            'subquery' => ""
        );

        //get the confident tag
        $conTag = $this->confidentTag($value);

        // create instance of Log Exercise, initialize common values
        $logExercise = new LogExercise();
        $logExercise->student_id = Student::where('user_id', Auth::user()->id)->first()->id;
        $logExercise->schedule_id = $this->schedule->id;
        $logExercise->exercise_id = $this->exercise->id;
        $logExercise->time = 0;

        // check whether the generated answer matches the correct & the array of generated answer
        $finalAnswerInnerQuery = $this->checkAnswer($db, $answerInnerQuery);

        // assign answer with generated inner query array
        $logExerciseAnswer['inner_query'] = $finalAnswerInnerQuery['generatedAnswer'];

        // if the generated answer is correct, continue to next question or return to home. If not, throw error message
        if ($finalAnswerInnerQuery['isCorrect']) {
            // check whether the generated answer matches the correct & the array of generated answer
            $finalAnswerSubquery = $this->checkAnswer($db, $answerSubquery, false);

            // assign answer with generated subquery array
            $logExerciseAnswer['subquery'] = $finalAnswerSubquery['generatedAnswer'];

            if ($finalAnswerSubquery['isCorrect']) {
                // set the status to correct and save the log data
                $logExercise->answer = json_encode($logExerciseAnswer);
                $logExercise->status = 'correct';
                $logExercise->confident= $conTag;
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
                    $this->dispatchBrowserEvent('showSuccessToast', ['message' => 'Jawaban benar, mengarahkan ke soal berikutnya...', 'nextQuestionUrl' => $this->nextQuestionUrl]);
                } else {
                    $this->dispatchBrowserEvent('showDoneAlert', ['message' => 'Selamat, Kamu telah menyelesaikan seluruh latihan dengan benar']);
                }

                return;
            }
        }

        // set the status to incorrect and save the log data
        $logExercise->answer = json_encode($logExerciseAnswer);
        $logExercise->status = 'incorrect';
        $logExercise->confident= $conTag;
        $logExercise->save();

        $this->dispatchBrowserEvent('showErrorToast', ['message' => 'Jawaban salah, silahkan periksa kembali']);
    }

    private function checkAnswer($db, $correctAnswerQuery, $innerQuery = true)
    {
        // get the correct & generated answer array
        $correctAnswer = $db->getQueryData($this->db_name, $correctAnswerQuery);

        // make generated answer for standard & aggregation query
        $generatedAnswer = null;

        $step = $innerQuery ? 2 : 4;
        $selectedColumnQuery = $innerQuery ? $this->selectedColumnInnerQuery : $this->selectedColumnOuterQuery;
        $queryTableColumns = $innerQuery ? $this->inner_query_table[0]['columns'] : $this->outer_query_table[0]['columns'];
        $selectedDataQuery = $innerQuery ? $this->selectedDataInnerQuery : $this->selectedDataOuterQuery;
        $query_table_filtered = $innerQuery ? $this->inner_query_table_filtered : $this->outer_query_table_filtered;
        $query_aggregate = $innerQuery ? $this->inner_query_aggregate : $this->outer_query_aggregate;

        // check if the query contain aggregation. If yes construct the aggregation answer, otherwise construct the common answer
        if ($this->steps[$step]['aggregation']) {
            $query_aggregate_key = $this->steps[$step]['aggregationKeyword'] . (count($selectedColumnQuery) == count($queryTableColumns) ? "(*)" : "(" . implode(', ', $selectedColumnQuery) . ")");
            $generatedAnswer = [
                [
                    $query_aggregate_key => strval($query_aggregate)
                ]
            ];
        } else {
            $generatedAnswer = array_values(array_intersect_key($query_table_filtered, array_flip($selectedDataQuery)));
        }

        $isCorrect = $this->compareAnswerArrays($correctAnswer, $generatedAnswer);

        return [
            'isCorrect' => $isCorrect,
            'generatedAnswer' => $generatedAnswer
        ];
    }

    private function compareAnswerArrays($array1, $array2)
    {
        $array1_upper = $this->array_change_key_case_recursive($array1, CASE_UPPER);
        $array2_upper = $this->array_change_key_case_recursive($array2, CASE_UPPER);

        sort($array1_upper);
        sort($array2_upper);

        if (count($array1_upper) !== count($array2_upper)) {
            return false;
        }
        foreach ($array1_upper as $key => $value) {
            if (!isset($array2_upper[$key]) || $array2_upper[$key] != $value) {
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
        }else {
            $conTag = "tidak yakin";
        }
        return $conTag;
    }
}
