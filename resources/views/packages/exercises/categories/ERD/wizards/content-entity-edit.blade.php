<div id="edit-content">
    <div class="form-group row">
        <label for="db_name" class="col-md-3 pt-1 text-md-right text-left">
            Database yang dipilih
        </label>
        <div class="col-lg-8 col-md-9">
            <p class="text-muted" id="db_name" data-id="db_name">{{$databaseSelected->name}}</p>
            <input type="hidden" name="database_id" id="database_id" data-id="database_id" value="{{$databaseSelected->name}}">
            <input type="hidden" id="_token_" value="{{ csrf_token() }}">
            <input type="hidden" name="db_id" id="db_id" value="{{$databaseSelected->id}}">
            <input type="hidden" name="exercise_id" id="exercise_id" value="{{$exercise->id}}">
            <input type="hidden" name="package_id" id="package_id" value="{{$exercise->package_id}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="question" class="text-md-right text-left">
            Pertanyaan
        </label>
        <div class="col-lg-10 col-md-9">
            <textarea class="summernote" name="question" id="question" data-id="question">{{$exercise->question}}</textarea>
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
            <button type="button" class="btn btn-icon btn-primary" onclick="storeJson()">
                Save
            </button>
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
