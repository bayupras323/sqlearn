<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Source extends Component
{
    public $list = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    public $clickedItem = [];

    public function render()
    {
        return view('livewire.source');
    }

    public function addToDestination($key)
    {
        //push item to clickeditem array
        array_push($this->clickedItem, $this->list[$key]);
        //pop item from list array by index
        unset($this->list[$key]);
    }

    public function resetItem()
    {
        $this->list = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $this->clickedItem = [];
    }
}
