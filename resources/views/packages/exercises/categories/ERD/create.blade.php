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
                            <form id="create-exercise-form" data-id="create-exercise-form" class="wizard-content mt-2" action="{{ route('exercises.dml.store', $package->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="package_id" id="package_id" value="{{ $package->id }}">
                                <div id="stepper" class="bs-stepper">
                                    <!-- Header -->
                                    
                                    <!-- Content -->
                                    @if (request()->input('erd_type') == 'entity_attribute')
                                        @include('packages.exercises.categories.ERD.wizards.header_attr_entity')
                                        @include('packages.exercises.categories.ERD.wizards.content-entity')
                                    @else
                                        @include('packages.exercises.categories.ERD.wizards.header_relationship')
                                        @include('packages.exercises.categories.ERD.wizards.content-relationship')
                                    @endif
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
@endsection

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/summernote-bs4.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/joinjs/css/joint.css') }}">
@endpush

@push('customScript')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/summernote-bs4.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    @if ($type == 'erd')
        <script src="{{ asset('assets/joinjs/js/lodash.js') }}"></script>
        <script src="{{ asset('assets/joinjs/js/backbone.js') }}"></script>
        <script src="{{ asset('assets/joinjs/js/joint.js') }}"></script>
        @if (request()->input('erd_type') == 'entity_attribute')
            <script src="{{ asset('assets/js/page/create-practice-erd-entity.js') }}"></script>
        @else
            <script src="{{ asset('assets/js/page/create-practice-erd-relationship.js') }}"></script>
        @endif
    @else
        <script src="{{ asset('assets/js/page/create-exercise-dml.js') }}"></script>
    @endif
@endpush
