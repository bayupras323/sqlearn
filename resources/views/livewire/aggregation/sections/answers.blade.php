<div class="col-md-5 col-sm-12">
    <div class="card overflow-auto" style="max-height: 70vh; min-height: 70vh">
        <div class="card-body pt-0">
            <h2 class="section-title">Jawaban</h2>
            <div class="alert alert-light">
                <i class="fas fa-info-circle mr-1"></i>
                Sel yang sudah diberi tanda <b>✓ akan tampil di bawah </b>
            </div>
            @if ($correct_log)
                @php
                    $answers = json_decode($correct_log->answer, true);
                    $columns = array_keys($answers[0]);
                @endphp
                <div class="table-responsive pt-2">
                    <table class="table table-bordered table-md table-hover"
                        style="width: 100%; line-height: 28px !important;">
                        <tr>
                            @foreach ($columns as $column)
                                <th>{{ $column }}</th>
                            @endforeach
                        </tr>
                        @foreach ($answers as $answer)
                            <tr>
                                @foreach ($columns as $column)
                                    <td>{!! $answer[$column] ?? '<span class="font-italic">NULL</span>' !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                    <div class="d-flex justify-content-end">
                        <div class="row mt-1">
                            <div class="col-md-12 text-right">
                                <a href="{{ $nextQuestionUrl }}" class="btn btn-primary">
                                    Soal Berikutnya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-responsive py-4">
                    <table class="table table-bordered table-md table-hover"
                        style="width: 100%; line-height: 28px !important;">
                        <tr>
                            @if ($steps[2]['filled'])
                                <th class="col-1"></th>
                            @endif
                            @forelse ($selectedColumnQuery as $column)
                                <th>
                                    @if ($steps[2]['aggregation'])
                                        {{ $steps[2]['aggregationKeyword'] }}{{ count($selectedColumnQuery) > 1 ? '(*)' : '(' . $column . ')' }}
                                        @if ($loop->iteration > 0)
                                        @break
                                    @endif
                                @else
                                    {{ $column }}
                                @endif
                                @if ($active_step == 1)
                                    <a class="d-inline p-0 cursor-pointer"
                                        wire:click="toggleSelectedColumn('{{ $column }}','{{ $active_step }}', 'true')">
                                        <i class="fas fa-minus-circle cursor-pointer"></i>
                                    </a>
                                @endif
                            </th>
                        @empty
                            <th class="text-center">⬅️ Isi jawaban</th>
                        @endforelse
                    </tr>
                    @if ($active_step == 2)
                        @forelse ($selectedDataQuery as $row)
                            <tr>
                                @if (!$steps[2]['aggregation'])
                                    <td class="align-middle">
                                        @if ($active_step == 2)
                                            <a class="p-0 cursor-pointer"
                                                wire:click="toggleSelectedData('{{ $row }}', '{{ $steps[$active_step]['queryType'] }}', '{{ $active_step }}', 'true')">
                                                <i class="fas fa-minus-circle cursor-pointer"></i>
                                            </a>
                                        @endif
                                    </td>
                                    @foreach ($query_table_filtered as $key => $filtered_data)
                                        @if ($key == $row)
                                            @foreach ($filtered_data as $data)
                                                <td>
                                                    {{ $data }}
                                                </td>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="{{ count($selectedColumnQuery) + 1 }}">
                                    ⬅️ Isi jawaban
                                </td>
                            </tr>
                        @endforelse
                        @if ($steps[2]['aggregation'] && $steps[2]['filled'])
                            @isset($query_aggregate)
                                <td>
                                    <a class="d-inline p-0 cursor-pointer" wire:click="resetSelected(2)">
                                        <i class="fas fa-minus-circle cursor-pointer"></i>
                                    </a>
                                </td>
                                <td colspan="{{ count($selectedColumnQuery) + 1 }}">
                                    {{ $query_aggregate }}
                                </td>
                            @endisset
                        @endif
                    @endif
                </table>
            </div>
        @endif
        @if (!$correct_log)
            <div class="d-flex justify-content-between">
                <button class="btn btn-link" {{ $active_step == 1 ? 'disabled' : '' }} wire:click="previousStep"><i
                        class="fas fa-chevron-left"></i> Sebelumnya</button>
                <div class="row mt-1">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-flat" wire:click="resetSelected({{ $active_step }})">Reset</button>
                        <button class="btn btn-primary"
                            wire:click="{{ $active_step == 2 ? 'showConfirm' : 'nextStep' }}"
                            {{ $steps[$active_step]['filled'] && ($active_step >= 1 && $active_step <= 2) ? '' : 'disabled' }}>{{ $active_step == 2 ? 'Submit' : 'Lanjut' }}</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
</div>
