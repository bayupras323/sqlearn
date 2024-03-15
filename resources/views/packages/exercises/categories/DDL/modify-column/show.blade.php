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
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tabel Skema</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-md">
                                        <tr>
                                            <th>Nama Tabel</th>
                                            <th>Nama Kolom</th>
                                            <th>Tipe Data</th>
                                            <th>Size</th>
                                        </tr>
                                        @for($i = 0; $i < $numRows; $i++)
                                            <tr>
                                                <td>{{ $previews['tableName'][$i] ?? '' }}</td>
                                                <td>{{ $previews['columnName'][$i] ?? '' }}</td>
                                                <td>{{ $previews['dataType'][$i] ?? ''}}</td>
                                                <td>{{ $previews['columnSize'][$i] ?? '' }}</td>
                                            </tr>
                                        @endfor
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h4>Tabel Jawaban Benar</h4>
                            </div>
                            <div class="card-body">
                                <div class="section-title mt-0">
                                    Tabel {{ $answerTable }}
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-md">
                                        <tr>
                                            <th>Nama Kolom</th>
                                            <th>Tipe Data</th>
                                            <th>Size</th>
                                        </tr>
                                        @foreach($answers as $column)
                                            <tr>
                                                <td>{{ $column['name'] }}</td>
                                                <td>{{ $column['type'] }}</td>
                                                <td>{{ $column['length'] }}</td>
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
