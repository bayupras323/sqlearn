<div class="bs-stepper-content">
    <div id="step-content-1" class="content">
        <div class="wizard-pane">
            <div class="form-group row align-items-center">
                <label for="database" class="text-md-right text-left">
                    Pilih Database
                </label>
                <div class="col-lg-10 col-md-9">
                    <select name="database" class="form-control select2" id="database">
                        <option disabled selected>Pilih Database</option>
                        @foreach ($databases as $database)
                            <option value="{{ $database->name }}" data-id="{{ $database->id }}">
                                {{ $database->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12" align="center">
                    <p class="text-danger d-none" id="invalidDatabase">
                        Pilih database terlebih dahulu
                    </p>
                    <button type="button" class="btn btn-link" data-toggle="modal" data-target="#create-database">
                        <i class="fas fa-plus"></i>
                        Tambah database baru
                    </button>
                </div>
            </div>
        </div>

        <p align="center">Entity Yang Tersedia</p>
        <div class="col-lg-12">
            <div id="accordion">
                <p class="text-muted" id="choose_db_first" align="center">Pilih Database Terlebih Dahulu</p>
                <!--   -->
                <p style="">
                    <img id="loader_erd" src="{{ url('assets/img/spinner.gif') }}" style="background-color: white;width: 5%;display: none;">
                    <small id="loader_erd_text" style="display: none;">Proses pengambilan data</small>
                </p>
                <div class="table-responsive" style="display: none;">
                    <table class="table table-bordered table-md" id="show_entity_table">
                        <thead>
                            <tr>
                                <th>Tabel</th>
                                <th>Entity</th>
                                <th>Field</th>
                                <th>Tipe Data</th>
                            </tr>
                        </thead>
                        <tbody id="list_entity_table">
                        </tbody>
                    </table>
                </div>
                <p class="text-danger" align="center" style="display: none;" id="error_erd"></p>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-12 col-md-8 text-right">
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
                <input type="hidden" name="relation_check_m_m" id="relation_check_m_m" value="">
                <input type="hidden" id="_token_" value="{{ csrf_token() }}">
            </div>
        </div>

        <div class="form-group row">
            <label for="question" class="text-md-right text-left">
                Pertanyaan
            </label>
            <div class="col-lg-10 col-md-9">
                <textarea class="summernote" name="question" id="question" data-id="question"></textarea>
                <p class="text-danger d-none" id="invalidQuestion">
                    Tulis pertanyaan terlebih dahulu
                </p>
            </div>
        </div>
        <p align="center"> Diagram</p>
        <div class="row">
            <div class="col-lg-10 col-md-9" id="joinEntityGraph" style="margin-bottom: 5%;">

            </div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-primary" id="btnAddCustomeEntity">
                    Add Custom Entity
                </button>
                <div id="input_wrapper" style="display: none;">
                    <input type="text" id="name" />
                    <button type="button" class="btn btn-primary" id="add">
                        Submit
                    </button>
                </div>
                <button type="button" class="btn btn-primary mt-2" id="btnAddCustomeAttribute">
                    Add Custom Attribute
                </button>
                <div id="input_wrappercustome" style="display: none;">
                    <input type="text" id="nameattribute" />
                    <button type="button" class="btn btn-primary" id="addattribute">
                        Submit
                    </button>
                </div>
                <button type="button" class="btn btn-primary mt-2" id="btnAddComposite">
                    Add Composite Attribute
                </button>
                <div id="input_wrappercomposite" style="display: none;">
                    <input type="text" id="nameattributeComposite" />
                    <button type="button" class="btn btn-primary" id="addattributeComposite">
                        Submit
                    </button>
                </div>
                <button type="button" class="btn btn-primary mt-2" id="btnAddDerived">
                    Add Derived Attribute
                </button>
                <div id="input_wrapperderived" style="display: none;">
                    <input type="text" id="nameattributeDerived" />
                    <button type="button" class="btn btn-primary" id="addattributeDerived">
                        Submit
                    </button>
                </div>
                <button type="button" class="btn btn-primary mt-2" id="btnAddMultivalue">
                    Add Multivalue
                </button>
                <div id="input_wrappermultivalue" style="display: none;">
                    <input type="text" id="nameattributeMultivalue" />
                    <button type="button" class="btn btn-primary" id="addattributeMultivalue">
                        Submit
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-8 col-md-9 offset-md-3 text-right">
                <button type="button" class="btn btn-icon icon-right btn-secondary" onclick="previousStep()">
                    <i class="fas fa-arrow-left"></i>
                    Previous
                </button>
                <button type="button" class="btn btn-icon btn-primary" onclick="storeJson()">
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