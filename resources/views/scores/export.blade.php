<p>Nama Latihan: {{ $schedule->name }}</p>
<p>Kelas: {{ $students->first()->classrooms->name }}</p>
<p>
    Dari: {{ \Carbon\Carbon::parse($schedule->start_date)->locale('id')->translatedFormat('l, j F Y H:i') }}
</p>
<p>
    Sampai: {{ \Carbon\Carbon::parse($schedule->end_date)->locale('id')->translatedFormat('l, j F Y H:i') }}
</p>

<table>
    <tbody>
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Nilai</th>
        </tr>
        @php
            $all_score = collect();
            $done = collect();
            $undone = collect();
        @endphp
        @forelse ($students as $student_key => $student)
            @forelse ($scores as $key => $score)
                @php
                    $key += $student_key;
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $student->student_id_number }}</td>
                    <td>{{ $student->user->name }}</td>
                    <td>
                        @if ($student->id == $score->students->id)
                            {{ $score->score }}
                            @php
                                $all_score->push($score);
                                $done->push($student);
                            @endphp
                        @else
                            Belum Mengerjakan
                            @php
                                $undone->push($student);
                            @endphp
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td>{{ $student_key + 1 }}</td>
                    <td>{{ $student->student_id_number }}</td>
                    <td>{{ $student->user->name }}</td>
                    <td>
                        Belum Mengerjakan
                        @php
                            $undone->push($student);
                        @endphp
                    </td>
                </tr>
            @endforelse
        @empty
            <tr>
                <td colspan="4">Tidak ada data untuk
                    ditampilkan</td>
            </tr>
        @endforelse

        @php
            $done_percentage = ($done->count() / $students->count()) * 100;
            $undone_percentage = ($undone->count() / $students->count()) * 100;
        @endphp

        <tr>
            <td colspan="3">
                Total Mahasiswa
            </td>
            <td>
                <b> {{ $students->count() }} </b>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                Mengerjakan
            </td>
            <td>
                <b> {{ $done->count() }} ({{ $done_percentage }}%) </b>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                Belum Mengerjakan
            </td>
            <td>
                <b> {{ $undone->count() }} ({{ $undone_percentage }}%) </b>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                Nilai Tertinggi
            </td>
            <td>
                <b> {{ $all_score->max('score') ?? '-' }} </b>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                Nilai Terendah
            </td>
            <td>
                <b> {{ $all_score->min('score') ?? '-' }} </b>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                Rata-rata
            </td>
            <td>
                <b> {{ $all_score->avg('score') ?? '-' }} </b>
            </td>
        </tr>
    </tbody>
</table>
