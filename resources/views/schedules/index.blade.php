@extends('layouts.app')

@section('content')
<!-- Main Content -->
<section class="section">
    <div class="section-header">
        <h1>Manajemen Jadwal</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Manajemen Jadwal</a></div>
            <div class="breadcrumb-item">Data Jadwal</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">Manajemen Jadwal</h2>
        <div class="row">
            <div class="col-12">
                @include('layouts.alert')
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>List Jadwal</h4>
                        <div class="card-header-action">
                            <button class="btn btn-icon icon-left btn-primary" data-toggle="modal" data-target="#add-modal" data-id="tambah_jadwal"><i class="fa fa-plus" aria-hidden="true"></i> Tambah
                                Jadwal</button>
                            <a class="btn btn-icon btn-primary active search" data-id="cari_jadwal">
                                <i class="fa fa-search" aria-hidden="true"></i> Cari Jadwal</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="show-search mb-3" style="display: none">
                            <form id="search" method="GET" action="{{ route('schedules.index') }}">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="name">Jadwal</label>
                                        <input type="text" name="schedule-keyword" class="form-control" id="schedule-keyword" placeholder="Cari Jadwal" data-id="cari_jadwal_input">
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-primary mr-1" type="submit" data-id="cari_jadwal_submit">Submit</button>
                                    <a class="btn btn-secondary" href="{{ route('schedules.index') }}">Reset</a>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-md">
                                <tbody>
                                    <tr>
                                        <th style="width: 2% !important;">#</th>
                                        <th style="width: 22% !important;">Nama</th>
                                        <th style="width: 6% !important;">Tipe</th>
                                        <th style="width: 16% !important;">Waktu</th>
                                        <th style="width: 15% !important;">Nama Paket</th>
                                        <th style="width: 12% !important;">Kelas</th>
                                        <th style="width: 12% !important;">Status</th>
                                        <th style="width: 15% !important;" class="text-right" >Action</th>
                                    </tr>
                                    @forelse($schedules as $key => $schedule)
                                        <tr>
                                            <td style="width: 2% !important;" data-id="schedule_{{ $schedules->firstItem() + $key }}">{{ $schedules->firstItem() + $key }}</td>
                                            <td style="width: 22% !important;" class="col-md-2">
                                                {{ $schedule->name }}
                                            </td>
                                            <td style="width: 6% !important;" class="text-capitalize">
                                                {{ $schedule->type == 'exam' ? 'Ujian' : 'Latihan' }}
                                            </td>
                                            <td style="width: 16% !important;" class="col-md-2">
                                                {{ \Carbon\Carbon::parse($schedule->start_date)->locale('id')->translatedFormat('D, d M Y H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($schedule->end_date)->locale('id')->translatedFormat('D, d M Y H:i') }}
                                            </td>
                                            <td style="width: 15% !important;">{{ $schedule->package->name }}</td>
                                            <td style="width: 12% !important;">
                                                <button class="btn btn-link" data-toggle="modal" data-target="#preview-modal-{{ $schedule->id }}" data-id="detail_jadwal_{{ $schedules->firstItem() + $key }}">
                                                    <i class="fas fa-chalkboard-teacher"></i> Lihat Kelas
                                                </button>
                                            </td>
                                            <td style="width: 12% !important;">
                                                @if ( Carbon\Carbon::parse($schedule->end_date)->isPast())
                                                    <span class="badge badge-success">Selesai</span>
                                                @elseif ( Carbon\Carbon::parse($schedule->start_date)->isPast())
                                                    <span class="badge badge-info">Sedang berjalan</span>
                                                @else
                                                    <span class="badge badge-light">Belum mulai</span>
                                                @endif
                                            </td>
                                            <td style="width: 15% !important;" class="text-right">
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-sm btn-info btn-icon ml-2"
                                                            data-toggle="modal" data-target="#edit-modal-{{ $schedule->id }}"
                                                            data-id="edit_jadwal_{{ $schedule->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" class="ml-2" id="delete-schedule-{{ $schedule->id }}">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button class="btn btn-sm btn-danger btn-icon"
                                                                data-confirm="
                                                                    <i class='fas fa-exclamation-triangle text-danger'></i>
                                                                    Hapus Jadwal? | Apakah Anda yakin ingin menghapus jadwal <strong>{{ $schedule->name }}</strong>?
                                                                    Semua data (termasuk nilai dan riwayat pekerjaan mahasiswa)
                                                                    yang terkait dengan jadwal ini akan terhapus juga."
                                                                data-confirm-yes="$('#delete-schedule-{{ $schedule->id }}').submit()"
                                                                data-id="delete_jadwal_{{ $schedules->firstItem() + $key }}">
                                                            <i class="fas fa-times"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            @include('schedules.modal-preview')
                                            @include('schedules.modal-edit')
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted" data-id="data_jadwal_kosong">Tidak ada data untuk ditampilkan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $schedules->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('schedules.modal-create')

@endsection

@push('customStyle')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@push('customScript')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.search').click(function(event) {
                event.stopPropagation();
                $(".show-search").slideToggle("fast");
                $(".show-import").hide();
            });

            //letakkan semua modal di body agar tidak berada di belakang backdrop
            $('.modal').appendTo("body")

            //hide error di field ketika tombol close pada modal diklik
            $('.close-modal').click(function() {
                $('input, select').removeClass('is-invalid')
            });

            $('.select2').select2({
                closeOnSelect: false,
                placeholder: "Pilih Kelas"
            })

            @if($errors->has('insert-invalid-fields'))
                $('#add-modal').modal('show');
            @endif

            @if($errors->has('update-invalid-fields'))
                var id = {{$errors-> first('schedule-id')}}
                $(`#edit-modal-${id}`).modal('show');
            @endif
        });

    </script>
@endpush
