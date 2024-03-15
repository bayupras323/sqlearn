<div>
    <h2 class="section-title">Soal Latihan DDL</h2>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Pertanyaan</h4>
                </div>
                <div class="card-body">
                    <p style="font-size: 1rem; line-height: 1.5">{!! $exercise->question !!}</p>
                    <small class="font-italic">Tips: Perhatikan <strong>foreign key</strong>(jika ada), <strong>urutan kolom</strong>, dan <strong>nama tabel!</strong></small>
                </div>
            </div>
            <div class="row">
                <div class="col-5">
                    <div class="alert alert-light fs-6" role="alert">
                        <small>
                            <i class="fa fa-exclamation-circle mr-1"></i>
                            Pilih nama tabel. Kemudian pilih nama kolom dan tentukan attributnya, lalu klik Submit.
                        </small>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Tabel Skema</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tr>
                                        <th>Nama Tabel</th>
                                        <th>Nama Kolom</th>
                                    </tr>
                                    @for($i = 0; $i < $numRows; $i++) <tr>
                                        <!-- Table name -->
                                        @if(isset($tableName[$i]))
                                        <td class="col-5"">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault{{$i}}" wire:click="setAnswerTable({{$i}})">
                                                <label class="form-check-label" for="flexRadioDefault{{$i}}">
                                                    {{ $tableName[$i] }}
                                                </label>
                                            </div>
                                        </td>
                                        @else
                                        <td></td>
                                        @endif

                                        <!-- Column name -->
                                        @if(isset($columnName[$i]))
                                        <td>
                                            <a class="text-dark" data-toggle="collapse" href="#collapseExample-{{$i}}" role="button" aria-expanded="false" aria-controls="collapseExample-{{$i}}" wire:click="collapseShow({{$i}})">
                                                <div class="d-flex">
                                                    {{ $columnName[$i] }}
                                                </div>
                                            </a>
                                            <div class="collapse {{$show[$i] ?? ''}}" id="collapseExample-{{$i}}">
                                                <div>
                                                    <form wire:submit.prevent="addAnswer({{$i}})">
                                                        <input type="hidden" name="colName" id="colName.{{ $i }}" wire:model="colName.{{ $i }}" :key="colName.{{ $i }}">
                                                        <div class="mt-2">
                                                            <select name="colType" class="form-control form-control-sm" data-id="colType.{{ $i }}" wire:model="colType.{{ $i }}" :key="colType.{{ $i }}">
                                                                <option type="hidden">Tipe</option>
                                                                @foreach ($dataType as $tipe)
                                                                <option value="{{ $tipe }}">
                                                                    {{ $tipe }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <select name="size" class="form-control form-control-sm" data-id="size.{{ $i }}" wire:model="colSize.{{ $i }}" :key="colSize.{{ $i }}">
                                                                <option type="hidden">Size</option>
                                                                @foreach ($columnSize as $size)
                                                                <option value="{{ $size }}">
                                                                    {{ $size }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <select name="columnKey" class="form-control form-control-sm" data-id="columnKey.{{ $i }}" wire:model="colKey.{{ $i }}" :key="colKey.{{ $i }}">
                                                                <option type="hidden">Key</option>
                                                                @foreach ($columnKey as $cKey)
                                                                <option value="{{ $cKey }}">
                                                                    {{ $cKey }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <select name="columnNullability" class="form-control form-control-sm" data-id="columnNullability.{{ $i }}" wire:model="colNullability" :key="colNullability.{{ $i }}">
                                                                <option type="hidden">Nullability</option>
                                                                @foreach ($columnNullability as $nullability)
                                                                <option value="{{ $nullability }}">
                                                                    {{ $nullability }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <select name="columnDefault" class="form-control form-control-sm" data-id="columnDefault.{{ $i }}" wire:model="colDefault" :key="colDefault.{{ $i }}">
                                                                <option type="hidden">Default</option>
                                                                @foreach ($columnDefault as $default)
                                                                <option value="{{ $default }}">
                                                                    {{ $default }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mt-2">
                                                            <select name="columnExtra" class="form-control form-control-sm" data-id="columnExtra.{{ $i }}" wire:model="colExtra" :key="colExtra.{{ $i }}">
                                                                <option type="hidden">Extra</option>
                                                                @foreach ($columnExtra as $extra)
                                                                <option value="{{ $extra }}">
                                                                    {{ $extra }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="text-right mt-2">
                                                            <button type="submit" class="btn btn-success" wire:click="collapseShow({{$i}})">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        @else
                                        <td></td>
                                        @endif
                                        </tr>
                                        @endfor
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <div class="alert alert-light" role="alert">
                        <small>
                            <i class="fa fa-exclamation-circle mr-1"></i>
                            Nama tabel dan kolom yang sudah dipilih akan tampil pada tabel di bawah ini.
                        </small>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Tabel Jawaban</h4>
                        </div>
                        <div class="card-body">
                            <div class="section-title mt-0">
                                Tabel {{ $answerTable }}
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Nama Kolom</th>
                                        <th>Tipe Data</th>
                                        <th>Size</th>
                                        <th>Key</th>
                                        <th>Nullability</th>
                                        <th>Default</th>
                                        <th>Extra</th>
                                    </tr>
                                    <tbody wire:sortable="updateAnswerOrder" style="border-top: none">
                                        @foreach($userAnswer as $key => $answer)
                                        <tr wire:sortable.item="{{ $key }}">
                                            <td style="border: none;">
                                                <i class="fas fa-grip-vertical" style="cursor: move !important;" wire:sortable.handle></i>
                                            </td>
                                            <td class="align-middle prevent-drag">
                                                <a class="p-0 cursor-pointer" wire:click="deleteAnswer({{$key}})">
                                                    <i class="fas fa-minus-circle cursor-pointer"></i>
                                                </a>
                                            </td>
                                            @foreach($answer as $data)
                                            <td class="prevent-drag">{{ $data }}</td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-flat" wire:click="resetAnswer">Reset</button>
                                    <button class="btn btn-primary" wire:click="submitAnswer">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('customScript')
<script src="{{ asset('assets/js/livewire-sortable.js') }}"></script>
<script>
    window.addEventListener('showErrorToast', event => {
        toastr.error(event.detail.message, null, {
            closeButton: true,
            timeOut: 5000,
            positionClass: 'toast-bottom-right',
        });
    });

    window.addEventListener('showSuccessToast', event => {
        toastr.success(event.detail.message, null, {
            closeButton: true,
            timeOut: 5000,
            positionClass: 'toast-bottom-right',
        });
    });

    window.addEventListener('showDoneAlert', event => {
        Swal.fire({
            title: 'Latihan Selesai!',
            text: event.detail.message,
            icon: 'success',
            confirmButtonText: 'Kembali ke Halaman Utama'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/home';
            }
        });
    });

    window.addEventListener('swal:confirm', event => {
        Swal.fire({
            title: event.detail.title,
            icon: event.detail.icon,
            confirmButtonColor: event.detail.confirmButtonColor,
            showDenyButton: event.detail.showDenyButton,
            confirmButtonText: event.detail.confirmButtonText,
            denyButtonText: event.detail.denyButtonText,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('submitAnswer', true);
            } else {
                @this.call('submitAnswer', false);
            }
        })
    });
</script>
@endpush