<div class="modal fade" id="add-modal" tabindex="-1" data-backdrop="static" role="dialog"
    aria-labelledby="add-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('packages.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="add-modal-label">Tambah Paket Soal</h5>
                    <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Paket</label>
                        <input data-id="nama_paket" type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" placeholder="Masukkan Nama Paket"
                            value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback" data-id="invalid_name_tambah_packages">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Topik</label>
                        <select data-id="jenis_topik" id="topic_id" name="topic_id"
                            class="form-control select2 @error('topic_id') is-invalid @enderror">
                            <option value="#" selected disabled>Topik Paket</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('topic_id')
                            <div class="invalid-feedback" data-id="invalid_topik_tambah_packages">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" data-id="button_submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
