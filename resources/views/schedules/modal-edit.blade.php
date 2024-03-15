<div class="modal fade" id="edit-modal-{{ $schedule->id }}" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="edit-modal-{{ $schedule->id }}label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-{{ $schedule->id }}-label">Edit Jadwal</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('schedules.update', $schedule->id ) }}" method="post">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nama Jadwal" value="{{ $schedule->name }}" data-id="name_edit_jadwal_{{ $schedule->id }}">
                            @error('name')
                            <div class="invalid-feedback" data-id="invalid_name_edit_jadwal_{{ $schedule->id }}">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Tipe</label>
                            <select name="type" class="form-control select2 @error('tipe') is-invalid @enderror" data-id="tipe_edit_jadwal_{{ $schedule->id }}">
                                <option value="#" disabled>Pilih Tipe</option>
                                <option value="practice" @if( $schedule->type == 'practice') selected @endif >Latihan</option>
                                <option value="exam" @if( $schedule->type == 'exam') selected @endif >Ujian</option>
                            </select>
                            @error('tipe')
                            <div class="invalid-feedback" data-id="invalid_tipe_edit_jadwal_{{ $schedule->id }}">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="start_date">Waktu Mulai</label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ $schedule->start_date }}" data-id="start_date_edit_jadwal_{{ $schedule->id }}">
                            @error('start_date')
                            <div class="invalid-feedback" data-id="invalid_start_date_edit_jadwal_{{ $schedule->id }}">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="end_date">Waktu Selesai</label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ $schedule->end_date }}" data-id="end_date_edit_jadwal_{{ $schedule->id }}">
                            @error('end_date')
                            <div class="invalid-feedback" data-id="invalid_end_date_edit_jadwal_{{ $schedule->id }}">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="classroom[]" class="form-control select2 @error('classroom') is-invalid @enderror" placeholder="Pilih Kelas" multiple data-id="kelas_edit_jadwal_{{ $schedule->id }}">
                                @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" @foreach($schedule->classrooms as $class)@if( $classroom->id == $class->id ) selected @endif @endforeach >{{ $classroom->name }}</option>
                                @endforeach
                            </select>
                            @error('classroom')
                            <div class="invalid-feedback" data-id="invalid_kelas_edit_jadwal_{{ $schedule->id }}">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Paket Soal</label>
                            <select name="package_id" class="form-control select2 @error('package_id') is-invalid @enderror" data-id="package_edit_jadwal_{{ $schedule->id }}">
                                <option value="#" selected disabled>Pilih Paket Soal</option>
                                @foreach($packages as $package)
                                <option value="{{ $package->id }}" @if( $schedule->package_id == $package->id) selected @endif >{{ $package->name }}</option>
                                @endforeach
                            </select>
                            @error('package_id')
                            <div class="invalid-feedback" data-id="invalid_package_edit_jadwal_{{ $schedule->id }}">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" data-id="edit_jadwal_simpan_{{ $schedule->id }}">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
