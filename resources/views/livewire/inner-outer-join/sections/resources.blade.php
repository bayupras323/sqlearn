<div class="col-md-7 sm-12">
    <div class="card overflow-auto" style="max-height: 70vh; min-height: 60vh">
        <div class="card-body pt-0">
            <h2 class="section-title">Resources</h2>
            <div class="alert alert-light">
                <i class="fas fa-info-circle mr-1"></i>
                Beri tanda <b>✓</b> pada sel tabel yang sesuai untuk dimasukan ke Tabel Jawaban
            </div>
            @if ($correct_log)
                <div id="accordion" class="pt-2">
                    @foreach($tables as $index => $table)
                        <div class="accordion">
                            <div class="accordion-header" role="button" data-toggle="collapse"
                                 data-target="#panel-body-2-{{ $table['name'] }}">
                                <h4>Tabel {{ $table['name'] }}</h4>
                            </div>
                            <div class="accordion-body collapse" id="panel-body-2-{{ $table['name'] }}"
                                 data-parent="#accordion">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm table-hover">
                                        <tr>
                                            @foreach($table['columns'] as $key => $column)
                                                <th>{{ $column['name'] }}</th>
                                            @endforeach
                                        </tr>
                                        @foreach($table['data'] as $key => $data)
                                            @php
                                                $data = (array) $data;
                                            @endphp
                                            <tr>
                                                @foreach($table['columns'] as $column)
                                                    <td>{!! $data[$column['name']] ?? '<span class="font-italic">NULL</span>'  !!}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <ul class="stepper stepper-vertical pt-0">
                    <!-- First Step -->
                    <li class="{{ $steps[1]['status'] }}">
                        <!--Section Title -->
                        <a href="#">
                            <span class="circle">1</span>
                            <span class="label">Pilih Kolom Tabel</span>
                        </a>
                        @if ($steps[1]['status'] == 'active')
                            <!-- Section Description -->
                            <div class="step-content pt-0 pb-0" style="width: 95%">
                                <div id="accordion">
                                    @foreach($tables as $index => $table)
                                        <div class="accordion">
                                            <div class="accordion-header" role="button" data-toggle="collapse"
                                                 data-target="#panel-body-1-{{ $table['name'] }}"
                                                {{ $activeAccordion == '1-' . $table['name'] ? 'aria-expanded=true' : '' }}>
                                                <h4>Tabel {{ $table['name'] }}</h4>
                                            </div>
                                            <div class="accordion-body collapse {{ $activeAccordion == '1-' . $table['name'] ? 'show' : '' }}" id="panel-body-1-{{ $table['name'] }}"
                                                 data-parent="#accordion">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm select-column">
                                                        <tr>
                                                            @foreach($table['columns'] as $key => $column)
                                                                <th>
                                                                    <div class="custom-checkbox custom-control">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input"
                                                                               id="checkbox-column-{{ $table['name'] . '-' . $key }}"
                                                                               wire:key="{{ $index . '-' . $key }}"
                                                                               wire:model="selectedColumn"
                                                                               wire:change="toggleSelectedColumn($event.target.value, 1)"
                                                                               wire:click="$set('activeAccordion', '1-{{ $table['name'] }}')"
                                                                               value="{{ $column['name'] }}">
                                                                        <label for="checkbox-column-{{ $table['name'] . '-' . $key }}"
                                                                               class="custom-control-label">{{ $column['name'] }}</label>
                                                                    </div>
                                                                </th>
                                                            @endforeach
                                                        </tr>
                                                        @foreach($table['data'] as $key => $data)
                                                            @php
                                                                $data = (array) $data;
                                                            @endphp
                                                            <tr>
                                                                @foreach($table['columns'] as $column)
                                                                    <td>{!! $data[$column['name']] ?? '<span class="font-italic">NULL</span>' !!}</td>
                                                                @endforeach
                                                            </tr>
                                                            @if($key == 1)
                                                                <tr>
                                                                    <td colspan="{{ count($table['columns']) }}">
                                                                        ...
                                                                    </td>
                                                                </tr>
                                                                @break
                                                            @endif
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
                        @if ($steps[2]['status'] == 'active')
                            <!-- Section Description -->
                            <div class="step-content pt-0 pb-0" style="width: 95%">
                                <div id="accordion">
                                    @foreach($tables as $index => $table)
                                        <div class="accordion">
                                            <div class="accordion-header" role="button" data-toggle="collapse"
                                                 data-target="#panel-body-2-{{ $table['name'] }}"
                                                {{ $activeAccordion == '2-' . $table['name'] ? 'aria-expanded=true' : '' }}>
                                                <h4>Tabel {{ $table['name'] }}</h4>
                                            </div>
                                            <div class="accordion-body collapse {{ $activeAccordion == '2-' . $table['name'] ? 'show' : '' }}" id="panel-body-2-{{ $table['name'] }}"
                                                 data-parent="#accordion">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm table-hover">
                                                        <tr>
                                                            <th></th>
                                                            @foreach($table['columns'] as $key => $column)
                                                                <th>{{ $column['name'] }}</th>
                                                            @endforeach
                                                        </tr>
                                                        @foreach($table['data'] as $key => $data)
                                                            @php
                                                                $data = (array) $data;
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <div class="custom-checkbox custom-control">
                                                                        <input type="checkbox"
                                                                               class="custom-control-input"
                                                                               id="checkbox-data-{{ $index . '-' . $key }}"
                                                                               wire:key="{{ $index . '-' . $key }}"
                                                                               wire:model="pairedData"
                                                                               wire:change="toggleSelectedData($event.target.value, 2)"
                                                                               wire:click="$set('activeAccordion', '2-{{ $table['name'] }}')"
                                                                               value="{{ $index . '-' . $key }}">
                                                                        <label for="checkbox-data-{{ $index . '-' . $key }}"
                                                                               class="custom-control-label"></label>
                                                                    </div>
                                                                </td>
                                                                @foreach($table['columns'] as $column)
                                                                    <td>{!! $data[$column['name']] ?? '<span class="font-italic">NULL</span>' !!}</td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </li>
                </ul>
            @endif
        </div>
    </div>
</div>
