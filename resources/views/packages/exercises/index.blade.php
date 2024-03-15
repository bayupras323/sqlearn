@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>List Latihan</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="#">Dashboard</a>
            </div>
            <div class="breadcrumb-item">
                <a href="{{ route('packages.index') }}">Manajemen Paket Soal</a>
            </div>
            <div class="breadcrumb-item">List Latihan</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">Manajemen Latihan</h2>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>List Latihan Paket {{ $package->name }}</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-icon icon-left btn-primary" data-toggle="modal"
                                data-target="#choose-type">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Buat Latihan Baru
                            </button>
                            <a class="btn btn-info btn-primary active search" data-id="search-practice">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                Cari Latihan
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- start: Search Form -->
                        <div class="show-search mb-3" style="display: none">
                            <form id="search" method="GET"
                                action="{{ route('packages.show', $package->id) }}">
                                <div class="row">
                                    <div class="form-group col">
                                        <label for="name">Kata Kunci</label>
                                        <input type="text" name="keyword" class="form-control" id="keyword"
                                            placeholder="Nama database, Latihan Soal" data-id="keyword">
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-primary mr-1" type="submit" data-id="search">Submit</button>
                                    <a class="btn btn-secondary"
                                        href="{{ route('packages.show', $package->id) }}">Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                        <!-- end: Search Form -->

                        <div class="table-responsive">
                            <table class="table table-bordered table-md">
                                <caption></caption>
                                <tr>
                                    <th>#</th>
                                    <th>Database</th>
                                    <th style="width: 40%">Pertanyaan</th>
                                    <th style="max-width: 20%">Terakhir diperbarui</th>
                                    <th class="text-right" style="min-width: 16rem">Action</th>
                                </tr>
                                @forelse($exercises as $key => $exercise)
                                    <tr>
                                        <td>{{ $exercises->firstItem() + $key }}</td>
                                        <td class="text-break">{{ $exercise->database->name ?? 'no_database' }}</td>
                                        <td class="text-break">{!! $exercise->question !!}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($exercise->updated_at)->locale('id')->translatedFormat('l, d M Y H:i') }}
                                        </td>
                                        <td class="text-right">
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ route('exercises.show', ['package' => $package->id, 'exercise' =>$exercise->id]) }}"
                                                    class="btn btn-sm btn-outline-info btn-icon">
                                                    <i class="fas fa-search"></i>
                                                    Preview
                                                </a>
                                                <a href="{{ route('exercises.edit', ['package' => $package->id, 'exercise' => $exercise->id]) }}" class="btn btn-sm btn-info btn-icon ml-2">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </a>
                                                <form action="{{ route('exercises.destroy', $exercise->id) }}"
                                                      method="POST" class="ml-2" id="delete-exercise-{{ $exercise->id }}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button class="btn btn-sm btn-danger btn-icon"
                                                        data-confirm="
                                                            <i class='fas fa-exclamation-triangle text-danger'></i>
                                                            Hapus Latihan Soal? | Apakah Anda yakin ingin menghapus latihan soal ini?
                                                            Semua data yang terkait dengan latihan soal ini akan terhapus juga."
                                                        data-confirm-yes="$('#delete-exercise-{{ $exercise->id }}').submit()"
                                                        data-id="delete_exercise_{{ $exercise->id }}">
                                                        <i class="fas fa-times"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Data tidak ditemukan</td>
                                    </tr>
                                @endforelse
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $exercises->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
@include('packages.exercises.modals.choose-type')
@endsection

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@push('customScript')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.search').click(function (event) {
                event.stopPropagation();
                $(".show-search").slideToggle("fast");
                $(".show-import").hide();
            });
        })

    </script>
@endpush
