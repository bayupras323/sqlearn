<div>
    @if ($this->currentPage > $correct_log_count + 1)
        @php
            $correct_log_count++;
            return redirect("exercise/$schedule->id?question=$correct_log_count");
        @endphp
    @endif
    <div class="row mt-1">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body pt-0 question" wire:ignore>
                    <h2 class="section-title">Pertanyaan</h2>
                    <p> {!! $exercise->question !!} </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-1">
        @include('livewire.subquery.sections.resources')
        @include('livewire.subquery.sections.answers')
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

            setTimeout(() => {
                window.location.href = event.detail.nextQuestionUrl
            }, 1500);
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
