<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Imports\UsersImport;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ClassroomController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:classrooms.index')->only('index');
        $this->middleware('permission:classrooms.create')->only('create', 'store');
        $this->middleware('permission:classrooms.edit')->only('edit', 'update');
        $this->middleware('permission:classrooms.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $classrooms = Classroom::with('permissions')
            ->when($request->has('classroom-keyword'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('classroom-keyword') . '%');
            })
            ->withCount('students')
            ->orderBy('name')
            ->paginate(10);

        return view('classrooms.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClassroomRequest $request)
    {
        DB::beginTransaction();

        $classroom = Classroom::create($request->validated());
        if ($request->hasFile('file-students')) {
            try {
                Excel::import(new UsersImport($classroom->id), $request->file('file-students'));
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                DB::rollBack();

                $failures = $e->failures();
                return redirect()->route('classrooms.index')
                    ->with(['failures' => $failures])
                    ->withErrors(['file-invalid-fields' => 'some fields are invalid']);
            }
        }

        DB::commit();
        return redirect()->route('classrooms.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $classroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function edit(Classroom $classroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        $classroom->update($request->validated());
        return redirect()->route('classrooms.index')->with('success', 'Kelas berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classroom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $classroom)
    {
        //delete data
        $classroom->delete();
        return redirect()->route('classrooms.index')->with('success', 'Kelas Berhasil Dihapus');
    }
}
