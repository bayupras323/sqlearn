@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Latihan Soal Baru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">
                    <a href="{{ route('packages.index') }}">Manajemen Paket Soal</a>
                </div>
                <div class="breadcrumb-item"><a href="{{ url()->previous() }}">List Latihan</a></div>
                <div class="breadcrumb-item">Tambah Latihan Soal Baru</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Manajemen Latihan</h2>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tambah Latihan Soal Baru - {{ $type }}</h4>
                        </div>
                        <div class="card-body">
                            <form id="create-exercise-form" data-id="create-exercise-form" class="wizard-content mt-2"
                                  action="{{ route('exercises.ddl.store', $package->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="package_id" id="package_id" value="{{ $package->id }}">
                                <input type="hidden" name="database_id" id="database_id">
                                <input type="hidden" name="ddl_type" id="ddl_type" value="drop table">
                                <div class="form-group row align-items-center">
                                    <label for="database" class="col-md-3 text-md-right text-left">
                                        Pilih Database
                                    </label>
                                    <div class="col-lg-8 col-md-9">
                                        <select name="database" class="form-control select2" id="database">
                                            <option disabled selected>Pilih Database</option>
                                            @foreach($databases as $database)
                                                <option value="{{ $database->name }}" data-id="{{ $database->id }}">
                                                    {{ $database->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-8 col-md-9 offset-md-3">
                                        <p class="text-danger d-none" id="invalidDatabase">
                                            Pilih database terlebih dahulu
                                        </p>
                                        <button type="button" class="btn btn-link" data-toggle="modal"
                                                data-target="#create-database">
                                            <i class="fas fa-upload"></i>
                                            Upload database baru
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="table" class="col-md-3 text-md-right text-left">
                                        Pilih Tabel yang Akan Dihapus
                                    </label>
                                    <div class="col-lg-8 col-md-9">
                                        <div id="accordion">
                                            <p class="text-muted">Pilih Database Terlebih Dahulu</p>
                                        </div>
                                        <p class="text-danger d-none" id="invalidTable">
                                            Pilih tabel yang akan dihapus
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="question" class="col-md-3 text-md-right text-left">
                                        Pertanyaan
                                    </label>
                                    <div class="col-lg-8 col-md-9">
                                        <textarea class="summernote" name="question" id="question" data-id="question"></textarea>
                                        <input type="hidden" name="additions" id="additions" class="d-none">
                                        <p class="text-danger d-none" id="invalidQuestion">
                                            Tulis pertanyaan terlebih dahulu
                                        </p>
                                        <div class="form-check d-none" id="customization">
                                            <input class="form-check-input" type="checkbox" id="customTable" value="hide">
                                            <label class="form-check-label mb-2" for="customTable">
                                                Kustomisasi Tabel
                                                <span data-toggle="tooltip" data-placement="right" title="" data-original-title="Centang checkbox ini untuk melakukan customisasi pada atribut tabel sebagai pengecoh">
                                                    <i class="fas fa-info-circle"></i>
                                                </span>
                                            </label>
                                        </div>
                                        <div id="customizationTable"></div>
                                        <div class="form-group">
                                            <label for="additional" class="d-none"></label>
                                            <select class="form-control col-4 d-none" name="additional" id="additional">
                                                <option value="placeholder" selected disabled>Tambah Data Pengecoh</option>
                                                <option value="tableName">Tambah Nama Tabel</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8 col-md-9 offset-md-3 text-right">
                                        <button type="button" class="btn btn-icon btn-primary" onclick="submitForm()">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modals -->
    @include('packages.exercises.modals.create-database')
    @include('packages.exercises.modals.add-addition')

@endsection

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/summernote-bs4.css') }}">
@endpush

@push('customScript')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/page/create-exercise-ddl-drop-table.js') }}"></script>
@endpush
