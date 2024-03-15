@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Manajemen Paket Soal</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Manajemen Paket Soal</a></div>
                <div class="breadcrumb-item">Data Paket Soal</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Manajemen Paket Soal</h2>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>List Paket Soal</h4>
                            <div class="card-header-action">
                                <button class="btn btn-icon icon-left btn-primary" data-toggle="modal"
                                    data-target="#add-modal" data-id="tambah_paket">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    Tambah Paket
                                </button>
                                <a class="btn btn-icon btn-primary active search" data-id="cari_paket">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                    Cari Paket
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-search mb-3" style="display: none">
                                <form id="search" method="GET" action="{{ route('packages.index') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="name">Nama Paket</label>
                                            <input type="text" name="paket-keyword" class="form-control"
                                                id="paket-keyword" placeholder="Nama Paket" data-id="cari_paket_input">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit" data-id="cari_paket_submit">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('packages.index') }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Paket</th>
                                        <th>Topik</th>
                                        <th>Pembuat</th>
                                        <th>Daftar Soal</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                    @forelse ($packages as $key => $package)
                                        <tr>
                                            <td>{{ $packages->firstItem() + $key }}</td>
                                            <td data-id="package_name_{{ $packages->firstItem() + $key }}">{{ $package->name }}</td>
                                            <td>{{ $package->topic->name }}</td>
                                            <td>{{ $package->user->name }}</td>
                                            <td>
                                                <a href="{{ route('packages.show', $package->id ) }}"
                                                    class="btn btn-sm btn-link preview-button">
                                                    <i class="fas fa-list"></i>
                                                    Lihat Soal
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-sm btn-info btn-icon edit-button ml-2"
                                                        data-toggle="modal" data-target="#edit-modal-{{ $package->id }}"
                                                        data-id="edit_modal_{{ $package->id }}">
                                                        <i class="fas fa-edit"></i>
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('packages.destroy', $package->id) }}"
                                                        method="POST" class="ml-2" id="delete-package-{{ $package->id }}">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button class="btn btn-sm btn-danger btn-icon"
                                                            data-confirm="
                                                                <i class='fas fa-exclamation-triangle text-danger'></i>
                                                                Hapus Paket Soal | Apakah Anda yakin ingin menghapus paket <strong>{{ $package->name }}</strong>?
                                                                Semua data yang terkait dengan paket ini akan terhapus juga."
                                                            data-confirm-yes="$('#delete-package-{{ $package->id }}').submit()"
                                                            data-id="delete_paket_{{ $packages->firstItem() + $key }}">
                                                            <i class="fas fa-times"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            @include('packages.modal-edit')
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted" data-id="data_paket_kosong">
                                                Tidak ada data untuk ditampilkan
                                            </td>
                                        </tr>
                                    @endforelse
                                </table>
                                <div class="d-flex justify-content-center">
                                    {{ $packages->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('packages.modal-create')
@endsection

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@push('customScript')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        // Select 2 Topic
        $('#topic_id').select2({
            placeholder: 'Pilih Topik'
        });

        $(document).ready(function() {
            $('.search').click(function(event) {
                event.stopPropagation();
                $(".show-search").slideToggle("fast");
            });
        });
        $(document).ready(function() {
            //letakkan semua modal di body agar tidak berada di belakang backdrop
            $('.modal').appendTo("body")
            //hide error di field ketika tombol close pada modal diklik
            $('.close-modal').click(function() {
                $('input, select').removeClass('is-invalid')
            });
            //tampil modal insert ketika ada validasi error
            @if ($errors->has('insert-invalid-fields') || $errors->has('file-invalid-fields'))
                $('#add-modal').modal('show');
            @endif
            //tampil modal edit ketika ada validasi error
            @if ($errors->has('update-invalid-fields'))
                var id = {{ $errors->first('package-id') }}
                $(`#edit-modal-${id}`).modal('show');
            @endif
        });
    </script>
@endpush
