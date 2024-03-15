
<div id="step-content-2" class="content">
    <div class="wizard-pane">

<p align="center"> Pertanyaan</p>
        <div class="form-group row">
          
            <div class="col-lg-12 col-md-9">
                <textarea class="summernote" name="question" id="question" data-id="question"><?php echo $exercise->question?></textarea>
                <p class="text-danger d-none" id="invalidQuestion">
                    Tulis pertanyaan terlebih dahulu
                </p>
            </div>
        </div>
        <p align="center"> Diagram</p>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary" id="btnAddCustomeEntity">
                            Add Custom Entity
                        </button>
                        <div id="input_wrapper" style="display: none;">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="name" placeholder="Ex: Karyawan" class="form-control"/>
                                </div>
                            </div>
                            <br>
                            <button type="button" class="btn btn-primary" id="add">
                                Submit
                            </button>
                            <button type="button" class="btn btn-danger" id="closeCustome">
                                Close
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary mt-2" id="btnAddCustomeAttribute">
                            Add Primary Attribute
                        </button>
                        <div id="input_wrappercustome" style="display: none;">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="nameattribute" placeholder="Ex: NIK" class="form-control"/>
                                </div>
                            </div>
                            <br>
                            <button type="button" class="btn btn-primary" id="addattribute">
                                Submit
                            </button>
                            <button type="button" class="btn btn-danger" id="closeCustomeAttr">
                                Close
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary mt-2" id="btnAddComposite">
                            Add Composite Attribute
                        </button>
                        <div id="input_wrappercomposite" style="display: none;">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="nameattributeComposite" placeholder="Ex: Nama Depan" class="form-control"/>
                                </div>
                            </div>
                            <br>
                            <button type="button" class="btn btn-primary" id="addattributeComposite">
                                Submit
                            </button>
                            <button type="button" class="btn btn-danger" id="closeComposite">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary mt-2" id="btnAddDerived">
                            Add Derived Attribute
                        </button>
                        <div id="input_wrapperderived" style="display: none;">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="nameattributeDerived" placeholder="Ex: Usia" class="form-control"/>
                                </div>
                            </div>
                            <br>
                            <button type="button" class="btn btn-primary" id="addattributeDerived">
                                Submit
                            </button>
                            <button type="button" class="btn btn-danger" id="closeDerived">
                                Close
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary mt-2" id="btnAddRelation">
                            Add Relation Shape
                        </button>
                        <div id="input_wrapper_relation" style="display: none;">
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="nameOfRelation" placeholder="Ex: Mempunyai" class="form-control" />
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control" id="relationType">
                                        <option disabled selected>Pilih Relasi</option>
                                        <option value="one_to_one">One To One</option>
                                        <option value="one_to_many">One To Many</option>
                                        <option value="many_to_many">Many To Many</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <button type="button" class="btn btn-primary" id="addRelationShape">
                                Submit
                            </button>
                            <button type="button" class="btn btn-danger" id="closeRelation">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12 col-md-9" id="joinJsGraph" style="margin-bottom: 5%;">

            </div>
        </div>
        <div class="col-lg-12" align="right">
          
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