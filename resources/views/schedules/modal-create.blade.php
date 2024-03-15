<div class="modal fade" id="add-modal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="add-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-modal-label">Tambah Jadwal</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('schedules.store') }}" method="post">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="Nama Jadwal" data-id="name_tambah_jadwal">
                        @error('name')
                            <div class="invalid-feedback" data-id="invalid_name_tambah_jadwal">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Tipe</label>
                        <select name="type" class="form-control select2 @error('type') is-invalid @enderror"
                            data-id="tipe_tambah_jadwal">
                            <option value="#" selected disabled>Pilih Tipe</option>
                            <option value="practice">Latihan</option>
                            <option value="exam">Ujian</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback" data-id="invalid_tipe_tambah_jadwal">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Waktu Mulai</label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror"
                                id="start_date" name="start_date" data-id="start_date_tambah_jadwal">
                            @error('start_date')
                                <div class="invalid-feedback" data-id="invalid_start_date_tambah_jadwal">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name">Waktu Selesai</label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                                id="end_date" name="end_date" data-id="end_date_tambah_jadwal">
                            @error('end_date')
                                <div class="invalid-feedback" data-id="invalid_end_date_tambah_jadwal">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Kelas</label>
                        <select name="classroom[]" class="form-control select2 @error('classroom') is-invalid @enderror"
                            placeholder="Pilih Kelas" data-id="kelas_tambah_jadwal" multiple>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" data-id="kelas_tambah_jadwal_{{ $classroom->id }}">
                                    {{ $classroom->name }}</option>
                            @endforeach
                        </select>
                        @error('classroom')
                            <div class="invalid-feedback" data-id="invalid_kelas_tambah_jadwal">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Paket Soal</label>
                        <select name="package_id" class="form-control select2 @error('package_id') is-invalid @enderror"
                            data-id="package_tambah_jadwal">
                            <option value="#" selected disabled>Pilih Paket Soal</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('package_id')
                            <div class="invalid-feedback" data-id="invalid_package_tambah_jadwal">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" data-id="tambah_jadwal_simpan">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
