@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Latihan Soal</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('packages.index') }}">Manajemen Paket Soal</a></div>
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
                        <h4>Edit Latihan Soal</h4>
                    </div>
                    <div class="card-body">
                        <form id="edit-practice-form" data-id="edit-practice-form" class="wizard-content mt-2"
                            action="{{ route('exercises.dml.update', $exercise->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="package_id" id="package_id" value="{{ $package->id }}">
                            <div class="form-group row">
                                <label for="db_name" class="col-md-3 pt-1 text-md-right text-left">
                                    Database yang dipilih
                                </label>
                                <div class="col-lg-8 col-md-9">
                                    <p class="text-muted" id="db_name" data-id="db_name">
                                        {{ $exercise->database->name }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="table" class="col-md-3 text-md-right text-left">
                                    Tabel yang Akan Digunakan
                                </label>
                                <div class="col-lg-8 col-md-9">
                                    <div id="accordion_selected">
                                        @foreach($tableData as $table)
                                            <div class="accordion">
                                                <div class="accordion-header flex-fill ml-2" role="button"
                                                    data-toggle="collapse"
                                                    data-target="#collapse{{ $table['name'] }}">
                                                    {{ $table['name'] }}
                                                </div>
                                                <div class="accordion-body collapse"
                                                    id="collapse{{ $table['name'] }}">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    @foreach ($table['columns'] as $column)
                                                                        <th>{{ $column['name'] }}
                                                                        </th>
                                                                    @endforeach
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($table['data'] as $data)
                                                                    <tr>
                                                                        @foreach ($table['columns'] as $column)
                                                                            @php
                                                                                $data = (array) $data;
                                                                            @endphp
                                                                            <td>{{ $data[$column['name']] }}
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="question" class="col-md-3 text-md-right text-left">
                                    Pertanyaan
                                </label>
                                <div class="col-lg-8 col-md-9">
                                    <textarea class="summernote" name="question" id="question"
                                        data-id="question">{{ $exercise->question }}</textarea>
                                    <p class="text-danger d-none" id="invalidQuestion">
                                        Tulis pertanyaan terlebih dahulu
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="answer" class="col-md-3 text-md-right text-left">
                                    Jawaban Benar
                                </label>
                                <div class="col-lg-8 col-md-9">
                                    <textarea name="answer" id="answer" data-id="answer" class="form-control"
                                        style="height: 100px; resize:none">{{ $exercise->answer['queries'] }}</textarea>
                                    <small class="text-muted">Tulis jawaban dalam bentuk kueri SQL</small>
                                    <p class="text-danger d-none" id="invalidAnswer">
                                        Tulis jawaban benar terlebih dahulu
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-8 col-md-9 offset-md-3 text-right">
                                    <a href="{{ url()->previous() }}" class="btn btn-icon btn-secondary">
                                        Batal
                                    </a>
                                    <button type="button" class="btn btn-icon btn-primary" onclick="submitForm()">
                                        Simpan
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

@endsection

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('assets/css/summernote-bs4.css') }}">
    <style>
        .invalid-summernote {
            border: 1px solid red !important;
        }

    </style>
@endpush

@push('customScript')
    <script src="{{ asset('assets/js/summernote-bs4.js') }}"></script>
    <script>
        $('.summernote').summernote('lineHeight', 1);

        function submitForm() {
            if ($('#question').val() != '' && $('#answer').val() != '') {
                $('#edit-practice-form').submit();
            } else {
                if ($('#question').val() == '' ) {
                    $('#invalidQuestion').removeClass('d-none')
                    $('.note-editor').addClass('invalid-summernote')
                } else {
                    $('#invalidAnswer').removeClass('d-none')
                    $('#answer').addClass('is-invalid')
                }

            }
        }

    </script>
@endpush
