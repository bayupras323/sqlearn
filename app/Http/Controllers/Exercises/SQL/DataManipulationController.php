<?php

namespace App\Http\Controllers\Exercises\SQL;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Exercises\DatabaseController;
use App\Http\Requests\StoreDataManipulationRequest;
use App\Http\Requests\UpdateDataManipulationRequest;
use App\Models\Database;
use App\Models\Exercise;
use App\Models\Package;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DataManipulationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(StoreDataManipulationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['type'] = 'DML';
        if(str_contains($validated['answer'], "FULL OUTER JOIN")) {
            $validated['answer'] = [
                'queries' => str_replace("FULL OUTER JOIN", "LEFT JOIN", $validated['answer']) .
                    " UNION " .
                    str_replace("FULL OUTER JOIN", "RIGHT JOIN", $validated['answer'])
            ];
        } else {
            $validated['answer'] = ['queries' => $validated['answer']];
        }

        (new Exercise)->create($validated);
        return redirect()->route('packages.show', $request['package_id']);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Package $package, Exercise $exercise): Factory|View|Application
    {
        $database = (new Database)->find($exercise->database_id);
        $answer = $exercise->answer['queries'];

        $databaseController = new DatabaseController;
        $tables = $databaseController->getSelectedTable($database->id, $answer);

        $tableData = [];
        foreach (array_reverse($tables) as $table) {
            $data = $databaseController->getTableData($database->name, $table);

            // menghapus elemen pertama dari array dan mengembalikan nilai elemen tersebut
            $firstElement = array_shift($data);
            // menggabungkan elemen array yang dikembalikan dari array_shift() dengan elemen array yang tersisa
            $arr = array_merge($firstElement, $data);

            $tableData[] = $arr;
        }

        $answer = $databaseController->getAnswer($database->id, $answer);
        $columns = array_keys((array) $answer[0]);

        return view('packages.exercises.categories.DML.show', compact('exercise', 'tableData', 'answer', 'columns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(Package $package, Exercise $exercise): View|Factory|Application
    {
        $databases = Database::all();

        $databaseController = new DatabaseController;
        $tables = $databaseController->getSelectedTable($exercise->database_id, $exercise->answer['queries']);

        $tableData = [];
        foreach ($tables as $table) {
            $data = $databaseController->getTableData($exercise->database->name, $table);

            // menghapus elemen pertama dari array dan mengembalikan nilai elemen tersebut
            $firstElement = array_shift($data);
            // menggabungkan elemen array yang dikembalikan dari array_shift() dengan elemen array yang tersisa
            $arr = array_merge($firstElement, $data);

            $tableData[] = $arr;
        }

        return view('packages.exercises.categories.DML.edit', compact('exercise', 'package', 'databases', 'tableData'));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(UpdateDataManipulationRequest $request, Exercise $exercise): RedirectResponse
    {
        $validated = $request->validated();
        $validated['type'] = 'DML';
        $validated['answer'] = ['queries' => $validated['answer']];

        $exercise->update($validated);
        return redirect()->route('packages.show', $request['package_id']);
    }
}
