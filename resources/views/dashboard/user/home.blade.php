@extends('dashboard.user.layouts.app')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Beranda</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">Beranda</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">
            @if(date('H') < 11)
                Selamat pagi,
            @elseif(date('H') < 15)
                Selamat siang,
            @elseif(date('H') < 18)
                Selamat sore,
            @else
                Selamat malam,
            @endif
            {{ Auth::user()->name }}!
        </h2>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Jadwal Latihan</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-md">
                                <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Jadwal</th>
                                        <th>Tipe</th>
                                        <th>Waktu </th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                        <th>Nilai</th>
                                    </tr>
                                    @if ($schedules->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted" data-id="data_jadwal_kosong">Tidak ada data untuk ditampilkan</td>
                                        </tr>
                                    @else
                                        @foreach($schedules as $key => $schedule)
                                            <tr>
                                                <td data-id="schedule_{{ $schedule->id }}">{{ $schedules->firstItem() + $key }}</td>
                                                <td>{{ $schedule->name }}</td>
                                                <td class="text-capitalize">{{ $schedule->type == 'exam' ? 'Ujian' : 'Latihan' }}</td>
                                                <td>
                                                    @if (Carbon\Carbon::parse($schedule->start_date)->isPast() && Carbon\Carbon::parse($schedule->end_date)->isFuture())
                                                        {{ now()->diff(Carbon\Carbon::parse($schedule->end_date))->format("%a hari, %h jam, %i menit") }} sebelum deadline
                                                    @else
                                                        {{ Carbon\Carbon::parse($schedule->start_date)->format('d F Y H:i')}} - {{ Carbon\Carbon::parse($schedule->end_date)->format('d F Y H:i') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (Carbon\Carbon::parse($schedule->end_date)->isPast() && $schedule->scores->isNotEmpty())
                                                        <span class="badge badge-light">Selesai</span>
                                                    @elseif (Carbon\Carbon::parse($schedule->end_date)->isPast())
                                                        <span class="badge badge-danger">Overdue</span>
                                                    @elseif (Carbon\Carbon::parse($schedule->start_date)->isPast())
                                                        <span class="badge badge-success">Berjalan</span>
                                                    @else
                                                        <span class="badge badge-light">Terjadwal</span>
                                                    @endif
                                                </td>
                                                @if (Carbon\Carbon::parse($schedule->start_date)->isPast() && Carbon\Carbon::parse($schedule->end_date)->isFuture())
                                                    <td>
                                                        @if($schedule->scores->isNotEmpty() && $schedule->scores[0]->score == $schedule->package->total_exercises)
                                                            <a href="#" onclick="return alert('on development')" class="btn btn-sm btn-primary btn-icon ml-2">Review</a>
                                                        @else
                                                            @if($schedule->log_exercises->isNotEmpty())
                                                                @php $lastExercise = count($schedule->log_exercises) + 1 @endphp
                                                                <a href="{{ url("exercise/$schedule->id?question=$lastExercise") }}" class="btn btn-sm btn-info btn-icon ml-2">Lanjutkan</a>
                                                            @else
                                                                <a href="{{ route('dashboard.user.start', $schedule->id)}}" class="btn btn-sm btn-success btn-icon ml-2">Kerjakan</a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($schedule->scores->isNotEmpty())
                                                            @foreach($schedule->scores as $score)
                                                                @if ($loop->last)
                                                                    @if($schedule->scores[0]->score == $schedule->package->total_exercises)
                                                                        {{ round($score->score / $schedule->package->total_exercises * 100, 2) }}
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>
                                                        @if($schedule->scores->isNotEmpty())
                                                            <a href="#" onclick="return alert('on development')" class="btn btn-sm btn-primary btn-icon ml-2">Review</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($schedule->scores->isNotEmpty())
                                                            @foreach($schedule->scores as $score)
                                                                @if ($loop->last)
                                                                    {{ round($score->score / $schedule->package->total_exercises * 100, 2) }}
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            0
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
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
@endsection
