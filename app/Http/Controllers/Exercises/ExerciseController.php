<?php

namespace App\Http\Controllers\Exercises;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Exercises\SQL\DataDefinitionController;
use App\Http\Controllers\Exercises\SQL\DataManipulationController;
use App\Http\Controllers\Exercises\SQL\ParsonsProblem2dController;
use App\Http\Controllers\Exercises\Erd\RelationshipController;
use App\Http\Controllers\Exercises\Erd\EntityController;
use App\Models\Addition;
use App\Models\Database;
use App\Models\Exercise;
use App\Models\Package;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     */
    public function create(Package $package, Request $request): View
    {
        $databases = Database::all();
        if (!empty($request->type)) {
            $type = $request->type;
            if ($type == 'DDL') {
                if (!empty($request->ddl_type)) {
                    $ddl_type = $request->ddl_type;
                    return match ($ddl_type) {
                        'drop table' => view('packages.exercises.categories.DDL.drop-table.create', compact('databases', 'package', 'type')),
                        'alter add column' => view('packages.exercises.categories.DDL.add-column.create', compact('databases', 'package', 'type')),
                        'alter rename column' => view('packages.exercises.categories.DDL.rename-column.create', compact('databases', 'package', 'type')),
                        'alter modify column' => view('packages.exercises.categories.DDL.modify-column.create', compact('databases', 'package', 'type')),
                        'alter drop column' => view('packages.exercises.categories.DDL.drop-column.create', compact('databases', 'package', 'type')),
                        default => view('packages.exercises.categories.DDL.create-table.create', compact('databases', 'package', 'type')),
                    };
                }
            } else if ($type == 'DML') {
                return view('packages.exercises.categories.DML.create', compact('databases', 'package', 'type'));
            } else if ($type == 'PP2D') {
                return view('packages.exercises.categories.PP2D.create', compact('databases', 'package', 'type'));
            } else {
                if (!empty($request->erd_type)) {
                    $typeErd = $request->erd_type;
                    return view('packages.exercises.categories.ERD.create', compact('databases', 'package', 'type', 'typeErd'));
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Package $package, Exercise $exercise)
    {
        if ($exercise->type == 'DDL') {
            $dataDefinitionController = new DataDefinitionController();
            return $dataDefinitionController->show($package, $exercise);
        } elseif ($exercise->type == 'DML') {
            $dataManipulationController = new DataManipulationController();
            return $dataManipulationController->show($package, $exercise);
        } elseif ($exercise->type == 'PP2D') {
            $parsonsProblem2d = new ParsonsProblem2dController();
            return $parsonsProblem2d->show($package, $exercise);
        }elseif ($package->topic_id == 1) {
            $entityController = new EntityController();
            return $entityController->show($exercise);
        } else {
            $relationshipController = new RelationshipController();
            return $relationshipController->show($exercise);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(Package $package, Exercise $exercise)
    {
        if ($exercise->type == 'DDL') {
            $dataDefinitionController = new DataDefinitionController();
            return $dataDefinitionController->edit($package, $exercise);
        } elseif ($exercise->type == 'DML') {
            $dataManipulationController = new DataManipulationController();
            return $dataManipulationController->edit($package, $exercise);
        } elseif ($package->topic_id == 1) {
            $entityController = new EntityController();
            return $entityController->edit($exercise);
        } else {
            $relationshipController = new RelationshipController();
            return $relationshipController->edit($exercise);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(Exercise $exercise): RedirectResponse
    {
        // delete additions
        Addition::where('exercise_id', $exercise->id)->delete();

        // delete exercise
        $exercise->delete();

        // redirect back
        return redirect()->back();
    }
}
