<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Squad extends Component
{
    public $squadList = [
        ['id' => 1, 'name' => 'Squad 1', 'position' => 'GK'],
        ['id' => 2, 'name' => 'Squad 2', 'position' => 'DF'],
        ['id' => 3, 'name' => 'Squad 3', 'position' => 'DF'],
        ['id' => 4, 'name' => 'Squad 4', 'position' => 'DF'],
        ['id' => 5, 'name' => 'Squad 5', 'position' => 'DF'],
        ['id' => 6, 'name' => 'Squad 6', 'position' => 'MF'],
        ['id' => 7, 'name' => 'Squad 7', 'position' => 'MF'],
        ['id' => 8, 'name' => 'Squad 8', 'position' => 'MF'],
        ['id' => 9, 'name' => 'Squad 9', 'position' => 'MF'],
        ['id' => 10, 'name' => 'Squad 10', 'position' => 'CF'],
        ['id' => 11, 'name' => 'Squad 10', 'position' => 'CF'],
    ];

    public $selectedColumn = [];
    public $selectedRow = [];

    public function render()
    {
        return view('livewire.squad');
    }

    public function addSelectedColumn($columnName)
    {
        //add columName to selectedColumn array
        array_push($this->selectedColumn, $columnName);
        $this->selectedColumn = array_unique($this->selectedColumn);
    }

    public function addSelectedRow($indexKey)
    {
        # code...
        array_push($this->selectedRow, $this->squadList[$indexKey]);
    }

    public function resetSelected()
    {
        # code...
        $this->selectedColumn = [];
        $this->selectedRow = [];
    }
}
