<?php

namespace App\Http\Controllers\Exercises;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Topic;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Models\Database;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:packages.index')->only('index');
        $this->middleware('permission:packages.create')->only('create', 'store');
        $this->middleware('permission:packages.edit')->only('edit', 'update');
        $this->middleware('permission:packages.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $topics = Topic::all();
        $packages = Package::with(['topic', 'user'])
            ->when($request->has('paket-keyword'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('paket-keyword') . '%');
            })
            ->orderBy('name')
            ->paginate(10);
        return view('packages.index', compact('packages', 'topics'));
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
     * @param  \App\Http\Requests\StorePackageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePackageRequest $request)
    {
        try {
            Package::create([
                'user_id'   => Auth::user()->id,
                'topic_id'  => $request->topic_id,
                'name'      => $request->name,
            ]);
            return redirect()->route('packages.index')->with('success', 'Paket Soal Berhasil Ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('packages.index')->with('alert', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Package  $package
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package, Request $request)
    {
        $database = Database::where('name', 'like', '%' . $request->input('keyword') . '%')->first();
        $exercises = Exercise::with('database')
            ->where('package_id', $package->id)
            ->when($request->has('keyword'), function ($query) use ($request, $database, $package) {
                if ($database != null) {
                    $query->where('question', 'like', '%' . $request->input('keyword') . '%')
                        ->orWhere('database_id', $database->id)
                        ->where('package_id', $package->id);
                } else {
                    $query->where('question', 'like', '%' . $request->input('keyword') . '%')
                        ->where('package_id', $package->id);
                }
            })
            ->paginate(10);
        return view('packages.exercises.index', compact('package', 'exercises'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePackageRequest  $request
     * @param  Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        $package->user_id = Auth::user()->id;
        $package->update($request->validated());
        return redirect()->route('packages.index')->with('success', 'Paket Soal berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('packages.index')->with('success', 'Paket Berhasil Dihapus');
    }
}
