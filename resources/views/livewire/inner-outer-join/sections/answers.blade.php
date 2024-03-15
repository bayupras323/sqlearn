<div class="col-md-5 col-sm-12">
    <div class="card overflow-auto" style="max-height: 70vh; min-height: 60vh">
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
                    <table class="table table-bordered table-md table-hover" style="width: 100%; line-height: 28px !important;">
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
                <div class="table-responsive pt-4">
                    <table class="table table-bordered table-md table-hover" style="width: 100%; line-height: 28px !important;">
                        <tr>
                            @if ($steps[2]['filled'])
                                <th style="border: none"></th>
                                <th></th>
                            @endif
                            @forelse ($selectedColumn as $column)
                                <th>
                                    @if ($active_step == 1)
                                        <a class="py-2 pl-0 pr-2 cursor-pointer d-inline"
                                           wire:click="toggleSelectedColumn('{{ $column }}', '{{ $active_step }}', 'true')">
                                            <i class="fas fa-minus-circle cursor-pointer"></i>
                                        </a>
                                    @endif
                                    {{ $column }}
                                </th>
                            @empty
                                <th class="text-center">⬅️ Isi jawaban</th>
                            @endforelse
                        </tr>
                        @if ($active_step == 2)
                            @isset($selectedDataKey)
                                <tbody wire:sortable="updateAnswerOrder" style="border-top: none">
                                @forelse ($selectedDataKey as $index => $row)
                                    <tr wire:sortable.item="{{ $index }}">
                                        <td style="border: none" class="align-middle">
                                            <i class="fas fa-grip-vertical float-right"
                                               style="cursor: move !important;"
                                               wire:sortable.handle ></i>
                                        </td>
                                        <td class="align-middle prevent-drag">
                                            <a class="p-0 cursor-pointer"
                                               wire:click="removeAnswerData({{ $index }})">
                                                <i class="fas fa-minus-circle cursor-pointer"></i>
                                            </a>
                                        </td>
                                        @foreach ($selectedColumn as $column)
                                            @if (isset($selectedData[$index][$column]))
                                                <td class="prevent-drag">{{ $selectedData[$index][$column] }}</td>
                                            @else
                                                <td class="font-italic pb-1 prevent-drag">
                                                    @if(count($row) == count($tables))
                                                        NULL
                                                    @else
                                                        <p style="cursor: pointer"
                                                           wire:click="setNullData({{ $index }})"
                                                           class="text-primary p-0 mb-0">
                                                            set NULL
                                                        </p>
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <th class="text-center prevent-drag" colspan="{{ count($selectedColumn) + 2 }}">⬅️ Isi jawaban</th>
                                    </tr>
                                @endforelse
                                </tbody>
                            @endisset
                        @endif
                    </table>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-link" {{ $active_step == 1 ? 'disabled' : '' }}
                    wire:click="previousStep"><i class="fas fa-chevron-left"></i> Sebelumnya</button>
                    <div class="row mt-1">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-flat"
                                    wire:click="resetSelected({{ $active_step }})">Reset</button>
                            <button class="btn btn-primary"
                                    wire:click="{{ $active_step == 2 ? 'submitAnswer' : 'nextStep' }}"
                                {{ $steps[$active_step]['filled'] && ($active_step >= 1 && $active_step <= 2) ? '' : 'disabled' }}>
                                {{ $active_step == 2 ? 'Submit' : 'Lanjut' }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
