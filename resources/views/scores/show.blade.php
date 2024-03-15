@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $schedule->name }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Nilai Mahasiswa</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('scores.index') }}">Daftar Nilai</a></div>
                <div class="breadcrumb-item">{{ $schedule->name }}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Nilai</h2>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Daftar Nilai</h4>
                            <div class="card-header-action">
                                <a href="#"
                                    class="btn btn-icon btn-primary active search" data-id="export_excel_button">
                                    <i class="fa fa-file-excel" aria-hidden="true"></i> Export Excel </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Tipe</dt>
                                <dd class="col-sm-9">{{ $schedule->type == 'exam' ? 'Ujian' : 'Latihan' }}</dd>

                                <dt class="col-sm-3">Nama Paket</dt>
                                <dd class="col-sm-9">{{ $schedule->package->name }}</dd>

                                <dt class="col-sm-3">Waktu</dt>
                                <dd class="col-sm-9">
                                    {{ \Carbon\Carbon::parse($schedule->start_date)->locale('id')->translatedFormat('d M Y H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($schedule->end_date)->locale('id')->translatedFormat('d M Y H:i') }}
                                </dd>

                                <dt class="col-sm-3 ">Status</dt>
                                <dd class="col-sm-9">
                                    @if (Carbon\Carbon::parse($schedule->end_date)->isPast())
                                        <span class="badge badge-success">Selesai</span>
                                    @elseif (Carbon\Carbon::parse($schedule->start_date)->isPast())
                                        <span class="badge badge-info">Sedang berjalan</span>
                                    @endif</dd>
                            </dl>
                            <div id="accordion">
                                @foreach($schedule->classrooms as $classroom)
                                    <div class="accordion">
                                        <div class="accordion-header collapsed" role="button" data-toggle="collapse" data-target="#panel-body-{{ $classroom->id }}" aria-expanded="false">
                                            <h4>Kelas {{ $classroom->name }}</h4>
                                        </div>
                                        <div class="accordion-body collapse" id="panel-body-{{ $classroom->id }}" data-parent="#accordion" style="">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-md">
                                                    <tbody>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>NIM</th>
                                                            <th>Nama</th>
                                                            <th>Nilai</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                        @php
                                                            $totalMahasiswa = 0;
                                                            $totalMengerjakan = 0;
                                                            $totalBelumMengerjakan = 0;
                                                            $all_scores = collect();
                                                        @endphp
                                                        @forelse ($classroom->students as $key => $student)
                                                            <tr>
                                                                <td>{{ ++$key }}</td>
                                                                <td>{{ $student->student_id_number }}</td>
                                                                <td>{{ $student->user->name }}</td>
                                                                <td>
                                                                    @if($scores->contains('student_id', $student->id))
                                                                        @php
                                                                        $score = $scores->where('student_id', $student->id)->first();
                                                                        $totalMengerjakan++;
                                                                        $all_scores->push($score);
                                                                        @endphp
                                                                        {{ round($score->score / $schedule->package->exercises->count() * 100, 2) }}
                                                                    @else
                                                                        @if(\Carbon\Carbon::now() > $schedule->end_date)
                                                                            0
                                                                        @else
                                                                            Belum Mengerjakan
                                                                        @endif
                                                                        @php $totalBelumMengerjakan++; @endphp
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($scores->contains('student_id', $student->id))
                                                                        <a href={{ route('scores.log', [$schedule->id, $student->id]) }} class="btn btn-primary btn-sm">Review</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center text-muted"
                                                                    data-id="data_nilai_kosong">Tidak ada data untuk ditampilkan</td>
                                                            </tr>
                                                        @endforelse
                                                        @php
                                                            $totalMahasiswa = $classroom->students->count();
                                                        @endphp
                                                        <tr>
                                                            <td colspan="3" class="font-weight-bold">
                                                                Total Mahasiswa
                                                            </td>
                                                            <td class="font-weight-bold" colspan="2">
                                                                {{ $totalMahasiswa }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="font-weight-bold">
                                                                Mengerjakan
                                                            </td>
                                                            <td class="font-weight-bold" colspan="2">
                                                                {{ $totalMengerjakan . ' (' . round($totalMengerjakan / $totalMahasiswa * 100, 2) . '%)' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="font-weight-bold">
                                                                Belum Mengerjakan
                                                            </td>
                                                            <td class="font-weight-bold" colspan="2">
                                                                {{ $totalBelumMengerjakan . ' (' . round($totalBelumMengerjakan / $totalMahasiswa * 100, 2) . '%)'}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="font-weight-bold">
                                                                Nilai Tertinggi
                                                            </td>
                                                            <td class="font-weight-bold" colspan="2">
                                                                {{ $all_scores->max('score') / $schedule->package->exercises->count() * 100 ?? '-' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="font-weight-bold">
                                                                Nilai Terendah
                                                            </td>
                                                            <td class="font-weight-bold" colspan="2">
                                                                {{ $all_scores->min('score') / $schedule->package->exercises->count() * 100 ?? '-' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="font-weight-bold">
                                                                Rata-rata
                                                            </td>
                                                            <td class="font-weight-bold" colspan="2">
                                                                {{ round($all_scores->avg('score') / $schedule->package->exercises->count() * 100, 2) ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
