@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            {{-- <h1>{{ $schedule->name }} Kelas {{ $students->first()->classrooms->name }}</h1> --}}
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Nilai Mahasiswa</a></div>
                <div class="breadcrumb-item active"><a href="#">Daftar Log</a></div>
                {{-- <div class="breadcrumb-item">{{ $schedule->name }} Kelas {{ $students->first()->classrooms->name }}</div> --}}
            </div>
        </div>
        <div class="section-body">
            {{-- <h2 class="section-title">Nilai Kelas {{ $students->first()->classrooms->name }}</h2> --}}
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Daftar Log</h4>
                            <div class="card-header-action">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tbody>
                                        <tr>
                                            <th>NO</th>
                                            {{-- <th>NIM</th>
                                            <th>Nama</th> --}}
                                            <th>Soal</th>
                                            <th>Status</th>
                                            <th>Keyakinan</th>
                                        </tr>
                                        @php
                                            $i = 1;
                                            $all_score = collect();
                                            $done = collect();
                                            $undone = collect();
                                        @endphp
                                        @forelse ($logs as $log)
                                            {{-- @foreach ($logs as $log) --}}
                                                <tr>
                                                    <td data-id="score_{{ $i }}">{{ $i }}</td>
                                                    {{-- <td>{{ $student->student_id_number }}</td>
                                                    <td>{{ $student->user->name }}</td> --}}
                                                    <td>{{ $log ->exercises->question}}</td>
                                                    <td>{{ $log ->status}}</td>
                                                    <td>{{ $log ->confident}}</td>

                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            {{-- @endforeach --}}
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted"
                                                    data-id="data_nilai_kosong">Tidak ada data untuk ditampilkan</td>
                                            </tr>
                                        @endforelse
{{-- 
                                        @php
                                            $done_percentage = ($done->count() / $students->count()) * 100;
                                            $undone_percentage = ($undone->count() / $students->count()) * 100;
                                        @endphp --}}

                                        {{-- <tr>
                                            <td colspan="3" class="font-weight-bold">
                                                Total Mahasiswa
                                            </td>
                                            <td class="font-weight-bold">
                                                {{ $students->count() }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="font-weight-bold">
                                                Mengerjakan
                                            </td>
                                            <td class="font-weight-bold">
                                                {{ $done->count() }} ({{ $done_percentage }}%)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="font-weight-bold">
                                                Belum Mengerjakan
                                            </td>
                                            <td class="font-weight-bold">
                                                {{ $undone->count() }} ({{ $undone_percentage }}%)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="font-weight-bold">
                                                Nilai Tertinggi
                                            </td>
                                            <td class="font-weight-bold">
                                                {{ $all_score->max('score') ?? '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="font-weight-bold">
                                                Nilai Terendah
                                            </td>
                                            <td class="font-weight-bold">
                                                {{ $all_score->min('score') ?? '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="font-weight-bold">
                                                Rata-rata
                                            </td>
                                            <td class="font-weight-bold">
                                                {{ $all_score->avg('score') ?? '-' }}
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                                <div class="card-header">
                                    <h4>Rangkuman Percobaan</h4>
                                    <div class="card-header-action">
                                    </div>
                                </div>
                                <table class="table table-bordered table-md">
                                    <tbody>
                                        <tr>
                                            <th>No</th>
                                            <th>Soal</th>
                                            <th>Jumlah percobaan</th>
                                        </tr>
                                        @php
                                            $no = 1
                                        @endphp
                                        @forelse ($percobaan as $percobaan)
                                            <tr>
                                                <td data-id="">{{ $no }}</td>
                                                <td>{{ $percobaan ->exercises->question}}</td>
                                                <td>{{ $percobaan ->jumlah}}</td>
                                                {{-- <td>{{ $log ->status}}</td>
                                                <td>{{ $log ->confident}}</td> --}}

                                            </tr>
                                        @php
                                            $no++;
                                        @endphp
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted"
                                                    data-id="data_nilai_kosong">Tidak ada data untuk ditampilkan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
