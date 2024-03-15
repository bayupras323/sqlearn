<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentImportRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Classroom $classroom, Request $request)
    {
        $students = Student::select('students.id', 'students.student_id_number', 'users.name')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('classrooms_id', $classroom->id)
            ->when($request->hasAny(['name-keyword', 'nim-keyword']), function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->when($request->filled(['name-keyword', 'nim-keyword']), function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->input('name-keyword') . '%')
                            ->orWhere('student_id_number', 'like', '%' . $request->input('nim-keyword') . '%');
                    })
                        ->when($request->filled('name-keyword'), function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->input('name-keyword') . '%');
                        })
                        ->when($request->filled('nim-keyword'), function ($query) use ($request) {
                            $query->where('student_id_number', 'like', '%' . $request->input('nim-keyword') . '%');
                        });
                });
            })
            ->orderBy('users.name') // Menambahkan pengurutan berdasarkan kolom name
            ->paginate(10);

        return view('students.index', compact(['students', 'classroom']));
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
    public function store(StoreStudentRequest $request)
    {
        try {
            DB::beginTransaction();
            $email = $request->student_id_number . '@student.polinema.ac.id';

            $user = User::create([
                'name' => $request->name,
                'email' => $email,
                'password' => $request->student_id_number,
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_id_number' => $request->student_id_number,
                'classrooms_id' => $request->classrooms_id,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Mahasiswa Berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('alert', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {

        try {
            DB::beginTransaction();
            $email = $request->student_id_number . '@student.polinema.ac.id';

            User::where('id', $student->user_id)
                ->update([
                    'name' => $request->name,
                    'email' => $email,
                    'password' => $request->student_id_number,
                ]);

            $student->update([
                'student_id_number' => $request->student_id_number,
                'classrooms_id' => $request->classrooms_id,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Mahasiswa Berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('alert', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        User::find($student->user_id)->delete();
        return redirect()->back()->with('success', 'Mahasiswa Berhasil Dihapus');
    }

    public function import(StoreStudentImportRequest $request)
    {
        if ($request->hasFile('file-students')) {
            try {
                Excel::import(new UsersImport($request->get('classrooms_id')), $request->file('file-students'));
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                return redirect()->back()
                    ->with(['failures' => $failures])
                    ->withErrors(['file-invalid-fields' => 'some fields are invalid']);
            }
        }

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan');
    }
}
