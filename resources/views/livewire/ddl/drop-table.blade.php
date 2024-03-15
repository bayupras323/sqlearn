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
                            Pilih nama tabel yang akan dihapus sesuai query di atas, lalu klik Drop.
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
                                        <th colspan="2">Nama Tabel</th>
                                    </tr>
                                    @foreach($preview as $key => $p)
                                    <tr>
                                        <td>
                                            {{ $p }}
                                        </td>
                                        <td>
                                            <div class="text-right"><button class="btn btn-sm btn-icon" wire:click="addAnswer({{$key}})"><i class="fas fa-trash mr-2"></i>Drop</button></div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="alert alert-light fs-6" role="alert">
                        <small>
                            <i class="fa fa-exclamation-circle mr-1"></i>
                            Nama tabel yang dihapus akan muncul di bawah ini.
                        </small>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Tabel Jawaban Benar</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tr>
                                        <th class="col-1"></th>
                                        <th>Nama Tabel Terhapus</th>
                                    </tr>
                                    @foreach($userAnswer as $key => $answer)
                                    <tr>
                                        <td>
                                            <a wire:click="deleteAnswer({{$key}})">
                                                <i class="fas fa-minus-circle cursor-pointer"></i>
                                            </a>
                                        </td>
                                        <td>{{$answer}}</td>
                                    </tr>
                                    @endforeach
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