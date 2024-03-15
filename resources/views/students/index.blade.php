@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Mahasiswa Kelas {{$classroom->name}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('classrooms.index') }}">Daftar Kelas</a></div>
                <div class="breadcrumb-item active">Daftar Mahasiswa {{ $classroom->name }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Mahasiswa Kelas {{$classroom->name}}</h2>
            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>List Mahasiswa Kelas {{$classroom->name}} </h4>
                            <div class="card-header-action">
                                <button class="btn btn-icon icon-left btn-primary" data-toggle="modal"data-id="import_mahasiswa"
                                    data-target="#import-modal-excel">
                                    <i class="fa fa-upload" aria-hidden="true" ></i> Import Mahasiswa
                                </button>
                                <button class="btn btn-icon icon-left btn-primary" data-id="tambah_mahasiswa"
                                    data-toggle="modal" data-target="#add-modal">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Tambah Mahasiswa
                                </button>
                                <a class="btn btn-icon btn-primary active search" data-id="cari_mahasiswa">
                                    <i class="fa fa-search" aria-hidden="true"></i> Cari Mahasiswa
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-search mb-3" style="display: none">
                                <form id="search" method="GET" action="{{ route('students.index', $classroom) }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="role">NIM</label>
                                            <input type="text" name="nim-keyword" class="form-control" id="nim-keyword"
                                            data-id="cari_nim" placeholder="Cari berdasarkan NIM">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="role">Nama Mahasiswa</label>
                                            <input type="text" name="name-keyword" class="form-control" id="name-keyword"
                                            data-id="cari_nama" placeholder="Cari berdasarkan Nama Mahasiswa">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit" data-id="submit_cari">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('students.index', $classroom) }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" data-id="kolom">
                                    <tbody>
                                        <tr>
                                            <th>#</th>
                                            <th>NIM</th>
                                            <th>Nama</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                        @forelse ($students as $key => $student)
                                            <tr>
                                                <td>{{ $students->firstItem() + $key }}</td>
                                                <td data-id="kolom_nim">{{ $student->student_id_number }}</td>
                                                <td data-id="kolom_nama">{{ $student->name }}</td>
                                                <td class="text-right">
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn-sm btn-info btn-icon edit-button"
                                                            data-id="edit_mahasiswa_{{ $students->firstItem() + $key }}" data-toggle="modal"
                                                            data-target="#edit-modal-{{ $student->id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <form action="{{ route('students.destroy', $student->id) }}"
                                                            method="POST" class="ml-2" id="delete-student-{{ $student->id }}">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button class="btn btn-sm btn-danger btn-icon"
                                                                data-confirm="
                                                                    <i class='fas fa-exclamation-triangle text-danger'></i>
                                                                    Hapus Mahasiswa? | Apakah Anda yakin ingin menghapus mahasiswa <strong>{{ $student->name }}</strong>?
                                                                    Semua data yang terkait dengan mahasiswa ini akan terhapus juga."
                                                                data-confirm-yes="$('#delete-student-{{ $student->id }}').submit()"
                                                                data-id="hapus_mahasiswa_{{ $students->firstItem() + $key }}">
                                                                <i class="fas fa-times"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <div class="modal fade" id="edit-modal-{{ $student->id }}"
                                                    role="dialog" tabindex="-1" data-backdrop="static"
                                                    aria-labelledby="edit-modal-{{ $student->id }}-label"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="edit-modal-label">Edit Data
                                                                    Mahasiswa
                                                                </h5>
                                                                <button type="button" class="close close-modal"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('students.update', $student->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label for="student_id_number">NIM</label>
                                                                        <input type="text" name="student_id_number"
                                                                            class="form-control @error('student_id_number') is-invalid @enderror"
                                                                            id="student_id_number" data-id="edit_nim_{{ $students->firstItem() + $key }}"
                                                                            placeholder="Masukkan 10 digit NIM"
                                                                            value="{{ $student->student_id_number }}">
                                                                        @error('student_id_number')
                                                                            <div class="invalid-feedback"
                                                                                data-id="edit_invalid_nim">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="name">Nama Mahasiswa</label>
                                                                        <input type="text" name="name"
                                                                            class="form-control select2 @error('name') is-invalid @enderror"
                                                                            id="name" data-id="edit_nama_{{ $students->firstItem() + $key }}"
                                                                            placeholder="Masukkan Nama Mahasiswa"
                                                                            value="{{ $student->name }}">
                                                                        @error('name')
                                                                            <div class="invalid-feedback"
                                                                                data-id="edit_invalid_name">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                    <input type="hidden" name="classrooms_id"
                                                                        value="{{ $classroom->id }}">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-secondary close-modal" data
                                                                        data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        data-id="ubah_mahasiswa_{{ $students->firstItem() + $key }}">Ubah</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted" data-id="data_none">Tidak ada data untuk ditampilkan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $students->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Import Data -->

    <div class="modal fade" id="import-modal-excel" tabindex="-1" data-backdrop="static" role="dialog"
        aria-labelledby="import-modal-label" aria-hidden="true">
        <form action="{{ route('students.import') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" , value="{{ Auth::user()->id }}">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Mahasiswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="custom-file">
                                <label for="file-upload" class="custom-file-label">Import Data Excel </label>
                                <input type="file"
                                    class="form-control custom-file-input @error('file-students') is-invalid @enderror" data-id="file_import"
                                    id="file-upload" name="file-students" value="{{ old('file-students') }}">
                                @error('file-students')
                                    <div class="invalid-feedback" data-id="invalid_import">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="helper">
                                    Unggah file Excel berekstensi .xls, .xlsx, atau .csv sesuai template <a
                                        href="{{ asset('storage/template/sqlearn_list_of_students_format.xlsx') }}"
                                        class="btn-link">berikut</a>
                                </div>
                                @if (session()->get('failures'))
                                    <div class="alert alert-danger" class="alert alert-danger" role="alert" data-id="import_failed">
                                        @foreach (session()->get('failures') as $failure)
                                            @foreach ($failure->errors() as $error)
                                                {{ $error }} <br>
                                            @endforeach
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="classrooms_id" value="{{ $classroom->id }}">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" data-id="save_import">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- Modal Add Data -->
    <div class="modal fade" id="add-modal" tabindex="-1" data-backdrop="static" role="dialog"
        aria-labelledby="add-modal-label" aria-hidden="true">
        <form action="{{ route('students.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" , value="{{ Auth::user()->id }}">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Mahasiswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="student_id_number">NIM</label>
                            <input type="text" class="form-control @error('student_id_number') is-invalid @enderror"
                                data-id="nim_mahasiswa" id="student_id_number" name="student_id_number"
                                placeholder="Masukkan 10 digit NIM" value="{{ old('student_id_number') }}">
                            @error('student_id_number')
                                <div class="invalid-feedback" data-id="add_invalid_nim">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama Mahasiswa</label>
                            <input type="text" name="name"
                                class="form-control select2 @error('name') is-invalid @enderror" id="name"
                                data-id="nama_mahasiswa" name="name" placeholder="Masukkan Nama Mahasiswa"
                                value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback"data-id="add_invalid_nama">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <input type="hidden" name="classrooms_id" value="{{ $classroom->id }}">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" data-id="save_mahasiswa">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('customScript')
    <script>
        $(document).ready(function() {
            $('.import').click(function(event) {
                event.stopPropagation();
                $(".show-import").slideToggle("fast");
                $(".show-search").hide();
            });

            $('.search').click(function(event) {
                event.stopPropagation();
                $(".show-search").slideToggle("fast");
                $(".show-import").hide();
            });

            //ganti label berdasarkan nama file
            $('#file-upload').change(function() {
                var i = $(this).prev('label').clone();
                var file = $('#file-upload')[0].files[0].name;
                $(this).prev('label').text(file);
            });

            $('.modal').appendTo('body');

            //tampil modal insert ketika ada validasi error
            @if ($errors->has('insert-invalid-fields'))
                $('#add-modal').modal('show');
            @endif

            // tampil modal import ketika ada validasi error
            @if ($errors->has('import-invalid-fields'))
                $('#import-modal-excel').modal('show');
            @endif

            //hide error di field ketika tombol close pada modal diklik
            $('.close-modal').click(function() {
                $('input, select').removeClass('is-invalid')
            })

            //tampil modal edit ketika ada validasi error
            @if ($errors->has('update-invalid-fields'))
                var id = {{ $errors->first('student-id') }}
                $(`#edit-modal-${id}`).modal('show');
            @endif

            //aktifkan tombol edit ketika halaman sudah ter-load sepenuhnya
            $('.edit-button').prop("disabled", false);

            //isi data modal edit sesuai tombol edit yang diklik
            $('#edit-modal').on('show.bs.modal', function(event) {
                var action = $(event.relatedTarget).data('action')
                var student = $(event.relatedTarget).data('student')
                $(this).find('.modal-content form').attr('action', action)
                $(this).find('.modal-body #student_id_number').val(student.student_id_number)
                $(this).find('.modal-body #name').val(student.name)
                $(this).find('.modal-body #classroom_id').val(student.classroom_id)
            })
        });
    </script>
@endpush

@push('customStyle')
@endpush
