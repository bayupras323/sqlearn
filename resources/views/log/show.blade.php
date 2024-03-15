@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Logs Analytics</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Log Mahasiswa</a></div>
                <div class="breadcrumb-item">Log</div>

            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Logs Kelas</h2>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Log</h4>
                        </div>
                        <div class="card-body">
                            <div class="card-body">
                                <canvas id="logsChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tr>
                                        <th style="width: 2% !important;">#</th>
                                        <th style="width: 24% !important;">Nama</th>
                                        <th style="width: 14% !important;">Status Correct</th>
                                        <th style="width: 14% !important;">Status Incorrect</th>
                                    </tr>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @forelse ($usersData as $userData)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $userData['name'] }}</td>
                                            <td>{{ $userData['correct'] }}</td>
                                            <td>{{ $userData['incorrect'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted" data-id=data_nilai_kosong>
                                                Tidak ada data nilai untuk ditampilkan</td>
                                        </tr>
                                    @endforelse
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

@push('customScript')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rawData = @json($processedData);
            const data = {
                labels: Object.keys(rawData).filter(key => key !==
                    'exercise_most_errors'
                ), // Mengambil ID exercise sebagai label, kecuali 'exercise_most_errors'
                datasets: [{
                    label: 'Total Steps',
                    data: Object.values(rawData).map(item => item.total_steps || 0),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: 'Errors',
                    data: Object.values(rawData).map(item => item.errors || 0),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Attempts',
                    data: Object.values(rawData).map(item => item.attempts || 0),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            };

            const ctx = document.getElementById('logsChart').getContext('2d');
            const logsChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush

@push('customScript')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('errorsChart').getContext('2d');
        const errorsChart = new Chart(ctx, {
            type: 'bar', // Jenis grafik, bisa diubah sesuai kebutuhan
            data: {
                labels: @json($exerciseIds), // Label soal
                datasets: [{
                    label: 'Total Errors',
                    data: @json($totalErrors), // Data jumlah kesalahan
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
