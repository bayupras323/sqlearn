<div class="modal fade" id="preview-modal-{{ $schedule->id }}" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="edit-modal-{{ $schedule->id }}label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preview-modal-{{ $schedule->id }}-label">Daftar Kelas</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" data-id="detail_kelas">
                @if($schedule->classrooms == null)
                Tidak ada data untuk ditampilkan
                @else
                @foreach($schedule->classrooms as $class)
                <button class="btn btn-link" >
                    <i class="fas fa-chalkboard-teacher"></i> {{$class->name}}
                </button>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>