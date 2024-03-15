<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:user.index')->only('index');
        $this->middleware('permission:user.create')->only('create', 'store');
        $this->middleware('permission:user.edit')->only('edit', 'update');
        $this->middleware('permission:user.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //index -> menampilkan tabel data
        // mengambil data
        $users = DB::table('users')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->select('id', 'name', 'email', DB::raw("DATE_FORMAT(created_at, '%d %M %Y') as created_at"),
                DB::raw("DATE_FORMAT(updated_at, '%d %M %Y') as updated_at"))
            ->paginate(10);
        return view('users.index', compact('users','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // halaman tambah user
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        //simpan data
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return redirect(route('user.index'))->with('alert-user',['type'=>'success','content'=>'User Berhasil Ditambahkan']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //nampilkan detail satu user
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit')
            ->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //mengupdate data user ke database
        $validate = $request->validated();
        try {
            $user->update($validate);
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('alert-user', ['type'=>'danger','content'=>'Email yang dimasukkan '.$request->email.' telah digunakan sebelumnya']);
        }
        return redirect()->route('user.index')->with('alert-user', ['type'=>'success','content'=>'User Berhasil Diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //delete data
        $user->delete();
        return redirect()->route('user.index')->with('alert-user', ['type'=>'success','content'=>'User Berhasil Dihapus']);
    }

    public function export()
    {
        // export data ke excel
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import(Request $request)
    {
        // import excel ke data tables
        $users = Excel::toArray(new UsersImport, $request->import_file);
        $filter = UsersImport::filterRowCheckAndInsertOrUpdate($users,$request['update-existing']);
        return redirect()->route('user.index')->with('alert-user',$filter);
    }
}
