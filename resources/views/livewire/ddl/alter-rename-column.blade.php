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
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="alert alert-light fs-6" role="alert">
                        <small>
                            <i class="fa fa-exclamation-circle mr-1"></i>
                            Pilih nama tabel. Kemudian pilih nama kolom yang akan di ubah dan ketikkan nama kolom baru, lalu klik Submit.
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
                                        @if(isset($previews['tableName'][$i]))
                                        <td class="col-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault{{$i}}" wire:click="setAnswerTable({{$i}})">
                                                <label class="form-check-label" for="flexRadioDefault{{$i}}">
                                                    {{ $previews['tableName'][$i] }}
                                                </label>
                                            </div>
                                        </td>
                                        @else
                                        <td></td>
                                        @endif
                                        <td>
                                            <a class="text-dark" data-toggle="collapse" href="#collapseExample-{{$i}}" role="button" aria-expanded="false" aria-controls="collapseExample-{{$i}}" wire:click="collapseShow({{$i}})">
                                                <div class="d-flex">
                                                    {{ isset($previews['columnName'][$i]) ? $previews['columnName'][$i] : '' }}
                                                </div>
                                            </a>
                                            <div class="collapse {{$show[$i] ?? ''}}" id="collapseExample-{{$i}}">
                                                <div>
                                                    <form wire:submit.prevent="addAnswer({{$i}})">
                                                        <input type="hidden" class="form-control" name="old_Name.{{ $i }}" wire:model="old_Name.{{ $i }}" :key="old_Name.{{ $i }}">
                                                        <div class="mt-2">
                                                            <input type="text" class="form-control" name="new_name.{{ $i }}" wire:model="new_name.{{ $i }}" :key="new_name.{{ $i }}" placeholder="Nama kolom baru">
                                                        </div>
                                                        <div class="text-right mt-2">
                                                            <button type="submit" class="btn btn-success" wire:click="collapseShow({{$i}})">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        </tr>
                                        @endfor
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="alert alert-light fs-6" role="alert">
                        <small>
                            <i class="fa fa-exclamation-circle mr-1"></i>
                            Nama tabel dan kolom yang dipilih dan diubah akan tampil pada tabel di bawah ini.
                        </small>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Tabel Jawaban Benar</h4>
                        </div>
                        <div class="card-body">
                            <div class="section-title mt-0">
                                Tabel {{ $answerTable }}
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tr>
                                        <th class="col-1"></th>
                                        <th class="col-1"></th>
                                        <th>Nama Kolom Lama</th>
                                        <th>Nama Kolom Baru</th>
                                    </tr>
                                    <tbody wire:sortable="updateAnswerOrder" style="border-top: none">
                                        @foreach($userAnswer as $key => $column)
                                        <tr wire:sortable.item="{{ $key }}">
                                            <td style="border: none;">
                                                <i class="fas fa-grip-vertical" style="cursor: move !important;" wire:sortable.handle></i>
                                            </td>
                                            <td class="align-middle prevent-drag">
                                                <a class="p-0 cursor-pointer" wire:click="deleteAnswer({{$key}})">
                                                    <i class="fas fa-minus-circle cursor-pointer"></i>
                                                </a>
                                            </td>
                                            @foreach($column as $col)
                                            <td class="prevent-drag">{{ $col}}</td>
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