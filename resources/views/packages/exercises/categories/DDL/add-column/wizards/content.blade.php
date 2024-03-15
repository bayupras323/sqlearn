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
                        @foreach($databases as $database)
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
                <label for="db_name" class="col-md-2 pt-1 text-md-right text-left">
                    Database yang dipilih
                </label>
                <div class="col-md-10">
                    <p class="text-muted" id="db_name" data-id="db_name"></p>
                    <input type="hidden" name="database_id" id="database_id" data-id="database_id">
                </div>
            </div>
            <div class="form-group row">
                <label for="table" class="col-md-2 text-md-right text-left">
                    Tabel yang Akan Digunakan
                </label>
                <div class="col-md-10">
                    <div id="accordion_selected">
                        <p class="text-muted">Pilih Database Terlebih Dahulu</p>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="answer" class="col-md-2 text-md-right text-left">
                    Kolom yang Akan Ditambahkan
                </label>
                <div class="col-md-10">
                    <div id="addColumnFormContainer">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="addColumnBtn"><i class="fas fa-plus"></i> Tambah Kolom</button>
                </div>
            </div>
            <div class="form-group row">
                <label for="question" class="col-md-2 text-md-right text-left">
                    Pertanyaan
                </label>
                <div class="col-md-10">
                    <textarea class="summernote" name="question" id="question" data-id="question"></textarea>
                    <input type="hidden" name="answer" id="answer" class="d-none">
                    <input type="hidden" name="additions" id="additions" class="d-none">
                    <p class="text-danger d-none" id="invalidQuestion">
                        Tulis pertanyaan terlebih dahulu
                    </p>
                    <div class="form-check">
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
                        <label for="additional"></label>
                        <select class="form-control col-4 d-none" name="additional" id="additional">
                            <option value="placeholder" selected disabled>Tambah Data Pengecoh</option>
                            <option value="tableName">Tambah Nama Tabel</option>
                            <option value="columnName">Tambah Nama Kolom</option>
                            <option value="columnSize">Tambah Size Kolom</option>
                            <option value="columnDefault">Tambah Default Kolom</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-10 offset-md-2 text-right">
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
