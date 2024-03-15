@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Latihan Soal Baru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('packages.index') }}">Manajemen Paket
                        Soal</a></div>
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
                                <input type="hidden" name="ddl_type" id="ddl_type" value="alter drop column">
                                <div id="stepper" class="bs-stepper">
                                    <!-- Header -->
                                    @include('packages.exercises.categories.DDL.drop-column.wizards.header')
                                    <!-- Content -->
                                    @include('packages.exercises.categories.DDL.drop-column.wizards.content')
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
@endpush

@push('customScript')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/summernote-bs4.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <script src="{{ asset('assets/js/page/create-exercise-ddl-drop-column.js') }}"></script>
@endpush
