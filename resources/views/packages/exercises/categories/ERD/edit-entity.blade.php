@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Latihan Soal Baru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('packages.index') }}">Manajemen Paket
                        Soal</a></div>
                <div class="breadcrumb-item"><a href="{{ url()->previous() }}">List Latihan</a></div>
                <div class="breadcrumb-item">Edit Latihan Soal</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Manajemen Latihan</h2>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Latihan Soal - {{ $type }}</h4>
                        </div>
                        <div class="card-body">
                            <form id="create-exercise-form" data-id="create-exercise-form" class="wizard-content mt-2"
                                  method="POST">
                                @csrf

                                <div id="stepper" class="bs-stepper">
                                    <!-- Header -->
                                    {{--  @include('packages.exercises.categories.ERD.wizards.header')  --}}
                                    <!-- Content -->
                                    @include('packages.exercises.categories.ERD.wizards.content-entity-edit')
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
    @if($type == 'erd')
        <script src="{{ asset('assets/joinjs/js/lodash.js') }}"></script>
        <script src="{{ asset('assets/joinjs/js/backbone.js') }}"></script>
        <script src="{{ asset('assets/joinjs/js/joint.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript">
            function showDoneAlert(text, url) {
                Swal.fire({
                    title: 'Update Berhasil!',
                    text: text,
                    icon: 'success',
                    confirmButtonText: 'Oke'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            }

            function showErrorModal(text) {
                Swal.fire({
                    title: 'Mohon Maaf',
                    text: text,
                    icon: 'error',
                });
            }
            var json = '<?php echo $fixJson?>';

        </script>
        <script src="{{ asset('assets/js/page/edit-practice-erd-entity.js') }}"></script>
        <script type="text/javascript">
           window.onload = function(){
            document.body.className = "sidebar-mini";
                setTimeout(generateJoinJsEntity, 1000);
            };
        </script>
    @else
        <script src="{{ asset('assets/js/page/create-exercise-dml.js') }}"></script>
    @endif
@endpush
