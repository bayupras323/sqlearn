<div class="col-md-5 col-sm-12">
    <div class="card">
        <div class="card-body pt-0">
            <h2 class="section-title">Jawaban</h2>
            <div class="alert alert-light mt-4"><i class="fas fa-info-circle mr-1"></i> Sel atau baris yang
                sudah diberi
                tanda <b>✓ akan tampil di bawah </b></div>
            <ul class="stepper stepper-vertical pt-0">
                <!-- First Step -->
                <li class="{{ $steps[1]['status'] }} {{ $steps[2]['status'] }}">
                    <a href="#!">
                        <span class="circle">1</span>
                        <span class="label">Left Query</span>
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
                                            @forelse ($selectedColumnLeftQuery as $column)
                                                <th scope="col">
                                                    @if ($steps[2]['aggregation'])
                                                        <input type="hidden" name="">
                                                        <p class="d-inline">
                                                            {{ $steps[2]['aggregationKeyword'] }}{{ count($selectedColumnLeftQuery) == count($left_query_table[0]['columns']) ? '(*)' : '(' . implode(', ', $selectedColumnLeftQuery) . ')' }}
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
                                        @forelse ($selectedDataLeftQuery as $row)
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
                                                    @foreach ($left_query_table_filtered as $key => $filtered_data)
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
                                                    colspan="{{ count($selectedColumnLeftQuery) + 1 }}">
                                                    ⬅️ Isi
                                                    jawaban
                                                </td>
                                            </tr>
                                        @endforelse
                                        @if ($steps[2]['aggregation'] && $steps[2]['filled'])
                                            @isset($left_query_aggregate)
                                                <td>
                                                    <a class="d-inline p-0 cursor-pointer"
                                                        wire:click="resetSelected(2)">
                                                        <i class="fas fa-minus-circle cursor-pointer"></i>
                                                    </a>
                                                </td>
                                                <td colspan="{{ count($selectedColumnLeftQuery) + 1 }}">
                                                    {{ $left_query_aggregate }}
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
                    <span class="label">Right Query</span>
                </a>

                @if ($steps[3]['status'] == 'active' || $steps[4]['status'] == 'active')
                    <!-- Section Description -->
                    <div class="step-content" style="width: 95%">
                        <div class="table-responsive table-hover">
                            <table class="table table-bordered table-md">
                                <thead>
                                    <tr>
                                        <th scope="col" class="col-1"></th>
                                        @forelse ($selectedColumnUnionQuery as $column)
                                            <th scope="col">
                                                @if ($steps[4]['aggregation'])
                                                    <input type="hidden" name="">
                                                    <p class="d-inline">
                                                        {{ $steps[4]['aggregationKeyword'] }}{{ count($selectedColumnUnionQuery) == count($left_query_table[0]['columns']) ? '(*)' : '(' . implode(', ', $selectedColumnRightQuery) . ')' }}

                                                    </p>
                                                    @if ($loop->iteration > 0)
                                                    @break
                                                @endif
                                            @else
                                                <p class="d-inline">{{ $column }}</p>
                                            @endif
                                        </th>
                                    @empty
                                        <th scope="col" class="text-center">⬅️ Isi jawaban</th>
                                    @endforelse
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <th scope="col" class="col-1"></th>
                                    @forelse ($selectedColumnRightQuery as $column)
                                        <th scope="col">
                                            @if ($steps[4]['aggregation'])
                                                <input type="hidden" name="">
                                                <p class="d-inline">
                                                    {{ $steps[4]['aggregationKeyword'] }}{{ count($selectedColumnRightQuery) == count($right_query_table[0]['columns']) ? '(*)' : '(' . implode(', ', $selectedColumnRightQuery) . ')' }}

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
                                    <th scope="col" colspan="{{ count($selectedColumnUnionQuery) }}"
                                        class="text-center">⬅️ Isi jawaban</th>
                                @endforelse
                            </tr>
                        </thead>
                        @if ($active_step == 4)
                            <tbody>
                                @foreach ($selectedDataLeftQuery as $row)
                                    <tr>
                                        <td></td>
                                        @if (!$steps[2]['aggregation'])
                                            @foreach ($left_query_table_filtered as $key => $filtered_data)
                                                @if ($key == $row)
                                                    @foreach ($filtered_data as $data)
                                                        <td>
                                                            {{ $data ?? 'NULL' }}
                                                        </td>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    </tr>
                                @endforeach
                                @if ($steps[2]['aggregation'] && $steps[2]['filled'])
                                    @isset($left_query_aggregate)
                                        <td colspan="{{ count($selectedColumnLeftQuery) + 1 }}">
                                            {{ $left_query_aggregate }}
                                        </td>
                                    @endisset
                                @endif
                            </tbody>
                        @endif
                        <tbody>
                            @if ($active_step == 4)
                                @forelse ($selectedDataRightQuery as $row)
                                    <tr>
                                        @if (!$steps[4]['aggregation'])
                                            <td class="align-middle">
                                                @if ($active_step == 4)
                                                    <a class="p-0 cursor-pointer"
                                                        wire:click="toggleSelectedData('{{ $row }}', '{{ $steps[$active_step]['queryType'] }}', '{{ $active_step }}', 'true')">
                                                        <i class="fas fa-minus-circle cursor-pointer"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            @foreach ($right_query_table_filtered as $key => $filtered_data)
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
                                            colspan="{{ count($selectedColumnRightQuery) + 1 }}">
                                            ⬅️ Isi
                                            jawaban
                                        </td>
                                    </tr>
                                @endforelse
                                @if ($steps[4]['aggregation'] && $steps[4]['filled'])
                                    @isset($right_query_aggregate)
                                        <td>
                                            <a class="d-inline p-0 cursor-pointer"
                                                wire:click="resetSelected(4)">
                                                <i class="fas fa-minus-circle cursor-pointer"></i>
                                            </a>
                                        </td>
                                        <td colspan="{{ count($selectedColumnRightQuery) + 1 }}">
                                            {{ $right_query_aggregate }}
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
<div class="d-flex justify-content-between">
    <button class="btn btn-link" {{ $active_step == 1 ? 'disabled' : '' }} wire:click="previousStep"><i
            class="fas fa-chevron-left"></i> Sebelumnya</button>
    <div class="row mt-1">
        <div class="col-md-12 text-right">
            <button class="btn btn-flat" wire:click="resetSelected({{ $active_step }})">Reset</button>
            <button class="btn btn-primary"
                wire:click="{{ $active_step == 4 ? 'submitAnswer' : 'nextStep' }}"
                {{ $steps[$active_step]['filled'] && ($active_step >= 1 && $active_step <= 4) ? '' : 'disabled' }}>{{ $active_step == 4 ? 'Submit' : 'Lanjut' }}</button>
        </div>
    </div>
</div>
</div>
</div>