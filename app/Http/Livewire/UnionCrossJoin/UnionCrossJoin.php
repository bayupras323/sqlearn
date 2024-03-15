<?php

namespace App\Http\Livewire\UnionCrossJoin;

use App\Helpers\SQLHelper;
use Livewire\Component;

class UnionCrossJoin extends Component
{
    public $union; // store if it's union or cross join
    public $schedule; // store the schedule object
    public $exercise; // store the exercise object
    public $nextQuestionUrl; // store the next question url

    public function mount($exercise, $schedule, $nextQuestionUrl)
    {
        $this->exercise = $exercise; // return the exercise object
        $this->schedule = $schedule; // return the schedule object
        $this->nextQuestionUrl = $nextQuestionUrl; // return the next question url
        $unionorcross = SQLHelper::findUnionOrCross($this->exercise->answer['queries']); // find if it's union or cross join
        $unionorcross == 'UNION' ? $this->union = true : null; // if it's union set union to true
    }

    public function render()
    {
        return view('livewire.union-cross-join.union-cross-join');
    }
}
