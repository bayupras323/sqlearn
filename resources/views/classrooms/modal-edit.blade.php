<div class="modal fade" id="edit-modal-{{ $classroom->id }}" role="dialog" tabindex="-1" data-backdrop="static"
    aria-labelledby="edit-modal-{{ $classroom->id }}-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">Ubah Kelas
                </h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('classrooms.update', $classroom->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class=" modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Kelas</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="Misal: TI-1A, SIB-2C" value="{{ $classroom->name }}"
                            data-id="edit_nama_kelas_{{ $classroom->id }}">
                        @error('name')
                            <div class="invalid-feedback" data-id="edit_kelas_nama_error_{{ $classroom->id }}">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester"
                            class="form-control select2 form-control @error('semester') is-invalid @enderror"
                            id="semester" data-id="edit_kelas_semester_{{ $classroom->id }}">
                            <option value="#" disabled>Pilih
                                Semester</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ $classroom->semester == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                        @error('semester')
                            <div class="invalid-feedback" data-id="edit_kelas_semester_error_{{ $classroom->id }}">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"
                        data-id="edit_kelas_simpan_{{ $classroom->id }}">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>
