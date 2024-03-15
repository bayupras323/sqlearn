<div class="modal fade" id="create-database" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="newDatabase" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        Tambah Database Baru
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col">
                            <label for="name">Nama Database</label>
                            <input type="text" class="form-control" name="name" id="name" data-id="name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="sql_file">SQL File</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="sql_file" id="sql_file"
                                    data-id="sql_file">
                                <label class="custom-file-label" for="sql_file">Choose file</label>
                                <small class="form-text text-muted">
                                    Hanya upload file berformat .sql
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('customScript')
    <script src="{{ asset('assets/js/page/create-database.js') }}"></script>
@endpush
