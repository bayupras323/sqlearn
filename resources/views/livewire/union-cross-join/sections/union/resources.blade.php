<div class="col-md-7 sm-12">
    <div class="card overflow-auto" style="max-height: 65vh;">
        <div class="card-body pt-0">
            <h2 class="section-title">Resources</h2>
            <div class="alert alert-light mt-4"><i class="fas fa-info-circle mr-1"></i> Beri tanda <b>✓</b> pada
                sel atau baris
                tabel yang sesuai untuk dimasukan ke Tabel Jawaban</div>
            <!-- Stepers Wrapper -->
            <ul class="stepper stepper-vertical pt-0">

                <!-- First Step -->
                <li class="{{ $steps[1]['status'] }}">
                    <a href="#!">
                        <span class="circle">1</span>
                        <span class="label">Left Query - Pilih Kolom tabel</span>
                    </a>

                    @if ($steps[1]['status'] == 'active')
                        <!-- Section Description -->
                        <div class="step-content" style="width: 95%">
                            <p><b>{{ $left_query }}</b></p>
                            <div id="accordion" class="accordion">
                                <div class="accordion-header" role="button" data-toggle="collapse"
                                    data-target="#panel-body-1" aria-expanded="true">
                                    <h4>Tabel {{ $left_query_table_name }}</h4>
                                </div>
                                <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                                    <div class="table-responsive table-hover">
                                        <table class="table table-bordered {{ in_array($steps[1]['aggregationKeyword'], ['', 'COUNT']) ? 'table-sticky' : '' }} table-md">
                                            <thead>
                                                @isset($left_query_table)
                                                    <tr>
                                                        @if (in_array($steps[1]['aggregationKeyword'], ['', 'COUNT']))
                                                            <th scope="col" class="col-1">
                                                                <div class="custom-checkbox custom-control">
                                                                    <input type="checkbox" data-checkboxes="mygroup"
                                                                        data-checkbox-role="dad"
                                                                        class="custom-control-input" id="checkbox-all"
                                                                        wire:model.lazy="steps.1.checkedAll"
                                                                        wire:change="toggleSelectAllColumn(1)">
                                                                    <label for="checkbox-all"
                                                                        class="custom-control-label">&nbsp;</label>
                                                                </div>
                                                            </th>
                                                        @endif
                                                        @foreach ($left_query_table[0]['columns'] as $key => $column)
                                                            <th scope="col">
                                                                <div class="custom-checkbox custom-control">
                                                                    <input type="checkbox" data-checkboxes="mygroup"
                                                                        class="custom-control-input"
                                                                        id="checkbox-column-{{ $key }}"
                                                                        wire:key="{{ $key }}"
                                                                        wire:model.lazy="selectedColumnLeftQuery.{{ $key }}"
                                                                        wire:change="toggleSelectedColumn($event.target.value, 'left', 1)"
                                                                        value="{{ $column['name'] }}"
                                                                        {{ $steps[1]['aggregation'] && $steps[3]['aggregationKeyword'] != 'COUNT' && $steps[1]['filled'] && !in_array($column['name'], $selectedColumnLeftQuery) ? 'disabled' : '' }}>
                                                                    <label for="checkbox-column-{{ $key }}"
                                                                        class="custom-control-label">{{ $column['name'] }}</label>
                                                                </div>
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($left_query_table[0]['data'] as $key => $all_data)
                                                        @if ($loop->iteration > 2)
                                                        @break
                                                    @endif
                                                    <tr>
                                                        @if (in_array($steps[1]['aggregationKeyword'], ['', 'COUNT']))
                                                            <td></td>
                                                        @endif
                                                        @foreach ($all_data as $key => $data)
                                                            <td>{{ $data ?? 'NULL' }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="{{ count($left_query_table[0]['columns']) }}">
                                                        ...
                                                    </td>
                                                </tr>
                                            @endisset
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                @endif
            </li>

            <!-- Second Step -->
            <li class="{{ $steps[2]['status'] }}">

                <!--Section Title -->
                <a href="#!">
                    <span class="circle">2</span>
                    <span class="label">Left Query - Pilih Data</span>
                </a>

                @if ($steps[2]['status'] == 'active')
                    <div class="step-content" style="width: 95%">
                        <p><b>{{ $left_query }}</b></p>
                        <div id="accordion" class="accordion">
                            <div class="accordion-header" role="button" data-toggle="collapse"
                                data-target="#panel-body-1" aria-expanded="true">
                                <h4>Tabel {{ $left_query_table_name }}</h4>
                            </div>
                            <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                                <div class="table-responsive table-hover">
                                    <table class="table table-bordered table-sticky table-md">
                                        <thead>
                                            @isset($left_query_table)
                                                <tr>
                                                    <th scope="col" class="col-1">
                                                        @if (!in_array($steps[2]['aggregationKeyword'], ['MAX', 'MIN']))
                                                            <div class="custom-checkbox custom-control">
                                                                <input type="checkbox" data-checkboxes="mygroup"
                                                                    data-checkbox-role="dad"
                                                                    class="custom-control-input" id="checkbox-all"
                                                                    wire:model.lazy="steps.2.checkedAll"
                                                                    wire:change="toggleSelectAllColumn(2)">
                                                                <label for="checkbox-all"
                                                                    class="custom-control-label">&nbsp;</label>
                                                            </div>
                                                        @endif
                                                    </th>
                                                    @foreach ($selectedColumnLeftQueryWithAddition as $column)
                                                        <th scope="col">
                                                            {{ $column }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($steps[2]['additionColumns'] ? $left_query_table_filtered_with_addition : $left_query_table_filtered != null)
                                                    @foreach ($steps[2]['additionColumns'] ? $left_query_table_filtered_with_addition : $left_query_table_filtered as $key => $filtered_data)
                                                        <tr>
                                                            <td class="text-center">
                                                                <div class="custom-checkbox custom-control">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="checkbox-data-{{ $key }}"
                                                                        wire:key="{{ $key }}"
                                                                        wire:model.lazy="selectedDataLeftQuery.{{ $key }}"
                                                                        wire:change="toggleSelectedData($event.target.value, 'left', 2)"
                                                                        value="{{ $key }}"
                                                                        {{ $steps[2]['aggregation'] && $steps[2]['filled'] && in_array($steps[2]['aggregationKeyword'], ['MAX', 'MIN']) && !in_array($key, $selectedDataLeftQuery) ? 'disabled' : '' }}>
                                                                    <label for="checkbox-data-{{ $key }}"
                                                                        class="custom-control-label"></label>
                                                                </div>
                                                            </td>
                                                            @foreach ($filtered_data as $key => $data)
                                                                <td>
                                                                    {{ $data ?? 'NULL' }}
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endisset
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                @endif
            </li>

            <!-- Third Step -->
            <li class="{{ $steps[3]['status'] }}">
                <a href="#!">
                    <span class="circle">3</span>
                    <span class="label">Right Query - Pilih Kolom tabel</span>
                </a>

                @if ($steps[3]['status'] == 'active')
                    <div class="step-content" style="width: 95%">
                        <p><b>{{ $right_query }} </b></p>
                        <div id="accordion" class="accordion">
                            <div class="accordion-header" role="button" data-toggle="collapse"
                                data-target="#panel-body-1" aria-expanded="true">
                                <h4>Tabel {{ $right_query_table_name }}</h4>
                            </div>
                            <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                                <div class="table-responsive table-hover">
                                    <table
                                        class="table table-bordered {{ in_array($steps[3]['aggregationKeyword'], ['', 'COUNT']) ? 'table-sticky' : '' }} table-md">
                                        <thead>
                                            @isset($right_query_table)
                                                <tr>
                                                    @if (in_array($steps[3]['aggregationKeyword'], ['', 'COUNT']))
                                                        <th scope="col" class="col-1">
                                                            <div class="custom-checkbox custom-control">
                                                                <input type="checkbox" data-checkboxes="mygroup"
                                                                    data-checkbox-role="dad"
                                                                    class="custom-control-input" id="checkbox-all"
                                                                    wire:model.lazy="steps.3.checkedAll"
                                                                    wire:change="toggleSelectAllColumn(3)">
                                                                <label for="checkbox-all"
                                                                    class="custom-control-label">&nbsp;</label>
                                                            </div>
                                                        </th>
                                                    @endif
                                                    @foreach ($right_query_table[0]['columns'] as $key => $column)
                                                        <th scope="col">
                                                            <div class="custom-checkbox custom-control">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="checkbox-column-{{ $key }}"
                                                                    wire:key="{{ $key }}"
                                                                    wire:model.lazy="selectedColumnRightQuery.{{ $key }}"
                                                                    wire:change="toggleSelectedColumn($event.target.value, 'right', 3)"
                                                                    value="{{ $column['name'] }}"
                                                                    {{ $steps[3]['aggregation'] && $steps[3]['aggregationKeyword'] != 'COUNT' && $steps[3]['filled'] && !in_array($column['name'], $selectedColumnRightQuery) ? 'disabled' : '' }}>
                                                                <label for="checkbox-column-{{ $key }}"
                                                                    class="custom-control-label">{{ $column['name'] }}</label>
                                                            </div>
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($right_query_table[0]['data'] as $key => $all_data)
                                                    @if ($loop->iteration > 2)
                                                    @break
                                                @endif
                                                <tr>
                                                    @if (in_array($steps[3]['aggregationKeyword'], ['', 'COUNT']))
                                                        <td></td>
                                                    @endif
                                                    @foreach ($all_data as $key => $data)
                                                        <td>{{ $data ?? 'NULL' }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="{{ count($right_query_table[0]['columns']) }}">
                                                    ...
                                                </td>
                                            </tr>
                                        @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            @endif
        </li>

        <!-- Fourth Step -->
        <li class="{{ $steps[4]['status'] }}">
            <a href="#!">
                <span class="circle">4</span>
                <span class="label">Right Query - Pilih Data</span>
            </a>

            @if ($steps[4]['status'] == 'active')
                <div class="step-content" style="width: 95%">
                    <p><b>{{ $right_query }}</b></p>
                    <div id="accordion" class="accordion">
                        <div class="accordion-header" role="button" data-toggle="collapse"
                            data-target="#panel-body-1" aria-expanded="true">
                            <h4>Tabel {{ $right_query_table_name }}</h4>
                        </div>
                        <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                            <div class="table-responsive table-hover">
                                <table class="table table-bordered table-sticky table-md">
                                    <thead>
                                        @isset($right_query_table)
                                            <tr>
                                                <th scope="col" class="col-1">
                                                    @if (!in_array($steps[4]['aggregationKeyword'], ['MAX', 'MIN']))
                                                        <div class="custom-checkbox custom-control">
                                                            <input type="checkbox" data-checkboxes="mygroup"
                                                                data-checkbox-role="dad"
                                                                class="custom-control-input" id="checkbox-all"
                                                                wire:model.lazy="steps.4.checkedAll"
                                                                wire:change="toggleSelectAllColumn(4)">
                                                            <label for="checkbox-all"
                                                                class="custom-control-label">&nbsp;</label>
                                                        </div>
                                                    @endif
                                                </th>
                                                @foreach ($selectedColumnRightQueryWithAddition as $column)
                                                    <th scope="col">
                                                        {{ $column }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($steps[4]['additionColumns'] ? $right_query_table_filtered_with_addition : $right_query_table_filtered != null)
                                                @foreach ($steps[4]['additionColumns'] ? $right_query_table_filtered_with_addition : $right_query_table_filtered as $key => $filtered_data)
                                                    <tr>
                                                        <td class="text-center">
                                                            <div class="custom-checkbox custom-control">
                                                                <input type="checkbox"
                                                                    class="custom-control-input"
                                                                    id="checkbox-data-{{ $key }}"
                                                                    wire:key="{{ $key }}"
                                                                    wire:model.lazy="selectedDataRightQuery.{{ $key }}"
                                                                    wire:change="toggleSelectedData($event.target.value, 'right', 4)"
                                                                    value="{{ $key }}"
                                                                    {{ $steps[4]['aggregation'] && $steps[4]['filled'] && in_array($steps[4]['aggregationKeyword'], ['MAX', 'MIN']) && !in_array($key, $selectedDataRightQuery) ? 'disabled' : '' }}>
                                                                <label for="checkbox-data-{{ $key }}"
                                                                    class="custom-control-label"></label>
                                                            </div>
                                                        </td>
                                                        @foreach ($filtered_data as $key => $data)
                                                            <td>
                                                                {{ $data ?? 'NULL' }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </li>

    </ul>
    <!-- /.Stepers Wrapper -->
</div>
</div>
</div>
