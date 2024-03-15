<div class="modal fade" id="edit-modal-{{ $package->id }}" role="dialog" tabindex="-1" data-backdrop="static"
    aria-labelledby="edit-modal-{{ $package->id }}-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('packages.update', $package->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-modal-label">
                        Ubah Data Paket Soal
                    </h5>
                    <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class=" modal-body">
                    <div class="form-group">
                        <label for="name">Nama Paket</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="Masukkan Nama Paket"
                            value="{{ old('name') ?? $package->name }}" 
                            data-id="edit_nama_paket_{{ $package->id }}">
                        @error('name')
                            <div class="invalid-feedback" data-id="invalid_name_edit_packages">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Topic</label>
                        <select name="topic_id" class="form-control select2 @error('topic_id') is-invalid @enderror">
                            <option value="#" selected disabled>Topic Paket</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}"
                                    {{ $package->topic_id == $topic->id ? 'selected' : '' }}>
                                    {{ $topic->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('topic_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" data-id="edit_paket_simpan_{{ $package->id }}">
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
