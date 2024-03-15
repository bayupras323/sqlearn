<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Available Item</h4>
            </div>
            <div class="card-body">
                @foreach ($list as $key => $item)
                    <a href="#">
                        <div wire:key={{ $key }} class="alert alert-primary" role="alert"
                            wire:click.prevent=addToDestination({{ $key }})>
                            {{ $item }}
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Choosen Item</h4>
                <button class="btn btn-danger" wire:click="resetItem">Reset</button>
            </div>
            <div class="card-body">
                @foreach ($clickedItem as $item)
                    <div class="alert alert-warning" role="alert">
                        {{ $item }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div>
</div>
