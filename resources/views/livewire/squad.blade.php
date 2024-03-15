<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>The Query</h4>
            </div>
            <div class="card-body">
                Select id,name from squad list where id = 1;
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Available Squad</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-md">
                        <caption>Table of Available Squad</caption>
                        <tbody>
                            <tr>
                                <th wire:key="id" wire:click="addSelectedColumn('Id')">Id</th>
                                <th wire:key="name" wire:click="addSelectedColumn('Name')">Name</th>
                                <th wire:key="position" wire:click="addSelectedColumn('Position')">Position</th>
                            </tr>
                            @foreach ($squadList as $key => $value)
                                <tr wire:key="available-"{{ $key }}
                                    wire:click="addSelectedRow({{ $key }})">
                                    <td>{{ $value['id'] }}</td>
                                    <td>{{ $value['name'] }}</td>
                                    <td>{{ $value['position'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Choosen Squad</h4>
                <button class="btn btn-danger" wire:click="resetSelected">Reset</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-md">
                        <caption>Table of Available Squad</caption>
                        <thead>
                            <tr>
                                @foreach ($selectedColumn as $column)
                                    <th>
                                        {{ $column }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedRow as $key => $value)
                                <tr>
                                    @if (in_array('Id', $selectedColumn))
                                        <td>{{ $value['id'] }}</td>
                                    @endif
                                    @if (in_array('Name', $selectedColumn))
                                        <td>{{ $value['name'] }}</td>
                                    @endif
                                    @if (in_array('Position', $selectedColumn))
                                        <td>{{ $value['position'] }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
</div>
