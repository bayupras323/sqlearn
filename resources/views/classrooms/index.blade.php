@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Manajemen Kelas</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">Manajemen Kelas</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Manajemen Kelas</h2>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>List Kelas</h4>
                            <div class="card-header-action">
                                <button class="btn btn-icon icon-left btn-primary" data-toggle="modal"
                                    data-target="#add-modal" data-id="tambah_kelas"><i class="fa fa-plus"
                                        aria-hidden="true"></i> Tambah
                                    Kelas</button>
                                <a class="btn btn-icon btn-primary active search" data-id="cari_kelas">
                                    <i class="fa fa-search" aria-hidden="true"></i> Cari Kelas</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-search mb-3" style="display: none">
                                <form id="search" method="GET" action="{{ route('classrooms.index') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="name">Kelas</label>
                                            <input type="text" name="classroom-keyword" class="form-control"
                                                id="classroom-keyword" placeholder="Nama Kelas" data-id="cari_kelas_input">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit"
                                            data-id="cari_kelas_submit">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('classrooms.index') }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tbody>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Kelas</th>
                                            <th>Semester</th>
                                            <th>Jumlah Mahasiswa</th>
                                            <th>Daftar Mahasiswa</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                        @forelse ($classrooms as $key => $classroom)
                                            <tr>
                                                <td>{{ $classrooms->firstItem() + $key }}</td>
                                                <td data-id="data_kelas_{{ $classrooms->firstItem() + $key }}">
                                                    {{ $classroom->name }}</td>
                                                <td>{{ $classroom->semester }}</td>
                                                <td>{{ $classroom->students_count }}</td>
                                                <td>
                                                    <a href="{{ route('students.index', $classroom->id) }}"
                                                        class="btn btn-link" data-id="lihat_mahasiswa_{{$classroom->id}}"><i
                                                            class="fas fa-users"></i>
                                                        Lihat Mahasiswa</a>
                                                </td>
                                                <td class="text-right">
                                                    <div class="d-flex justify-content-end">
                                                        <button class="btn btn-sm btn-info btn-icon edit-button"
                                                            data-toggle="modal"
                                                            data-target="#edit-modal-{{ $classroom->id }}"
                                                            data-id="edit_kelas_{{ $classroom->id }}"><i
                                                                class="fas fa-edit"></i>
                                                            Edit</button>
                                                        <form action="{{ route('classrooms.destroy', $classroom->id) }}"
                                                            method="POST" class="ml-2"
                                                            id="delete-classroom-{{ $classroom->id }}">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}">
                                                            <button class="btn btn-sm btn-danger btn-icon"
                                                                data-confirm="
                                                                    <i class='fas fa-exclamation-triangle text-danger'></i>
                                                                    Hapus Kelas | Apakah Anda yakin ingin menghapus kelas <strong>{{ $classroom->name }}</strong>?
                                                                    Semua data yang terkait dengan kelas ini akan terhapus juga."
                                                                data-confirm-yes="$('#delete-classroom-{{ $classroom->id }}').submit()"
                                                                data-id="delete_kelas_confirm_{{ $classroom->id }}">
                                                                <i class="fas fa-times"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                @include('classrooms.modal-edit')
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted"
                                                    data-id="data_kelas_kosong">Tidak ada data untuk ditampilkan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $classrooms->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('classrooms.modal-create')
@endsection
@push('customStyle')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush
@push('customScript')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
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
            //letakkan semua modal di body agar tidak berada di belakang backdrop
            $('.modal').appendTo("body")
            //hide error di field ketika tombol close pada modal diklik
            $('.close-modal').click(function() {
                $('input, select').removeClass('is-invalid')
            })
            //tampil modal insert ketika ada validasi error
            @if ($errors->has('insert-invalid-fields') || $errors->has('file-invalid-fields'))
                $('#add-modal').modal('show');
            @endif
            //tampil modal edit ketika ada validasi error
            @if ($errors->has('update-invalid-fields'))
                var id = {{ $errors->first('classroom-id') }}
                $(`#edit-modal-${id}`).modal('show');
            @endif
        });
    </script>
@endpush
