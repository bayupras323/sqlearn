<div class="bs-stepper-content">
    <div id="step-content-1" class="content">
        <div class="wizard-pane">
            <div class="form-group row align-items-center">
                <label for="database" class="col-md-4 text-md-right text-left">
                    Pilih Database
                </label>
                <div class="col-lg-6 col-md-8">
                    <select name="database" class="form-control select2" id="database">
                        <option disabled selected>Pilih Database</option>
                        @foreach ($databases as $database)
                            <option value="{{ $database->name }}" data-id="{{ $database->id }}">
                                {{ $database->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 col-md-8 offset-md-4">
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
                <label for="table" class="col-md-4 text-md-right text-left">
                    Pilih Tabel yang Akan Digunakan
                </label>
                <div class="col-lg-6 col-md-8">
                    <div id="accordion">
                        <p class="text-muted">Pilih Database Terlebih Dahulu</p>
                    </div>
                    <p class="text-danger d-none" id="invalidTable">
                        Pilih tabel terlebih dahulu
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6 col-md-8 offset-md-4 text-right">
                    <button type="button" class="btn btn-icon icon-right btn-primary" onclick="nextStep()">
                        Next
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="step-content-2" class="content">
        <div class="wizard-pane">
            <div class="form-group row">
                <label for="db_name" class="col-md-3 pt-1 text-md-right text-left">
                    Database yang dipilih
                </label>
                <div class="col-lg-8 col-md-9">
                    <p class="text-muted" id="db_name" data-id="db_name"></p>
                    <input type="hidden" name="database_id" id="database_id" data-id="database_id">
                </div>
            </div>
            <div class="form-group row">
                <label for="table" class="col-md-3 text-md-right text-left">
                    Tabel yang Akan Digunakan
                </label>
                <div class="col-lg-8 col-md-9">
                    <div id="accordion_selected">
                        <p class="text-muted">Pilih Database Terlebih Dahulu</p>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="question" class="col-md-3 text-md-right text-left">
                    Pertanyaan
                </label>
                <div class="col-lg-8 col-md-9">
                    <textarea class="summernote" name="question" id="question" data-id="question"></textarea>
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
                    <textarea name="answer" id="answer" data-id="answer" class="form-control" style="height: 100px; resize: none"></textarea>
                    <p class="text-danger d-none" id="invalidAnswer">
                        Tulis jawaban benar terlebih dahulu
                    </p>
                    <p class="text-success d-none" id="correctAnswer">
                        Jawaban Benar
                    </p>
                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-8 col-md-9 text-md-left text-left">
                            <small class="text-muted">Tulis jawaban dalam bentuk kueri SQL</small>
                        </div>
                        <div class="col-lg-4 col-md-3">
                            <button type="button" class="btn btn-outline-success float-right" onclick="executeQuery()">
                                <i class="fas fa-play"></i>
                                Execute Query
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-8 col-md-9 offset-md-3 text-right">
                    <button type="button" class="btn btn-icon icon-right btn-secondary" onclick="previousStep()">
                        <i class="fas fa-arrow-left"></i>
                        Previous
                    </button>
                    <button type="button" class="btn btn-icon btn-primary" onclick="submitForm()">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('customStyle')
    <style>
        .is-invalid .select2-selection,
        .needs-validation~span>.select2-dropdown {
            border-color: red !important;
        }

        .invalid-accordion {
            border: red 1px solid !important;
        }

        .invalid-summernote {
            border: 1px solid red !important;
        }
    </style>
@endpush
