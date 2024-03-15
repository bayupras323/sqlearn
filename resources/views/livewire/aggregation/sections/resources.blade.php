<div class="col-md-7 sm-12">
    <div class="card overflow-auto" style="max-height: 70vh; min-height: 70vh">
        <div class="card-body pt-0">
            <h2 class="section-title">Resources</h2>
            <div class="alert alert-light">
                <i class="fas fa-info-circle mr-1"></i>
                Beri tanda <b>✓</b> pada sel tabel yang sesuai untuk dimasukan ke Tabel Jawaban
            </div>
            <ul class="stepper stepper-vertical pt-0">
                <!-- First Step -->
                <li class="{{ $steps[1]['status'] }}">
                    <!--Section Title -->
                    <a href="#">
                        <span class="circle">1</span>
                        <span class="label">Pilih Kolom Tabel</span>
                    </a>
                    @if($steps[1]['status'] == 'active')
                        <!-- Section Description -->
                        <div class="step-content" style="width: 95%">
                            <div id="accordion" class="accordion">
                                <div class="accordion-header" role="button" data-toggle="collapse"
                                     data-target="#panel-body-1" aria-expanded="true">
                                    <h4>Tabel {{ $query_table_name }}</h4>
                                </div>
                                <div class="accordion-body collapse show" id="panel-body-1"
                                     data-parent="#accordion">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-md">
                                            {{-- check apakah null atau ngga --}}
                                            @isset($query_table)
                                                <tr>
                                                    @if (in_array($steps[1]['aggregationKeyword'], ['', 'COUNT']))
                                                    <th scope="col" class="col-1">
                                                        <div class="custom-checkbox custom-control">
                                                            <input type="checkbox" data-checkboxes="mygroup"
                                                                data-checkbox-role="dad"
                                                                class="custom-control-input" id="checkbox-all"
                                                                wire:model="steps.1.checkedAll"
                                                                wire:change="toggleSelectAllColumn(1)">
                                                            <label for="checkbox-all"
                                                                class="custom-control-label">&nbsp;</label>
                                                        </div>
                                                    </th>
                                                @endif
                                                    @foreach ($query_table[0]['columns'] as $key => $column)
                                                        <th>
                                                            <div class="custom-checkbox custom-control">
                                                                <input type="checkbox" class="custom-control-input"
                                                                       id="checkbox-column-{{ $key }}"
                                                                       wire:key="{{ $key }}"
                                                                       wire:model="selectedColumnQuery.{{ $key }}"
                                                                       wire:change="toggleSelectedColumn($event.target.value, 1)"
                                                                       value="{{ $column['name'] }}"
                                                                    {{ $steps[1]['aggregation'] && $steps[1]['filled'] && !in_array($steps[1]['aggregationKeyword'], ['COUNT']) && !in_array($column['name'], $selectedColumnQuery) ? 'disabled' : '' }}>
                                                                <label for="checkbox-column-{{ $key }}"
                                                                       class="custom-control-label">{{ $column['name'] }}</label>
                                                            </div>
                                                        </th>
                                                    @endforeach
                                                </tr>
                                                {{-- batasan baris data --}}
                                                @foreach ($query_table[0]['data'] as $key => $all_data)
                                                    @if ($loop->iteration > 2)
                                                        @break
                                                    @endif
                                                    <tr>
                                                        @if (in_array($steps[1]['aggregationKeyword'], ['', 'COUNT']))
                                                        <td></td>
                                                    @endif
                                                        @foreach ($all_data as $key => $data)
                                                            <td>{{ $data }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="{{ count($query_table[0]['columns']) }}">
                                                        ...
                                                    </td>
                                                </tr>
                                            @endisset
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </li>

                <!-- Second Step -->
                <li class="{{ $steps[2]['status'] }}">
                    <!--Section Title -->
                    <a href="#">
                        <span class="circle">2</span>
                        <span class="label">Pilih Data</span>
                    </a>
                    @if($steps[2]['status'] == 'active')
                        <div class="step-content" style="width: 95%">
                            <div id="accordion" class="accordion">
                                <div class="accordion-header" role="button" data-toggle="collapse"
                                     data-target="#panel-body-1" aria-expanded="true">
                                    <h4>Tabel {{ $query_table_name }}</h4>
                                </div>
                                <div class="accordion-body collapse show" id="panel-body-1"
                                     data-parent="#accordion">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-md">
                                            @isset($query_table)
                                                <tr>    
                                                    <th scope="col" class="col-1">                                                       
                                                    @if (in_array($steps[2]['aggregationKeyword'], ['SUM','AVG', 'COUNT']))                                                    
                                                        <div class="custom-checkbox custom-control">
                                                            <input type="checkbox" data-checkboxes="mygroup"
                                                                data-checkbox-role="dad"
                                                                class="custom-control-input" id="checkbox-all"
                                                                wire:model="steps.2.checkedAll"
                                                                wire:change="toggleSelectAllColumn(2)">
                                                            <label for="checkbox-all"
                                                                class="custom-control-label">&nbsp;</label>
                                                        </div>                                                  
                                                @endif
                                                    @foreach ($selectedColumnQueryWithAddition as $column)
                                                        <th>
                                                            {{ $column }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                                @if ($steps[2]['additionColumns'] ? $query_table_filtered_with_addition : $query_table_filtered != null)
                                                    @foreach ($steps[2]['additionColumns'] ? $query_table_filtered_with_addition : $query_table_filtered as $key => $filtered_data)
                                                        <tr>
                                                            <td class="text-center">
                                                                <div class="custom-checkbox custom-control">
                                                                    <input type="checkbox"
                                                                           class="custom-control-input"
                                                                           id="checkbox-data-{{ $key }}"
                                                                           wire:key="{{ $key }}"
                                                                           wire:model="selectedDataQuery.{{ $key }}"
                                                                           wire:change="toggleSelectedData($event.target.value, 2)"
                                                                           value="{{ $key }}"
                                                                        {{ $steps[2]['aggregation'] && $steps[2]['filled'] && !in_array($steps[2]['aggregationKeyword'], ['SUM','AVG','COUNT']) && in_array($steps[2]['aggregationKeyword'], ['MAX', 'MIN']) && !in_array($key, $selectedDataQuery) ? 'disabled' : '' }}>
                                                                    <label for="checkbox-data-{{ $key }}"
                                                                           class="custom-control-label"></label>
                                                                </div>
                                                            </td>
                                                            @foreach ($filtered_data as $key => $data)
                                                                <td>
                                                                    {{ $data }}
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endisset
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</div>
