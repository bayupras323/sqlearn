<div>
    @if ($union == true)
        <livewire:union-cross-join.union-join :exercise="$exercise" :schedule="$schedule" :nextQuestionUrl="$nextQuestionUrl" />
    @else
        <livewire:union-cross-join.cross-join :exercise="$exercise" :schedule="$schedule" :nextQuestionUrl="$nextQuestionUrl" />
    @endif
</div>
