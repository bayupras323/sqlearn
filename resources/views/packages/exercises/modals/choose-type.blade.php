<div class="modal fade" id="choose-type" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('exercises.create', $package->id) }}" method="GET"
                id="choose-type-form">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Tambah Latihan Soal Baru
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col">
                            <label for="type">Pilih Jenis Soal</label>
                            <select class="form-control select2" name="type" id="type" required>
                                <option disabled selected>Pilih Jenis Soal</option>
                                <option value="erd">Entity Relationship Diagram (ERD)</option>
                                <option value="DDL">Data Definition Language (DDL)</option>
                                <option value="DML">Data Manipulation Language (DML)</option>
                                <option value="PP2D">Parsons Problem 2D</option>
                            </select>
                            <p class="text-danger d-none" id="invalidType">
                                Pilih jenis soal terlebih dahulu
                            </p>
                        </div>
                    </div>
                    <div class="row d-none" id="ddl_type">
                        <div class="form-group col">
                            <label for="ddl_type_choose">Pilih Jenis DDL</label>
                            <select class="form-control select2" name="ddl_type" id="ddl_type_choose" required>
                                <option disabled selected>Pilih Jenis DDL</option>
                                <option value="create table">Create Table</option>
                                <option value="drop table">Drop Table</option>
                                <option value="alter add column">Alter Add Column</option>
                                <option value="alter drop column">Alter Drop Column</option>
                                <option value="alter rename column">Alter Rename Column</option>
                                <option value="alter modify column">Alter Modify Column</option>
                            </select>
                            <p class="text-danger d-none" id="ddl_type_error">
                                Pilih jenis ddl terlebih dahulu
                            </p>
                        </div>
                    </div>
                    <div class="row d-none" id="erd_type">
                        <div class="form-group col">
                            <label for="erd_type_choose">Pilih Jenis Erd</label>
                            <select class="form-control select2" name="erd_type" id="erd_type_choose" required>
                                <option disabled selected>Pilih Jenis Erd</option>
                                <option value="entity_attribute">Entity and Attribute</option>
                                <option value="relationship">Relationship</option>
                            </select>
                            <p class="text-danger" id="erd_type_error" style="display: none;">
                                Pilih jenis erd terlebih dahulu
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitFormChooseType()">Lanjut</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('customStyle')
    <style>
        .is-invalid .select2-selection,
        .needs-validation~span>.select2-dropdown {
            border-color: red !important;
        }

    </style>
@endpush

@push('customScript')
    <script>
        $('#type').on('change', function () {
            // remove error message
            $('#type + span').removeClass('is-invalid')
            $('#invalidType').addClass('d-none')
            $('#erd_type_error').hide();
            if($(this).val() === 'erd') {
                $('#ddl_type').addClass('d-none')
                $('#erd_type').removeClass('d-none');
            } else if($(this).val() === 'DDL') {
                $('#erd_type').addClass('d-none');
                $('#ddl_type').removeClass('d-none')
            } else {
                $('#erd_type').addClass('d-none');
                $('#ddl_type').addClass('d-none')
            }
        })

        function submitFormChooseType() {
            if ($('#type').val() != null) {
                if($('#type').val() === 'erd') {
                    if($('#erd_type_choose').val() == null) {
                        $('#erd_type_error').show();
                    } else {
                        $('#choose-type-form').submit();
                    }
                } else if($('#type').val() === 'DDL') {
                    if($('#ddl_type_choose').val() == null) {
                        $('#ddl_type_error').removeClass('d-none');
                    } else {
                        $('#choose-type-form').submit();
                    }
                } else {
                    $('#choose-type-form').submit();
                }
            } else {
                $('#type + span').addClass('is-invalid')
                $('#invalidType').removeClass('d-none')
            }
        }

    </script>
@endpush
