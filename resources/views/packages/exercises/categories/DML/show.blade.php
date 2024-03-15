@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Preview Latihan Soal</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('packages.index') }}">Manajemen Paket Soal</a></div>
            <div class="breadcrumb-item"><a href="{{ url()->previous() }}">List Latihan</a>
            </div>
            <div class="breadcrumb-item">Preview Latihan Soal</div>
        </div>
    </div>
    <div class="section-body">
        <h2 class="section-title">Manajemen Latihan</h2>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Pertanyaan</h4>
                    </div>
                    <div class="card-body">
                        <p style="font-size: 1rem; line-height: 1.5">{!! $exercise->question !!}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        @foreach($tableData as $key => $table)
                            <div class="card">
                                <div class="card-header">
                                    <h4>Tabel {{ $table['name'] }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                @foreach($table['columns'] as $column)
                                                    <th class="text-lowercase">{{ $column['name'] }}</th>
                                                @endforeach
                                            </tr>
                                            @foreach($table['data'] as $data)
                                                @php
                                                    $data = (array) $data;
                                                @endphp
                                                <tr>
                                                    @foreach($table['columns'] as $column)
                                                        <td>{{ $data[$column['name']] ?? 'NULL' }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tabel Jawaban Benar</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            @foreach($columns as $column)
                                                <th>{{ $column }}</th>
                                            @endforeach
                                        </tr>
                                        @foreach($answer as $a)
                                            <tr>
                                                @foreach($columns as $column)
                                                    <td>{{ $a->$column ?? 'NULL' }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
