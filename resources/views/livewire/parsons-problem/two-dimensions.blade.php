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
                    {!! $exercise->question !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @include('livewire.parsons-problem.2d.question')
    </div>
</div>

@push('customScript')
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
    <script>
        // log parsons problem
        var activityLogs = [];

        function logDragActivity(event, ui) {
            var draggedItem = ui.item.text();
            activityLogs.push('Dragged item from: ' + draggedItem);
        }

        function logReorderActivity(event, ui) {
            var movedItem = ui.item.text();
            activityLogs.push('Moved item: ' + movedItem);
        }

        $('#sortable').on('sortreceive', logDragActivity);
        $('#sortable').on('sortupdate', logReorderActivity);

        $('#sortableTrash').on('sortreceive', function(event, ui) {
            var draggedItem = ui.item.text();
            activityLogs.push('Dragged item: ' + draggedItem);
        });
        // emit ketika saveButton di klik
        $('#saveButton').on('click', function() {
            Livewire.emit('storeActivityLogs', activityLogs);
        });
    </script>
@endpush
