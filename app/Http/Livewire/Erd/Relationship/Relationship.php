<?php

namespace App\Http\Livewire\Erd\Relationship;

use App\Helpers\SQLHelper;
use App\Http\Controllers\Exercises\DatabaseController;
use App\Models\Database;
use App\Models\Student;
use App\Models\LogExercise;
use Livewire\Component;
use Session;
use Auth;
class Relationship extends Component
{
    public $exercise;
    public $schedule;
    public $totalexercise;
    public $questionNumber;
	public function render()
    {
        $done = '0';
        $bypass = '0';
        $exercise = json_decode(json_encode($this->exercise),true);
        $exercise = json_decode($exercise['answer'],true);
       // $exercise = json_decode($exercise,true);
        $questionNumberMust = null;
        //dd($exercise);
        $xPostion = 20;
        $attributesY = 1;
        $entityY = 1;
        $relationY = 1;
        $entityX = $xPostion + 150;
        $relationX = $entityX + 150 * 5;
        foreach ($exercise['cells'] as $key => $value) 
        {
            // if($value['type'] == 'link')
            // {
            //     unset($exercise['cells'][$key]);
            // }
            if($value['type'] == 'link')
            {
                unset($exercise['cells'][$key]);
            }

            if($value['type'] == 'standard.Ellipse')
            {
                 $attributesY += 60;
                 $exercise['cells'][$key]['position']['x'] = $xPostion;
                 $exercise['cells'][$key]['position']['y'] = $attributesY; 
            }

            if($value['type'] == 'standard.Rectangle')
            {
                 $entityY += 60;
                 $exercise['cells'][$key]['position']['x'] = $entityX;
                 $exercise['cells'][$key]['position']['y'] = $entityY; 
            }

            if($value['type'] == 'erd.Relationship')
            {
                 $relationY += 120;
                 $exercise['cells'][$key]['position']['x'] = $relationX;
                 $exercise['cells'][$key]['position']['y'] = $relationY; 
            }
        }
        $fixJson =  json_encode($exercise);
        $fixJsonDef = $fixJson;
        $student = Student::where('user_id',Auth::user()->id)->first();

        $checkProviousLog = LogExercise::where('student_id',$student->id)
                            ->where('schedule_id',$this->schedule->id)
                            ->orderBy('created_at','DESC')
                            ->first();
        if($checkProviousLog)
        {
            if($checkProviousLog->status == 'incorrect')
            {
                if($checkProviousLog->exercise_id != $this->exercise->id)
                {
                    $questionNumber = $this->questionNumber;
                    $questionNumber = 1;
                    $fixJson = $checkProviousLog->answer;
                    $bypass = '1';
                    $questionNumberMust =  $questionNumber;
                    return view('livewire.erd.relationship',compact('fixJson','done','bypass','questionNumberMust','fixJsonDef'));
                }
            }
        }
        $checkLog = LogExercise::where('student_id',$student->id)
                    ->where('exercise_id',$this->exercise->id)
                    ->where('schedule_id',$this->schedule->id)
                    ->orderBy('created_at','DESC')
                    ->first();
        if($checkLog)
        {
            //dd($checkLog);
            $fixJson = $checkLog->answer;
            if($checkLog->status == 'correct')
            {
                $done = '1';
            }
        }
        //dd($done);
         return view('livewire.erd.relationship',compact('fixJson','done','bypass','questionNumberMust','fixJsonDef'));
    }

    //fungsi yang lain ada di App\Http\Controllers\Exercises\Erd\RelationshipController


}