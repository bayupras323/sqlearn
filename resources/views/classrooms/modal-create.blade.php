<div class="modal fade" id="add-modal" data-backdrop="static" role="dialog" aria-labelledby="add-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-modal-label">Tambah Kelas</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('classrooms.store') }}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="user_id" , value="{{ Auth::user()->id }}">
                    <div class="form-group">
                        <label for="name">Nama Kelas</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="Misal: TI-1A, SIB-2C" value="{{ old('name') }}"
                            data-id="tambah_nama_kelas">
                        @error('name')
                            <div class="invalid-feedback" data-id="tambah_kelas_nama_error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester" class="form-control select2 @error('semester') is-invalid @enderror"
                            data-id="tambah_kelas_semester">
                            <option value="#" selected disabled>Pilih Semester</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                        @error('semester')
                            <div class="invalid-feedback" data-id="tambah_kelas_semester_error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <label for="file-upload" class="custom-file-label">Daftar Mahasiswa (Opsional)</label>
                            <input type="file"
                                class="form-control custom-file-input @error('file-students') is-invalid @enderror"
                                id="file-upload" name="file-students" value="{{ old('file-students') }}"
                                data-id="tambah_kelas_file">
                            @error('file-students')
                                <div class="invalid-feedback" data-id="tambah_kelas_file_error">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="helper" data-id="tambah_kelas_helper">
                                Unggah file Excel berekstensi .xls, .xlsx, atau .csv sesuai template <a
                                    href="{{ asset('storage/template/sqlearn_list_of_students_format.xlsx') }}"
                                    class="btn-link">berikut</a>
                            </div>
                            @if (session()->get('failures'))
                                <div class="alert alert-danger" class="alert alert-danger" role="alert">
                                    @foreach (session()->get('failures') as $failure)
                                        @foreach ($failure->errors() as $error)
                                            {{ $error }} <br>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" data-id="tambah_kelas_simpan">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
