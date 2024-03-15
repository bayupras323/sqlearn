<?php

namespace App\Http\Controllers\Exercises\SQL;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Exercises\DatabaseController;
use App\Http\Requests\StoreDataDefinitionRequest;
use App\Http\Requests\UpdateDataDefinitionRequest;
use App\Models\Addition;
use App\Models\Database;
use App\Models\Exercise;
use App\Models\Package;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DataDefinitionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(StoreDataDefinitionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['type'] = 'DDL';

        // decode additions from string to json
        $data = json_decode($validated['additions']);

        // set variable for answer
        $answer = [];

        // generate answer
        switch ($validated['ddl_type']) {
            case 'create table':
                $answer = ['queries' => $validated['answer']];
                break;
            case 'drop table':
                $answer = ['table' => $validated['answer']];
                break;
            case 'alter add column':
            case 'alter rename column':
            case 'alter modify column':
            case 'alter drop column':
                $answer = json_decode($validated['answer']);
                break;
        }

        // insert data to exercises table
        $exercise = (new Exercise)->create([
            'package_id' => $validated['package_id'],
            'database_id' => $validated['database_id'],
            'type' => $validated['type'],
            'ddl_type' => $validated['ddl_type'],
            'question' => $validated['question'],
            'answer' => $answer,
        ]);

        if ($data != null) {
            $additions = [
                'table_name' => $data->tableName->additional,
                'column_name' => $data->columnName->additional,
                'column_size' => $data->columnSize->additional,
                'column_default' => $data->columnDefault->additional
            ];

            foreach ($additions as $type => $contents) {
                foreach ($contents as $content) {
                    Addition::create([
                        'exercise_id' => $exercise->id,
                        'type' => $type,
                        'content' => $content
                    ]);
                }
            }
        }

        return redirect()->route('packages.show', $request['package_id']);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Package $package, Exercise $exercise): Factory|View|Application
    {
        $database = (new Database)->find($exercise->database_id);
        $databaseController = new DatabaseController;

        // get preview data
        $preview = $databaseController->getPreviewTableDDL($database->id, $exercise);

        if (isset($exercise->ddl_type)) {
            switch ($exercise->ddl_type) {
                case 'create table':
                    // get answer query
                    $answer = $exercise->answer['queries'];
                    // get selected table
                    $table = $databaseController->getSelectedTable($database->id, $answer);
                    // merge data
                    $tableName = array_merge($preview['tableName']['desc'], $preview['tableName']['additional']);
                    $columnName = array_merge($preview['columnName']['desc'], $preview['columnName']['additional']);
                    $columnSize = array_merge($preview['columnSize']['desc'], $preview['columnSize']['additional']);
                    $columnDefault = array_merge($preview['columnDefault']['desc'], $preview['columnDefault']['additional']);
                    // shuffle data
                    shuffle($tableName);
                    shuffle($columnName);
                    shuffle($preview['dataType']);
                    shuffle($columnSize);
                    shuffle($columnDefault);
                    // set preview table
                    $previews = [
                        'tableName' => $tableName,
                        'columnName' => $columnName,
                        'dataType' => $preview['dataType'],
                        'columnSize' => $columnSize,
                        'columnKey' => $preview['columnKey'],
                        'columnNullability' => $preview['columnNullability'],
                        'columnDefault' => $columnDefault,
                        'columnExtra' => $preview['columnExtra'],
                    ];
                    // search the longest array
                    $numRows = max(
                        count($tableName),
                        count($columnName),
                        count($preview['dataType']),
                        count($columnSize),
                        count($preview['columnKey']),
                        count($preview['columnNullability']),
                        count($columnDefault),
                        count($preview['columnExtra'])
                    );
                    // get answer data
                    $answer = $databaseController->getAnswerDDL($database->id, $answer);

                    return view('packages.exercises.categories.DDL.create-table.show', compact('exercise', 'table', 'previews', 'numRows', 'answer'));
                    break;
                case 'drop table':
                    // get answer data
                    $answer = $exercise->answer['table'];
                    // shuffle data
                    shuffle($preview);

                    return view('packages.exercises.categories.DDL.drop-table.show', compact('exercise', 'preview',  'answer'));
                    break;
                case 'alter add column':
                    // generate answer
                    $answerTable = $exercise->answer['table'];
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
                    // merge data
                    $tableName = array_merge($preview['tableName']['desc'], $preview['tableName']['additional']);
                    $columnName = array_merge($preview['columnName']['desc'], $preview['columnName']['additional']);
                    $columnSize = array_merge($preview['columnSize']['desc'], $preview['columnSize']['additional']);
                    $columnDefault = array_merge($preview['columnDefault']['desc'], $preview['columnDefault']['additional']);
                    // add answer column
                    foreach ($columns as $column) {
                        $columnName[] = $column['name'];
                        if ($column['length'] != '') $columnSize[] = $column['length'];
                        if ($column['default'] != '') $columnDefault[] = $column['default'];
                    }
                    // shuffle data
                    shuffle($tableName);
                    shuffle($columnName);
                    shuffle($preview['dataType']);
                    shuffle($columnSize);
                    shuffle($columnDefault);
                    // set preview table
                    $previews = [
                        'tableName' => $tableName,
                        'columnName' => $columnName,
                        'dataType' => $preview['dataType'],
                        'columnSize' => $columnSize,
                        'columnKey' => $preview['columnKey'],
                        'columnNullability' => $preview['columnNullability'],
                        'columnDefault' => $columnDefault,
                        'columnExtra' => $preview['columnExtra'],
                    ];
                    // search the longest array
                    $numRows = max(
                        count($tableName),
                        count($columnName),
                        count($preview['dataType']),
                        count($columnSize),
                        count($preview['columnKey']),
                        count($preview['columnNullability']),
                        count($columnDefault),
                        count($preview['columnExtra'])
                    );

                    return view('packages.exercises.categories.DDL.add-column.show', compact('exercise', 'previews', 'numRows', 'answerTable', 'columns'));
                    break;
                case 'alter rename column':
                    // get answer data
                    $answerTable = $exercise->answer['table'];
                    $answerColumn = $exercise->answer['columns'];
                    // merge data
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

                    return view('packages.exercises.categories.DDL.rename-column.show', compact('exercise', 'answerTable', 'answerColumn', 'previews', 'numRows'));
                    break;
                case 'alter modify column':
                    // get answer data
                    $answerTable = $exercise->answer['table'];
                    $answerColumn = $exercise->answer['columns'];
                    foreach ($answerColumn as $answer) {
                        // search only data type
                        $type = preg_replace('/\((.*?)\)/', '', $answer['type']);

                        // search only size from data type
                        preg_match('/\((.*?)\)/', $answer['type'], $match);
                        $length = $match[1] ?? '';

                        $answers[] = [
                            'name' => $answer['name'],
                            'type' => $type,
                            'length' => $length,
                        ];
                    }
                    // merge data
                    $tableName = array_merge($preview['tableName']['desc'], $preview['tableName']['additional']);
                    $columnName = array_merge($preview['columnName']['desc'], $preview['columnName']['additional']);
                    $columnSize = array_merge($preview['columnSize']['desc'], $preview['columnSize']['additional']);
                    // shuffle data
                    shuffle($tableName);
                    shuffle($columnName);
                    shuffle($preview['dataType']);
                    shuffle($columnSize);
                    // set preview table
                    $previews = [
                        'tableName' => $tableName,
                        'columnName' => $columnName,
                        'dataType' => $preview['dataType'],
                        'columnSize' => $columnSize,
                    ];
                    // search the longest array
                    $numRows = max(
                        count($tableName),
                        count($columnName),
                        count($preview['dataType']),
                        count($columnSize),
                    );

                    return view('packages.exercises.categories.DDL.modify-column.show', compact('exercise', 'answerTable', 'answers', 'previews', 'numRows'));
                    break;
                case 'alter drop column':
                    // get answer data
                    $answerTable = $exercise->answer['table'];
                    $answerColumn = $exercise->answer['column'];
                    // merge data
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

                    return view('packages.exercises.categories.DDL.drop-column.show', compact('exercise', 'answerTable', 'answerColumn', 'previews', 'numRows'));
                    break;
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(Package $package, Exercise $exercise)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(UpdateDataDefinitionRequest $request, Exercise $exercise): RedirectResponse
    {
        //
    }
}
