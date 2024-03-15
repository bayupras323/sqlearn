<div>
    @if($this->currentPage > $correct_log_count + 1)
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
                    {!! $exercise->question !!}
                    <small class="font-italic">Tips: Perhatikan <strong>urutan kolom</strong> dan <strong>urutan data!</strong></small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @include('livewire.inner-outer-join.sections.resources')
        @include('livewire.inner-outer-join.sections.answers')
    </div>
</div>

@push('customStyle')
    <style>
        .select-column th:hover {
            background-color: rgba(0, 0, 0, .08) !important;
        }
    </style>
@endpush

@push('customScript')
    <script src="{{ asset('assets/js/livewire-sortable.js') }}"></script>
    <script>
        window.addEventListener('showErrorToast', event => {
            Swal.fire({
                title: 'Sorry!',
                text: event.detail.message,
                icon: 'error',
            });
        });

        window.addEventListener('showSuccessToast', event => {
            Swal.fire({
                title: 'Congrats!',
                text: event.detail.message,
                icon: 'success',
                confirmButtonText: 'Lanjut ke soal berikutnya'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ $nextQuestionUrl }}';
                }
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

