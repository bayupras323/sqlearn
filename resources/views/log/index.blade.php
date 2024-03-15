@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <section class="section">
        <div class="section-header">
            <h1>Logs Analytics</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Log Mahasiswa</a></div>
                <div class="breadcrumb-item">Daftar Log</div>

            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Daftar Log</h2>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Daftar Log dari Jadwal</h4>
                            <div class="card-header-action">
                                <a class="btn btn-icon btn-primary active search" data-id="cari_latihan">
                                    <i class="fa fa-search" aria-hidden="true"></i> Cari Jadwal</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="show-search mb-3" style="display: none">
                                <form id="search" method="GET" action="{{ route('scores.index') }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="name">Nama Jadwal</label>
                                            <input type="text" id="name" name="schedule-keyword"
                                                data-id="cari_latihan_select" class="form-control"
                                                value="{{ old('schedule-keyword') }}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="classroom">Kelas</label>
                                            <select name="classroom-keyword" id="classroom" class="form-control select2"
                                                data-id="cari_kelas_select">
                                                <option value="" data-id="opsi_kelas_0">Semua Kelas</option>
                                                @foreach ($all_classrooms as $key => $classroom)
                                                    <option value="{{ $classroom->id }}"
                                                        {{ old('classroom-keyword') == $classroom->id ? 'selected' : '' }}
                                                        {{ request('classroom-keyword') == $classroom->id ? 'selected' : '' }}
                                                        data-id="opsi_kelas_{{ $key + 1 }}">
                                                        {{ $classroom->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-primary mr-1" type="submit"
                                            data-id="submit_pencarian">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('scores.index') }}">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tr>
                                        <th style="width: 2% !important;">#</th>
                                        <th style="width: 24% !important;">Nama Jadwal</th>
                                        <th style="width: 14% !important;">Kelas</th>
                                        <th style="width: 6% !important;">Tipe</th>
                                        <th style="width: 14% !important;">Waktu </th>
                                        <th style="width: 12% !important;">Status</th>
                                        <th style="width: 12% !important;">Nilai</th>
                                    </tr>
                                    @forelse ($schedules as $key => $schedule)
                                        @php
                                            $all_students = 0;
                                        @endphp
                                        @foreach ($schedule->classrooms as $classroom)
                                            <tr>
                                                <td style="width: 2% !important;">{{ $schedules->firstItem() + $key }}</td>
                                                <td style="width: 24% !important;">
                                                    {{ $schedule->name }}
                                                </td>
                                                <td style="width: 14% !important;" class="badges">
                                                    {{ $classroom->name }}
                                                </td>
                                                <td style="width: 6% !important;" class="text-capitalize">
                                                    {{ $schedule->type == 'exam' ? 'Ujian' : 'Latihan' }}
                                                </td>
                                                <td style="width: 14% !important;" class="col-md-2">
                                                    {{ Carbon\Carbon::parse($schedule->start_date)->locale('id')->translatedFormat('d M Y H:i') }}
                                                    - <br>
                                                    {{ Carbon\Carbon::parse($schedule->end_date)->locale('id')->translatedFormat('d M Y H:i') }}
                                                </td>
                                                <td style="width: 12% !important;">
                                                    @if (Carbon\Carbon::parse($schedule->end_date)->isPast())
                                                        <span class="badge badge-success">Selesai</span>
                                                    @elseif (Carbon\Carbon::parse($schedule->start_date)->isPast())
                                                        <span class="badge badge-info">Sedang berjalan</span>
                                                    @endif
                                                </td>
                                                <td style="width: 12% !important;">
                                                    <a href="{{ route('logs.show', $classroom->id) }}"
                                                        class="btn btn-link">
                                                        <i class="fas fa-list-ol"></i> Lihat Logs
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted" data-id=data_nilai_kosong>
                                                Tidak ada data nilai untuk ditampilkan</td>
                                        </tr>
                                    @endforelse
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
@endsection

@push('customStyle')
    <link rel="stylesheet" href="/assets/css/select2.min.css">
@endpush

@push('customScript')
    <script src="/assets/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.search').click(function(event) {
                event.stopPropagation();
                $(".show-search").slideToggle("fast");
                $(".show-import").hide();
            });
        });
    </script>
@endpush
