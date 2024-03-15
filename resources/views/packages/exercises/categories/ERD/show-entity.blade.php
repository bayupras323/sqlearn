@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Preview Latihan Soal Baru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('packages.index') }}">Manajemen Paket
                        Soal</a></div>
                <div class="breadcrumb-item"><a href="{{ url()->previous() }}">List Latihan</a></div>
                <div class="breadcrumb-item">Preview Latihan Soal Baru</div>
            </div>
        </div>

            @include('packages.exercises.categories.ERD.wizards.content-entity-show')

    </section>

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
    @if($type == 'erd')
        <script src="{{ asset('assets/joinjs/js/lodash.js') }}"></script>
        <script src="{{ asset('assets/joinjs/js/backbone.js') }}"></script>
        <script src="{{ asset('assets/joinjs/js/joint.js') }}"></script>
        <script type="text/javascript">
          var json = '<?php echo $fixJson?>';
        </script>
        <script src="{{ asset('assets/js/page/show-practice-erd-entity.js') }}"></script>
        <script type="text/javascript">
           window.onload = function(){
           document.body.className = "sidebar-mini";
                setTimeout(generateEditenJoinJsEntity, 1000);
            };
        </script>
    @else
        <script src="{{ asset('assets/js/page/create-exercise-dml.js') }}"></script>
    @endif
@endpush
