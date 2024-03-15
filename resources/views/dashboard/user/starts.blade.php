@extends('dashboard.user.layouts.app')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Mulai {{ $tipe }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('dashboard.user') }}">Beranda</a></div>
                <div class="breadcrumb-item">Mulai {{ $tipe }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Mulai {{ $tipe }}</h2>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Mulai {{ $tipe }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-md">
                                    <tbody>
                                        <tr>
                                            <th>Nama Paket :</th>
                                            <td>{{ $schedule->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tipe :</th>
                                            <td>{{ $tipe }}</td>
                                        </tr>
                                        <tr>
                                            <th>Waktu Pengerjaan:</th>
                                            <td>60 Menit</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Soal:</th>
                                            <td>{{ $exerciseNum }} Soal</td>
                                        </tr>
                                        <tr>
                                            <th>Cara pengerjaan :</th>
                                            <td>Bacalah soal dengan seksama, ikuti perintah pengerjaan yang ada dan pilihlah
                                                jawaban yang menurut Anda benar!</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <div class="d-flex justify-content-center mt-2">
                            <a href="{{ route('dashboard.user') }}" class="btn btn-sm btn-light btn-icon mr-2">
                                Kembali
                            </a>
                            <button data-toggle="modal" data-target="#confirmStart" class="btn btn-sm btn-success btn-icon">
                                Mulai {{ $tipe }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="confirmStart" tabindex="-1" aria-labelledby="confirmStartLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize" id="confirmStartLabel">Mulai {{ $tipe }}?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Pastikan Anda sudah siap untuk mulai mengerjakan {{ $tipe }} ini
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="{{ route('dashboard.user.exercise', $schedule->id) }}" class="btn btn-success">Mulai {{ $tipe }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
