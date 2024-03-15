<div class="modal fade" id="add-addition" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="choose-type-form">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Tambah Data Pengecoh
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col">
                            <label for="content" id="contentLabel"></label>
                            <input type="text" class="form-control" name="content" id="content">
                            <p class="text-danger d-none" id="invalidContent"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="submitAdditional" class="btn btn-primary">Simpan</button>
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
@endpush
