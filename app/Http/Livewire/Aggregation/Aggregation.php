<?php

namespace App\Http\Livewire\Aggregation;

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

class Aggregation extends Component
{

    public $currentPage;

    public $exercise, $nextQuestionUrl, $schedule;

    public $db_name;

    //variabel query = ambil query yang tersimpan di database (soal)
    //variabel query tabel name = ambil nama tabel database yang ditampilkan pada soal
    //variabel query = table semua data berdasarkan nama tabel
    public $query, $query_table_name, $query_table, $query_table_filtered, $query_aggregate, $query_table_filtered_with_addition;

    //step awal = 1
    public $active_step = 1;

    public $steps = [
        1 => [
            "status" => "active",
            "filled" => false,
            "aggregation" => true,
            "aggregationKeyword" => "",
            "additionColumns" => false,
            "checkedAll" => false
        ],
        2 => [
            "status" => "",
            "filled" => false,
            "aggregation" => true,
            "aggregationKeyword" => "",
            "additionColumns" => false,
            "checkedAll" => false
        ],
    ];

    public $selectedColumnQuery = [];
    public $selectedDataQuery = [];
    public $selectedColumnQueryWithAddition = [];

    public $correct_log;
    public $correct_log_count;

    //ambil data saat load baru di render pada view livewire
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

        //query diambil dari tabel excercise kolom answer
        $this->query = $this->exercise->answer['queries'];

        // check if query contain aggregation, and assign the steps variable
        $this->checkIfQueryContainAggregation($this->query, 1);
        $this->checkIfQueryContainAggregation($this->query, 2);

        //sqlhelper digunakan untuk menyimpan function sql yang mungkin terpakai
        //mengambil nama tabel pada query
        $this->query_table_name = SQLHelper::getTableNames($this->query)[0];

        //ambil database yang ada di exercise dari fungsi getTableData di Database Controller
        $this->db_name = Database::findOrFail($this->exercise->database_id)->name;

        //crete instance of Database Controller to get the related tables data
        $db = new DatabaseController();

        // get the related tables data
        $this->query_table = $db->getTableData($this->db_name, $this->query_table_name);
    }

    public function render()
    {
        // dd($this->query_table);
        return view('livewire.aggregation.aggregation');
    }

    //assign to check value aggregation dengan step yang ada
    private function checkIfQueryContainAggregation($query, $step)
    {
        $keyword = SQLHelper::findSQLAggregationKeywords($query);

        // assign aggregation attribute to true if the keyword available
        if (count($keyword) > 0) {
            $this->steps[$step]['aggregationKeyword'] = $keyword[0];
        }
    }

    public function toggleSelectAllColumn($step)
    {
        if ($this->steps[$step]['checkedAll']) {
            switch ($step) {
                case 1:
                    $this->selectedColumnQuery = array_map(fn ($item) => $item["name"], $this->query_table[0]['columns']);
                    break;
                case 2:
                    $this->selectedDataQuery = array_map('strval', array_keys($this->query_table_filtered));
                    if ($this->steps[$step]['aggregation']) {
                        $this->aggregateData($this->steps[$step]['aggregationKeyword'], $this->selectedDataQuery);
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

    public function toggleSelectedColumn($value, $step, $removeOnly = false)
    {
        //determine if its selected query
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
        //determine if its selected query
        $selectedDataQuery = $this->selectedDataQuery;

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

        // untuk melakukan perhitungan agregasi yang dipilih
        $this->aggregateData($this->steps[$step]['aggregationKeyword'], $selectedDataQuery);


        // re-assign the global variables with the new value
        $this->selectedDataQuery = $selectedDataQuery;
    }

    //untuk mengaktifkan tombol lanjut pada filled = false pada fungsi steps
    private function toggleStepFill($step, $value = true)
    {
        $this->steps[$step]['filled'] = $value;
    }

    //tombol reset
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

    private function filterQueryTable()
    {
        if ($this->active_step === 2) {
            $query_table = $this->query_table[0]['data'];
            $selected_columns = $this->selectedColumnQuery;
            $selected_columns_with_addition = $this->selectedColumnQueryWithAddition;

            $filtered_tables = array_map(fn ($value) => array_intersect_key($value, array_flip($selected_columns)), $query_table);
            $filtered_tables_with_addition = array_map(function ($value) use ($selected_columns_with_addition) {
                $result = array();
                foreach ($selected_columns_with_addition as $column) {
                    $result[$column] = $value[$column] ?? null;
                }
                return $result;
            }, $query_table);

            $this->query_table_filtered = $filtered_tables;
            $this->query_table_filtered_with_addition = $filtered_tables_with_addition;
        }
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

    private function aggregateData($keyword, $row)
    {
        if ($this->active_step === 2) {
            $filtered_data = $this->query_table_filtered;

            //inisialisasi nilai agregasi awal
            $aggregate_value = null;

            //penggabungan selected column & selected data
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

            //hasil perhitungan untuk diakses di public view
            $this->query_aggregate = $aggregate_value;
        }
    }

    public function submitAnswer($value)
    {
        // create instance of Database Controller to get the correct answer result
        $db = new DatabaseController();
        $answerQuery = $this->exercise->answer['queries'];

        // get the correct & generated answer array
        $correctAnswer = $db->getQueryData($this->db_name, $answerQuery);
        $query_aggregate_key = $this->steps[2]['aggregationKeyword'] . (count($this->selectedColumnQuery) > 1 ? "(*)" : "(" . $this->selectedColumnQuery[array_key_first($this->selectedColumnQuery)] . ")");
        $generatedAnswer = [
            [
                $query_aggregate_key => strval($this->query_aggregate)
            ]
        ];

        // create instance of Log Exercise, initialize common values
        $logExercise = new LogExercise();
        $logExercise->student_id = Student::where('user_id', Auth::user()->id)->first()->id;
        $logExercise->schedule_id = $this->schedule->id;
        $logExercise->exercise_id = $this->exercise->id;
        $logExercise->time = 0;
        $logExercise->answer = json_encode($generatedAnswer);

        //get the confident tag
        $conTag = $this->confidentTag($value);
        // check whether the generated answer matches the correct answer
        $isCorrect = $this->compareAnswerArrays($correctAnswer, $generatedAnswer);

        // if the generated answer is correct, continue to next question or return to home. If not, throw error message
        if ($isCorrect) {
            // set the status to correct and save the log data
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
            // set the status to incorrect and save the log data
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

    private function addAdditionColumns()
    {
        if ($this->active_step === 2) {
            $additionColumns = SQLHelper::getWhereAndOrderByClauseColumns($this->query);

            $mergeColumnQuery = array_unique(array_merge($this->selectedColumnQuery, $additionColumns), SORT_STRING | SORT_FLAG_CASE);

            $this->selectedColumnQueryWithAddition = $mergeColumnQuery;

            if (count($additionColumns) > 0) {
                if ($this->active_step === 2) {
                    $this->steps[1]['additionColumns'] = true;
                    $this->steps[2]['additionColumns'] = true;
                }
            }
        }
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
