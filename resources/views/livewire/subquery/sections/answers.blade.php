<div class="col-md-5 col-sm-12">
    <div class="card">
        <div class="card-body pt-0">
            <h2 class="section-title">Jawaban</h2>
            <div class="alert alert-light mt-4"><i class="fas fa-info-circle mr-1"></i> Sel atau baris yang
                sudah diberi
                tanda <b>✓ akan tampil di bawah </b></div>
            @if ($correct_log)
                @php
                    $answers = json_decode($correct_log->answer, true);
                    $columns = array_keys($answers['subquery'][0]);
                @endphp
                <div class="table-responsive pt-2">
                    <table class="table table-bordered table-md table-hover"
                        style="width: 100%; line-height: 28px !important;">
                        <tr>
                            @foreach ($columns as $column)
                                <th>{{ $column }}</th>
                            @endforeach
                        </tr>
                        @foreach ($answers['subquery'] as $answer)
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
                <ul class="stepper stepper-vertical pt-0">
                    <!-- First Step -->
                    <li class="{{ $steps[1]['status'] }} {{ $steps[2]['status'] }}">
                        <a href="#!">
                            <span class="circle">1</span>
                            <span class="label">Inner Query</span>
                        </a>

                        @if ($steps[1]['status'] == 'active' || $steps[2]['status'] == 'active')
                            <!-- Section Description -->
                            <div class="step-content" style="width: 95%">
                                <div class="table-responsive table-hover">
                                    <table class="table table-bordered table-md">
                                        <thead>
                                            <tr>
                                                @if ($steps[2]['filled'])
                                                    <th scope="col" class="col-1"></th>
                                                @endif
                                                @forelse ($selectedColumnInnerQuery as $column)
                                                    <th scope="col">
                                                        @if ($steps[2]['aggregation'])
                                                            <input type="hidden" name="">
                                                            <p class="d-inline">
                                                                {{ $steps[2]['aggregationKeyword'] }}{{ count($selectedColumnInnerQuery) == count($inner_query_table[0]['columns']) ? '(*)' : '(' . implode(', ', $selectedColumnInnerQuery) . ')' }}
                                                            </p>
                                                            @if ($loop->iteration > 0)
                                                            @break
                                                        @endif
                                                    @else
                                                        <p class="d-inline">{{ $column }}</p>
                                                    @endif
                                                    @if ($active_step == 1)
                                                        <a class="d-inline p-0 cursor-pointer"
                                                            wire:click="toggleSelectedColumn('{{ $column }}', '{{ $steps[$active_step]['queryType'] }}', '{{ $active_step }}', 'true')">
                                                            <i class="fas fa-minus-circle cursor-pointer"></i>
                                                        </a>
                                                    @endif
                                                </th>
                                            @empty
                                                <th scope="col" class="text-center">⬅️ Isi jawaban</th>
                                            @endforelse
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($active_step == 2)
                                            @forelse ($selectedDataInnerQuery as $row)
                                                <tr>
                                                    @if (!$steps[2]['aggregation'])
                                                        <td class="align-middle">
                                                            @if ($active_step == 2)
                                                                <a class="p-0 cursor-pointer"
                                                                    wire:click="toggleSelectedData('{{ $row }}', '{{ $steps[$active_step]['queryType'] }}', '{{ $active_step }}', 'true')">
                                                                    <i
                                                                        class="fas fa-minus-circle cursor-pointer"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        @foreach ($inner_query_table_filtered as $key => $filtered_data)
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
                                                    <td class="text-center"
                                                        colspan="{{ count($selectedColumnInnerQuery) + 1 }}">
                                                        ⬅️ Isi
                                                        jawaban
                                                    </td>
                                                </tr>
                                            @endforelse
                                            @if ($steps[2]['aggregation'] && $steps[2]['filled'])
                                                @isset($inner_query_aggregate)
                                                    <td>
                                                        <a class="d-inline p-0 cursor-pointer"
                                                            wire:click="resetSelected(2)">
                                                            <i class="fas fa-minus-circle cursor-pointer"></i>
                                                        </a>
                                                    </td>
                                                    <td colspan="{{ count($selectedColumnInnerQuery) + 1 }}">
                                                        {{ $inner_query_aggregate }}
                                                    </td>
                                                @endisset
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </li>
                <!-- Second Step -->
                <li class="{{ $steps[3]['status'] }} {{ $steps[4]['status'] }}">
                    <a href="#!">
                        <span class="circle">2</span>
                        <span class="label">Outer Query</span>
                    </a>

                    @if ($steps[3]['status'] == 'active' || $steps[4]['status'] == 'active')
                        <!-- Section Description -->
                        <div class="step-content" style="width: 95%">
                            <div class="table-responsive table-hover">
                                <table class="table table-bordered table-md">
                                    <thead>
                                        <tr>
                                            @if ($steps[4]['filled'])
                                                <th scope="col" class="col-1"></th>
                                            @endif
                                            @forelse ($selectedColumnOuterQuery as $column)
                                                <th scope="col">
                                                    @if ($steps[4]['aggregation'])
                                                        <input type="hidden" name="">
                                                        <p class="d-inline">
                                                            {{ $steps[4]['aggregationKeyword'] }}{{ count($selectedColumnOuterQuery) == count($outer_query_table[0]['columns']) ? '(*)' : '(' . implode(', ', $selectedColumnOuterQuery) . ')' }}

                                                        </p>
                                                        @if ($loop->iteration > 0)
                                                        @break
                                                    @endif
                                                @else
                                                    <p class="d-inline">{{ $column }}</p>
                                                @endif
                                                @if ($active_step == 3)
                                                    <a class="d-inline p-0 cursor-pointer"
                                                        wire:click="toggleSelectedColumn('{{ $column }}', '{{ $steps[$active_step]['queryType'] }}', '{{ $active_step }}', 'true')">
                                                        <i class="fas fa-minus-circle cursor-pointer"></i>
                                                    </a>
                                                @endif
                                            </th>
                                        @empty
                                            <th scope="col" class="text-center">⬅️ Isi jawaban</th>
                                        @endforelse
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($active_step == 4)
                                        @forelse ($selectedDataOuterQuery as $row)
                                            <tr>
                                                @if (!$steps[4]['aggregation'])
                                                    <td class="align-middle">
                                                        @if ($active_step == 4)
                                                            <a class="p-0 cursor-pointer"
                                                                wire:click="toggleSelectedData('{{ $row }}', '{{ $steps[$active_step]['queryType'] }}', '{{ $active_step }}', 'true')">
                                                                <i
                                                                    class="fas fa-minus-circle cursor-pointer"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    @foreach ($outer_query_table_filtered as $key => $filtered_data)
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
                                                <td class="text-center"
                                                    colspan="{{ count($selectedColumnOuterQuery) + 1 }}">
                                                    ⬅️ Isi
                                                    jawaban
                                                </td>
                                            </tr>
                                        @endforelse
                                        @if ($steps[4]['aggregation'] && $steps[4]['filled'])
                                            @isset($outer_query_aggregate)
                                                <td>
                                                    <a class="d-inline p-0 cursor-pointer"
                                                        wire:click="resetSelected(4)">
                                                        <i class="fas fa-minus-circle cursor-pointer"></i>
                                                    </a>
                                                </td>
                                                <td colspan="{{ count($selectedColumnOuterQuery) + 1 }}">
                                                    {{ $outer_query_aggregate }}
                                                </td>
                                            @endisset
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </li>
        </ul>
    @endif
    @if (!$correct_log)
        <div class="d-flex justify-content-between">
            <button class="btn btn-link" {{ $active_step == 1 ? 'disabled' : '' }} wire:click="previousStep"><i
                    class="fas fa-chevron-left"></i> Sebelumnya</button>
            <div class="row mt-1">
                <div class="col-md-12 text-right">
                    <button class="btn btn-flat" wire:click="resetSelected({{ $active_step }})">Reset</button>
                    <button class="btn btn-primary"
                        wire:click="{{ $active_step == 4 ? 'showConfirm' : 'nextStep' }}"
                        {{ $steps[$active_step]['filled'] && ($active_step >= 1 && $active_step <= 4) ? '' : 'disabled' }}>{{ $active_step == 4 ? 'Submit' : 'Lanjut' }}</button>
                </div>
            </div>
        </div>
    @endif
</div>
</div>
