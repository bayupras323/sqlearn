<?php

namespace App\Http\Controllers\Exercises\Erd;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreErdRelationnShip;
use App\Http\Requests\UpdateErdRelationnShip;
use Illuminate\Http\RedirectResponse;
use App\Models\Exercise;
use App\Models\LogExercise;
use App\Models\Score;
use App\Models\Student;
use App\Models\Schedule;
use Auth;
use App\Models\Database;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use DB;
class RelationshipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function searchForKey($keyIndex, $array,$index,$indexDest) 
    {
       if(isset($array[$keyIndex]))
       {
            if(isset($array[$keyIndex][$index]))
            {
                if(isset($array[$keyIndex][$index][$indexDest]))
                {
                    return $array[$keyIndex][$index][$indexDest];
                }
            }
       }
       return null;
    }

    public function searchForId($id, $array,$index) 
    {
       foreach ($array as $key => $val) 
       {
           if(isset($val[$index]))
           {
               if(is_array($val[$index]))
               {    
                 foreach ($val[$index] as $valKey => $valData) 
                 {
                     if($valData === $id)
                     {
                        return $array[$key];
                     }
                 }
               }else
               {
                  if($val[$index] === $id) 
                   {
                       return $array[$key];
                   }
               }
           }
       }
       return null;
    }

    public function searchForIdArray($id, $array,$index) 
    {
       $arr = [];
       foreach ($array as $key => $val) 
       {
           if(isset($val[$index]))
           {
               if(is_array($val[$index]))
               {    
                 foreach ($val[$index] as $valKey => $valData) 
                 {
                     if($valData === $id)
                     {
                        array_push($arr, $key);
                     }
                 }
               }else
               {
                  if($val[$index] === $id) 
                   {
                       array_push($arr, $key);
                   }
               }
           }
       }
       return $arr;
    }

    public function store(StoreErdRelationnShip $request)
    {
        $answer = $request->answer;
        $answer = json_decode($answer,true);

        //manipulasi relasi
        $answer['relationship'] = [];
        $answer['entitiy_attr'] = [];
        $linkArr = [];
        $relationKey = -1;
        $entityKey = -1;
        foreach ($answer['cells'] as $key => $value) 
        {
           if($value['type'] == 'erd.Relationship')
           {
                $relationKey++;
                $label = $value['attrs']['text']['text'];
                $Id = $value['id'];
                $inCardinality = $value['ports']['items'][0]['attrs']['label']['text'];
                $inId = $value['ports']['items'][0]['id'];
                $outCardinality = $value['ports']['items'][1]['attrs']['label']['text'];
                $outId = $value['ports']['items'][1]['id'];
                $inLabel = null;
                $outLabel = null;

                $fixInId = $this->searchForId($inId,$answer['cells'],'target');
                if($fixInId == null)
                {
                    // jika null maka cari targetnya
                    $fixInId = $this->searchForId($inId,$answer['cells'],'source');
                    $fixInId = $fixInId['target']['id'];

                    $inLabel = $this->searchForId($fixInId,$answer['cells'],'id');
                    $inLabel = $inLabel['attrs']['label']['text'];
                }else{
                    //jika tidak null maka cari source nya
                    $fixInId = $fixInId['source']['id'];
                    $inLabel = $this->searchForId($fixInId,$answer['cells'],'id');

                    $inLabel = $inLabel['attrs']['label']['text'];
                }

                $fixOutId = $this->searchForId($outId,$answer['cells'],'target');
                if($fixOutId == null)
                {
                    // jika null maka cari targetnya
                    $fixOutId = $this->searchForId($outId,$answer['cells'],'source');
                    $fixOutId = $fixOutId['target']['id'];

                    $outLabel = $this->searchForId($fixOutId,$answer['cells'],'id');
                    $outLabel = $outLabel['attrs']['label']['text'];
                }else
                {
                    //jika tidak null maka cari source nya
                    $fixOutId = $fixOutId['source']['id'];
                    $outLabel = $this->searchForId($fixOutId,$answer['cells'],'id');
                    $outLabel = $outLabel['attrs']['label']['text'];
                }

                $answer['relationship'][$relationKey]['id'] = $Id;
                $answer['relationship'][$relationKey]['label'] = strtolower($label);
                $answer['relationship'][$relationKey]['in']['id'] = $fixInId; //entity
                $answer['relationship'][$relationKey]['in']['cardinality'] = $inCardinality;
                $answer['relationship'][$relationKey]['in']['label'] = $inLabel; 
                $answer['relationship'][$relationKey]['out']['id'] = $fixOutId; //entity
                $answer['relationship'][$relationKey]['out']['cardinality'] = $outCardinality;
                $answer['relationship'][$relationKey]['out']['label'] = $outLabel; 
           }elseif ($value['type'] == 'standard.Rectangle') 
           {
               $entityKey++;
               $labelAttr = $value['attrs']['label']['text'];
               $idAttr = $value['id'];
               $answer['entitiy_attr'][$idAttr]['label'] = $labelAttr;
           }elseif ($value['type'] == 'link') 
           {
               array_push($linkArr, $value);
           }
        }

        //manipulasi entitas
        foreach ($answer['entitiy_attr'] as $key => $value) 
        {
             $arrFix = [];
             $uniqueValues = array();
             //one
             $arrAttr = $this->searchForIdArray($key,$linkArr,'source');
             if(count($arrAttr) <= 0)
             {
                $arrAttr = $this->searchForIdArray($key,$linkArr,'target');
                if(count($arrAttr) > 0)
                {
                    foreach ($arrAttr as $itemKey => $itemValue) 
                    {
                        $fixniId = $this->searchForKey($itemValue,$linkArr,'source','id');
                        $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                        if(isset($labelFixIni['type']))
                        {
                            if($labelFixIni['type'] == 'standard.Ellipse')
                            {
                                $arrFix[$itemKey]['id'] = $fixniId;
                                $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                            }
                        }
                    }
                }
             }else{
                foreach ($arrAttr as $itemKey => $itemValue) 
                {
                    $fixniId = $this->searchForKey($itemValue,$linkArr,'target','id');
                    $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                    if(isset($labelFixIni['type']))
                    {
                        if($labelFixIni['type'] == 'standard.Ellipse')
                        {
                            $arrFix[$itemKey]['id'] = $fixniId;
                            $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                            array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                        }else{
                            $fixniId = $this->searchForKey($itemValue,$linkArr,'source','id');
                            $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                            if($labelFixIni['type'] == 'standard.Ellipse')
                            {
                                $arrFix[$itemKey]['id'] = $fixniId;
                                $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                            }
                        }
                    }
                }
             }

             //two
             $arrAttr = $this->searchForIdArray($key,$linkArr,'target');
             if(count($arrAttr) <= 0)
             {
                $arrAttr = $this->searchForIdArray($key,$linkArr,'source');
                if(count($arrAttr) > 0)
                {
                    foreach ($arrAttr as $itemKey => $itemValue) 
                    {
                        $fixniId = $this->searchForKey($itemValue,$linkArr,'target','id');
                        $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                        if(isset($labelFixIni['type']))
                        {
                            if($labelFixIni['type'] == 'standard.Ellipse')
                            {
                                if(isset($arrFix[$itemKey]))
                                {
                                    if(!in_array($labelFixIni['attrs']['label']['text'], $uniqueValues))
                                    {
                                        $itemKey += 1;
                                        $arrFix[$itemKey]['id'] = $fixniId;
                                        $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                        array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                    }
                                }else{
                                    $arrFix[$itemKey]['id'] = $fixniId;
                                    $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                    array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                }
                            }
                        }
                    }
                }
             }else{
                foreach ($arrAttr as $itemKey => $itemValue) 
                {
                    $fixniId = $this->searchForKey($itemValue,$linkArr,'source','id');
                    $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                    if(isset($labelFixIni['type']))
                    {
                        if($labelFixIni['type'] == 'standard.Ellipse')
                        {
                            if(isset($arrFix[$itemKey]))
                            {
                                if(!in_array($labelFixIni['attrs']['label']['text'], $uniqueValues))
                                {
                                    $itemKey += 1;
                                    $arrFix[$itemKey]['id'] = $fixniId;
                                    $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                    array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                }
                            }else{
                                $arrFix[$itemKey]['id'] = $fixniId;
                                $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                            }
                        }
                    }
                }
             }
             
             $answer['entitiy_attr'][$key]['attrs'] = $arrFix;
        }

        $answer = json_encode($answer);
        $exercise = new Exercise;
        $exercise->package_id = $request->package_id;
        $exercise->question = $request->question;
        $exercise->answer = $answer;
        $exercise->save();
        $url = url('/').'/packages/'.$request['package_id'].'';
        return response()->json(['message'=>'success','url'=>$url]);
    }

    public function show($exercise)
    {
       if($exercise){
            $data = json_decode(json_encode($exercise),true);
            $data['answer'] = json_decode($exercise->answer,true);
            //dd($data);
            $general = $data['answer'];
            $type = 'erd';
            $typeErd = 'relationship';
            $databaseSelected = Database::find($exercise->database_id);
            $oke = json_decode(json_encode($exercise),true);
            $test = json_decode($oke['answer'],true);
            $gas = $test;

            $xPostion = 20;
            $attributesY = 1;
            $entityY = 1;
            $relationY = 1;
            $entityX = $xPostion + 150;
            $relationX = $entityX + 150 * 5;
           // $pjgHarus = 
            foreach ($gas['cells'] as $key => $value) 
            {
              if($value['type'] == 'link')
              {
                unset($gas['cells'][$key]);
              }

              if($value['type'] == 'standard.Ellipse')
              {
                 $attributesY += 60;
                 $gas['cells'][$key]['position']['x'] = $xPostion;
                 $gas['cells'][$key]['position']['y'] = $attributesY; 
              }

              if($value['type'] == 'standard.Rectangle')
              {
                 $entityY += 60;
                 $gas['cells'][$key]['position']['x'] = $entityX;
                 $gas['cells'][$key]['position']['y'] = $entityY; 
              }

              if($value['type'] == 'erd.Relationship')
              {
                 $relationY += 120;
                 $gas['cells'][$key]['position']['x'] = $relationX;
                 $gas['cells'][$key]['position']['y'] = $relationY; 
              }
            }
            $fixJson =  json_encode($gas);
            //dd($fixJson);
            return view('packages.exercises.categories.ERD.show', 
                  compact('type', 'typeErd','data','general','databaseSelected','exercise','fixJson'));
        }
        return redirect()->back();
    }

    public function edit($exercise)
    {
        if($exercise){
           $data = json_decode(json_encode($exercise),true);
            $data['answer'] = json_decode($exercise->answer,true);
            //dd($data);
            $general = $data['answer'];
            $type = 'erd';
            $typeErd = 'relationship';
            $databaseSelected = Database::find($exercise->database_id);
            $oke = json_decode(json_encode($exercise),true);
            $test = json_decode($oke['answer'],true);
            $gas = $test;
            $fixJson =  json_encode($gas);
            // $exercise = $exercise->id;
            return view('packages.exercises.categories.ERD.edit', 
                  compact('type', 'typeErd','fixJson','exercise'));
        }
        return redirect()->back();
    }

    public function update(UpdateErdRelationnShip $request)
    {
        $answer = $request->answer;
        $answer = json_decode($answer,true);

        //manipulasi relasi
        $answer['relationship'] = [];
        $answer['entitiy_attr'] = [];
        $linkArr = [];
        $relationKey = -1;
        $entityKey = -1;
        foreach ($answer['cells'] as $key => $value) 
        {
           if($value['type'] == 'erd.Relationship')
           {
                $relationKey++;
                $label = $value['attrs']['text']['text'];
                $Id = $value['id'];
                $inCardinality = $value['ports']['items'][0]['attrs']['label']['text'];
                $inId = $value['ports']['items'][0]['id'];
                $outCardinality = $value['ports']['items'][1]['attrs']['label']['text'];
                $outId = $value['ports']['items'][1]['id'];
                $inLabel = null;
                $outLabel = null;

                $fixInId = $this->searchForId($inId,$answer['cells'],'target');
                if($fixInId == null)
                {
                    // jika null maka cari targetnya
                    $fixInId = $this->searchForId($inId,$answer['cells'],'source');
                    $fixInId = $fixInId['target']['id'];

                    $inLabel = $this->searchForId($fixInId,$answer['cells'],'id');
                    $inLabel = $inLabel['attrs']['label']['text'];
                }else{
                    //jika tidak null maka cari source nya
                    $fixInId = $fixInId['source']['id'];
                    $inLabel = $this->searchForId($fixInId,$answer['cells'],'id');

                    $inLabel = $inLabel['attrs']['label']['text'];
                }

                $fixOutId = $this->searchForId($outId,$answer['cells'],'target');
                if($fixOutId == null)
                {
                    // jika null maka cari targetnya
                    $fixOutId = $this->searchForId($outId,$answer['cells'],'source');
                    $fixOutId = $fixOutId['target']['id'];

                    $outLabel = $this->searchForId($fixOutId,$answer['cells'],'id');
                    $outLabel = $outLabel['attrs']['label']['text'];
                }else
                {
                    //jika tidak null maka cari source nya
                    $fixOutId = $fixOutId['source']['id'];
                    $outLabel = $this->searchForId($fixOutId,$answer['cells'],'id');
                    $outLabel = $outLabel['attrs']['label']['text'];
                }

                $answer['relationship'][$relationKey]['id'] = $Id;
                $answer['relationship'][$relationKey]['label'] = strtolower($label);
                $answer['relationship'][$relationKey]['in']['id'] = $fixInId; //entity
                $answer['relationship'][$relationKey]['in']['cardinality'] = $inCardinality;
                $answer['relationship'][$relationKey]['in']['label'] = $inLabel; 
                $answer['relationship'][$relationKey]['out']['id'] = $fixOutId; //entity
                $answer['relationship'][$relationKey]['out']['cardinality'] = $outCardinality;
                $answer['relationship'][$relationKey]['out']['label'] = $outLabel; 
           }elseif ($value['type'] == 'standard.Rectangle') 
           {
               $entityKey++;
               $labelAttr = $value['attrs']['label']['text'];
               $idAttr = $value['id'];
               $answer['entitiy_attr'][$idAttr]['label'] = $labelAttr;
           }elseif ($value['type'] == 'link') 
           {
               array_push($linkArr, $value);
           }
        }

        //manipulasi entitas
        foreach ($answer['entitiy_attr'] as $key => $value) 
        {
             $arrFix = [];
             $uniqueValues = array();
             //one
             $arrAttr = $this->searchForIdArray($key,$linkArr,'source');
             if(count($arrAttr) <= 0)
             {
                $arrAttr = $this->searchForIdArray($key,$linkArr,'target');
                if(count($arrAttr) > 0)
                {
                    foreach ($arrAttr as $itemKey => $itemValue) 
                    {
                        $fixniId = $this->searchForKey($itemValue,$linkArr,'source','id');
                        $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                        if(isset($labelFixIni['type']))
                        {
                            if($labelFixIni['type'] == 'standard.Ellipse')
                            {
                                $arrFix[$itemKey]['id'] = $fixniId;
                                $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                            }
                        }
                    }
                }
             }else{
                foreach ($arrAttr as $itemKey => $itemValue) 
                {
                    $fixniId = $this->searchForKey($itemValue,$linkArr,'target','id');
                    $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                    if(isset($labelFixIni['type']))
                    {
                        if($labelFixIni['type'] == 'standard.Ellipse')
                        {
                            $arrFix[$itemKey]['id'] = $fixniId;
                            $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                            array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                        }else{
                            $fixniId = $this->searchForKey($itemValue,$linkArr,'source','id');
                            $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                            if($labelFixIni['type'] == 'standard.Ellipse')
                            {
                                $arrFix[$itemKey]['id'] = $fixniId;
                                $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                            }
                        }
                    }
                }
             }

             //two
             $arrAttr = $this->searchForIdArray($key,$linkArr,'target');
             if(count($arrAttr) <= 0)
             {
                $arrAttr = $this->searchForIdArray($key,$linkArr,'source');
                if(count($arrAttr) > 0)
                {
                    foreach ($arrAttr as $itemKey => $itemValue) 
                    {
                        $fixniId = $this->searchForKey($itemValue,$linkArr,'target','id');
                        $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                        if(isset($labelFixIni['type']))
                        {
                            if($labelFixIni['type'] == 'standard.Ellipse')
                            {
                                if(isset($arrFix[$itemKey]))
                                {
                                    if(!in_array($labelFixIni['attrs']['label']['text'], $uniqueValues))
                                    {
                                        $itemKey += 1;
                                        $arrFix[$itemKey]['id'] = $fixniId;
                                        $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                        array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                    }
                                }else{
                                    $arrFix[$itemKey]['id'] = $fixniId;
                                    $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                    array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                }
                            }
                        }
                    }
                }
             }else{
                foreach ($arrAttr as $itemKey => $itemValue) 
                {
                    $fixniId = $this->searchForKey($itemValue,$linkArr,'source','id');
                    $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                    if(isset($labelFixIni['type']))
                    {
                        if($labelFixIni['type'] == 'standard.Ellipse')
                        {
                            if(isset($arrFix[$itemKey]))
                            {
                                if(!in_array($labelFixIni['attrs']['label']['text'], $uniqueValues))
                                {
                                    $itemKey += 1;
                                    $arrFix[$itemKey]['id'] = $fixniId;
                                    $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                    array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                }
                            }else{
                                $arrFix[$itemKey]['id'] = $fixniId;
                                $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                            }
                        }
                    }
                }
             }

             $answer['entitiy_attr'][$key]['attrs'] = $arrFix;
        }

        //dd($answer['entitiy_attr']);

        $answer = json_encode($answer);
        $exercise = Exercise::find($request->exercise_id);
        $exercise->package_id = $request->package_id;
        $exercise->question = $request->question;
        $exercise->answer = $answer;
        $exercise->update();
        $url = url('/').'/packages/'.$request['package_id'].'';
        return response()->json(['message'=>'success','url'=>$url]);
    }

    public function destroy($id)
    {

    }

    //exercise
    public function nextStep(Request $request)
    {
        $status = 'incorrect';
        $message = 'Jawaban salah, silahkan periksa kembali';
        $url = null;
        $step = $request->step;
        $exerciseId = $request->exercise_id;
        $scheduleId = $request->schedule_id;
        $exercise = Exercise::where('id',$exerciseId)->first();
        $question = $request->question;
        $answerKey = json_decode(json_encode($exercise),true);
        $answerKey = json_decode($answerKey['answer'],true);
        $keyMust = 0;
        $cekiRelasi = ['true'=>0,'key'=>$keyMust,'false'=>0];
        //manipulasi relasi & entitas_attribut
        $answer = json_decode($request->answer,true);
        if(count($answer['cells']) < count($answerKey['cells']))
        {
            $message = 'Jawaban salah, silahkan periksa kembali, pastikan tidak ada yang terlewat';
        }else
        {
            $answer['relationship'] = [];
            $answer['entitiy_attr'] = [];
            $linkArr = [];
            $relationKey = -1;
            $entityKey = -1;
            foreach ($answer['cells'] as $key => $value) 
            {
               if($value['type'] == 'erd.Relationship')
               {
                    $relationKey++;
                    $label = $value['attrs']['text']['text'];
                    $Id = $value['id'];
                    $inCardinality = $value['ports']['items'][0]['attrs']['label']['text'];
                    $inId = $value['ports']['items'][0]['id'];
                    $outCardinality = $value['ports']['items'][1]['attrs']['label']['text'];
                    $outId = $value['ports']['items'][1]['id'];
                    $inLabel = null;
                    $outLabel = null;

                    $fixInId = $this->searchForId($inId,$answer['cells'],'target');
                    if($fixInId == null)
                    {
                        // jika null maka cari targetnya
                        $fixInId = $this->searchForId($inId,$answer['cells'],'source');
                        $fixInId = $fixInId['target']['id'];

                        $inLabel = $this->searchForId($fixInId,$answer['cells'],'id');
                        $inLabel = $inLabel['attrs']['label']['text'];
                    }else{
                        //jika tidak null maka cari source nya
                        $fixInId = $fixInId['source']['id'];
                        $inLabel = $this->searchForId($fixInId,$answer['cells'],'id');

                        $inLabel = $inLabel['attrs']['label']['text'];
                    }

                    $fixOutId = $this->searchForId($outId,$answer['cells'],'target');
                    if($fixOutId == null)
                    {
                        // jika null maka cari targetnya
                        $fixOutId = $this->searchForId($outId,$answer['cells'],'source');
                        $fixOutId = $fixOutId['target']['id'];

                        $outLabel = $this->searchForId($fixOutId,$answer['cells'],'id');
                        $outLabel = $outLabel['attrs']['label']['text'];
                    }else
                    {
                        //jika tidak null maka cari source nya
                        $fixOutId = $fixOutId['source']['id'];
                        $outLabel = $this->searchForId($fixOutId,$answer['cells'],'id');
                        $outLabel = $outLabel['attrs']['label']['text'];
                    }

                    $answer['relationship'][$relationKey]['id'] = $Id;
                    $answer['relationship'][$relationKey]['label'] = strtolower($label);
                    $answer['relationship'][$relationKey]['in']['id'] = $fixInId; //entity
                    $answer['relationship'][$relationKey]['in']['cardinality'] = $inCardinality;
                    $answer['relationship'][$relationKey]['in']['label'] = $inLabel; 
                    $answer['relationship'][$relationKey]['out']['id'] = $fixOutId; //entity
                    $answer['relationship'][$relationKey]['out']['cardinality'] = $outCardinality;
                    $answer['relationship'][$relationKey]['out']['label'] = $outLabel; 
               }elseif ($value['type'] == 'standard.Rectangle') 
               {
                   $entityKey++;
                   $labelAttr = $value['attrs']['label']['text'];
                   $idAttr = $value['id'];
                   $answer['entitiy_attr'][$idAttr]['label'] = $labelAttr;
               }elseif ($value['type'] == 'link') 
               {
                   array_push($linkArr, $value);
               }
            }
            
            //manipulasi entitas
            foreach ($answer['entitiy_attr'] as $key => $value) 
            {
                 $arrFix = [];
                 $uniqueValues = array();
                 //one
                 $arrAttr = $this->searchForIdArray($key,$linkArr,'source');
                 if(count($arrAttr) <= 0)
                 {
                    $arrAttr = $this->searchForIdArray($key,$linkArr,'target');
                    if(count($arrAttr) > 0)
                    {
                        foreach ($arrAttr as $itemKey => $itemValue) 
                        {
                            $fixniId = $this->searchForKey($itemValue,$linkArr,'source','id');
                            $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                            if(isset($labelFixIni['type']))
                            {
                                if($labelFixIni['type'] == 'standard.Ellipse')
                                {
                                    $arrFix[$itemKey]['id'] = $fixniId;
                                    $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                    array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                }
                            }
                        }
                    }
                 }else{
                    foreach ($arrAttr as $itemKey => $itemValue) 
                    {
                        $fixniId = $this->searchForKey($itemValue,$linkArr,'target','id');
                        $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                        if(isset($labelFixIni['type']))
                        {
                            if($labelFixIni['type'] == 'standard.Ellipse')
                            {
                                $arrFix[$itemKey]['id'] = $fixniId;
                                $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                            }else{
                                $fixniId = $this->searchForKey($itemValue,$linkArr,'source','id');
                                $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                                if($labelFixIni['type'] == 'standard.Ellipse')
                                {
                                    $arrFix[$itemKey]['id'] = $fixniId;
                                    $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                    array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                }
                            }
                        }
                    }
                 }

                 //two
                 $arrAttr = $this->searchForIdArray($key,$linkArr,'target');
                 if(count($arrAttr) <= 0)
                 {
                    $arrAttr = $this->searchForIdArray($key,$linkArr,'source');
                    if(count($arrAttr) > 0)
                    {
                        foreach ($arrAttr as $itemKey => $itemValue) 
                        {
                            $fixniId = $this->searchForKey($itemValue,$linkArr,'target','id');
                            $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                            if(isset($labelFixIni['type']))
                            {
                                if($labelFixIni['type'] == 'standard.Ellipse')
                                {
                                    if(isset($arrFix[$itemKey]))
                                    {
                                        if(!in_array($labelFixIni['attrs']['label']['text'], $uniqueValues))
                                        {
                                            $itemKey += 1;
                                            $arrFix[$itemKey]['id'] = $fixniId;
                                            $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                            array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                        }
                                    }else{
                                        $arrFix[$itemKey]['id'] = $fixniId;
                                        $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                        array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                    }
                                }
                            }
                        }
                    }
                 }else{
                    foreach ($arrAttr as $itemKey => $itemValue) 
                    {
                        $fixniId = $this->searchForKey($itemValue,$linkArr,'source','id');
                        $labelFixIni = $this->searchForId($fixniId,$answer['cells'],'id');
                        if(isset($labelFixIni['type']))
                        {
                            if($labelFixIni['type'] == 'standard.Ellipse')
                            {
                                if(isset($arrFix[$itemKey]))
                                {
                                    if(!in_array($labelFixIni['attrs']['label']['text'], $uniqueValues))
                                    {
                                        $itemKey += 1;
                                        $arrFix[$itemKey]['id'] = $fixniId;
                                        $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                        array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                    }
                                }else{
                                    $arrFix[$itemKey]['id'] = $fixniId;
                                    $arrFix[$itemKey]['label'] = $labelFixIni['attrs']['label']['text'];
                                    array_push($uniqueValues, $labelFixIni['attrs']['label']['text']);
                                }
                            }
                        }
                    }
                 }
                 $answer['entitiy_attr'][$key]['attrs'] = $arrFix;
            }

            //dd($answer['relationship']);
            //checking jawban relasi
            $answerRelation = false;
            $answerKeyRelation = [];
            $keyMust = 0;
            $arrChec = [];
            $cekiRelasi = ['true'=>0,'key'=>$keyMust,'false_in'=>0,'false_out'=>0];
            //olah lagi
            $olahKey = [];
            foreach ($answerKey['relationship'] as $key => $value) 
            {
                $olahKey[$key]['label'][0] = $value['in']['label'].'-'.$value['out']['label'];
                $olahKey[$key]['label'][1] = $value['out']['label'].'-'.$value['in']['label'];
                $olahKey[$key]['card'] = $value['in']['cardinality'].'-'.$value['out']['cardinality'];
            }
            $olahAnswer = [];
            foreach ($answer['relationship'] as $key => $value) 
            {
                $olahAnswer[$key]['label'] = $value['in']['label'].'-'.$value['out']['label'];
                $olahAnswer[$key]['card'] = $value['in']['cardinality'].'-'.$value['out']['cardinality'];
            }
           
            foreach ($olahAnswer as $key1 => $value1) 
            {
                $cekiRelasi['key']++;
                foreach ($olahKey as $key2 => $value2) 
                {
                   if(in_array($value1['label'], $value2['label']))
                   {
                      if($value1['card'] == $value2['card'])
                      {
                        $cekiRelasi['true']++;
                      }
                   }
                }
            }

           // dd($cekiRelasi);
            // foreach ($answer['relationship'] as $relationshipKey => $relationshipValue) 
            // {
            //     foreach ($relationshipValue['in'] as $key1 => $value1) 
            //     {
            //         $answerKeyRelation['in'][$relationshipKey][$key1] = $value1;
            //     }
            //     foreach ($relationshipValue['out'] as $key2 => $value2) 
            //     {
            //         $answerKeyRelation['out'][$relationshipKey][$key2] = $value2;
            //     }
            // }
           // dd($answerKeyRelation);

            // $answerKeyRelationKey = [];
            // foreach ($answerKey['relationship'] as $relationshipKey => $relationshipValue) 
            // {
            //     foreach ($relationshipValue['in'] as $key1 => $value1) 
            //     {
            //         $answerKeyRelationKey['in'][$relationshipKey][$key1] = $value1;
            //     }
            //     foreach ($relationshipValue['out'] as $key2 => $value2) 
            //     {
            //         $answerKeyRelationKey['out'][$relationshipKey][$key2] = $value2;
            //     }
            // }
           
           // dd($answerKeyRelationKey);
            
            
            // foreach ($answerKeyRelationKey['in'] as $answerItemKey => $answerItemValue) 
            // {
            //     foreach ($answerKeyRelation['in'] as $answerKeyItemkey => $answerKeyItemValue) 
            //     {
            //         if($answerItemValue['label'] == $answerKeyItemValue['label'])
            //         {
            //             if($answerItemValue['cardinality'] == $answerKeyItemValue['cardinality'])
            //             {
            //                // $answerRelation = true;
            //                 $arrChec['in'][$answerItemKey]['result'] = true;
            //             }else{
            //                // $answerRelation = false;
            //                 $arrChec['in'][$answerItemKey]['result'] = false;
            //             }
            //         }
            //     }
            //     $keyMust++;
            // }

            // foreach ($answerKeyRelationKey['out'] as $answerItemKey => $answerItemValue) 
            // {
            //     foreach ($answerKeyRelation['out'] as $answerKeyItemkey => $answerKeyItemValue) 
            //     {
            //         if($answerItemValue['label'] == $answerKeyItemValue['label'])
            //         {
            //             if($answerItemValue['cardinality'] == $answerKeyItemValue['cardinality'])
            //             {
            //                // $answerRelation = true;
            //                 $arrChec['out'][$answerItemKey]['result'] = true;
            //             }else{
            //                // $answerRelation = false;
            //                 $arrChec['out'][$answerItemKey]['result'] = false;
            //             }
            //         }
            //     }
            //     $keyMust++;
            // }

            
           // dd($arrChec);
            // if(isset($arrChec['in']))
            // {
            //     foreach ($arrChec['in'] as $key => $value) 
            //     {
            //        if($value['result'])
            //        {
            //          $cekiRelasi['true']++;
            //        }else
            //        {
            //          $cekiRelasi['false']++;
            //        }
            //     }
            // }

            
            // if(isset($arrChec['out']))
            // {
            //     foreach ($arrChec['out'] as $key => $value) 
            //     {
            //        if($value['result'])
            //        {
            //          $cekiRelasi['true']++;
            //        }else
            //        {
            //          $cekiRelasi['false']++;
            //        }
            //     }
            // }
            //dd($cekiRelasi);
            if($cekiRelasi['true'] == $cekiRelasi['key'])
            {
                $answerRelation = true;
            }
           // dd($answerRelation);
            $answerAttr = false;
            //cheking jawaban attr
            $answerKeyAttrs = [];
            foreach ($answerKey['entitiy_attr'] as $entityKey => $entityItem) 
            {
                foreach ($entityItem['attrs'] as $key => $value) 
                {
                    $answerKeyAttrs[$entityKey]['label'][$key] = $value['label'];
                }
            }
            foreach ($answer['entitiy_attr'] as $entityKey => $entityItem) 
            {
               foreach ($entityItem['attrs'] as $key => $value)
               {
                    if(in_array($value['label'], $answerKeyAttrs[$entityKey]['label']))
                    {
                        $answerAttr = true;
                    }else{
                        $answerAttr = false;
                    }   
               }
            }

            if($answerAttr && $answerRelation)
            {
                if($step == 'step')
                {
                    $status = 'correct';
                    $message = 'Jawaban benar, mengarahkan ke soal berikutnya...';
                    $question += 1;
                    $url = url('exercise').'/'.$scheduleId.'?question='.$question;
                }else{
                    $status = 'correct';
                    $message = 'Selamat, Kamu telah menyelesaikan seluruh latihan dengan benar';
                    $url = url('home');

                }
            }
        }
        //return $rightAnswer;
        //save to log exercise
        $studentId = Student::where('user_id',Auth::user()->id)->first();
        if($studentId)
        {
            $check = LogExercise::where('student_id',$studentId->id)
                        ->where('exercise_id',$exerciseId)
                        ->where('schedule_id',$scheduleId)
                        ->where('status','correct')
                        ->first();
            if($check)
            {
                if($check->status == 'incorrect')
                {
                    if($status == 'correct')
                    {
                        LogExercise::create([
                            'student_id'=>$studentId->id,
                            'exercise_id'=>$exerciseId,
                            'schedule_id'=>$scheduleId,
                            'time'=>'00:00:00',
                            'answer'=>$request->answer,
                            'status'=>'correct',
                        ]);

                        $package_id = Schedule::findOrFail($scheduleId)->package_id;
                        $totalExercise = Exercise::where('package_id', $package_id)->count();
                        $score = 100 / $totalExercise;
                        Score::create([
                            'student_id'=>$studentId->id,
                            'schedule_id'=>$scheduleId,
                            'score'=>$score
                        ]);
                    }else
                    {
                         LogExercise::create([
                            'student_id'=>$studentId->id,
                            'exercise_id'=>$exerciseId,
                            'schedule_id'=>$scheduleId,
                            'time'=>'00:00:00',
                            'answer'=>$request->answer,
                            'status'=>'incorrect',
                        ]);
                    }
                }
            }else
            {
                 LogExercise::create([
                    'student_id'=>$studentId->id,
                    'exercise_id'=>$exerciseId,
                    'schedule_id'=>$scheduleId,
                    'time'=>'00:00:00',
                    'answer'=>$request->answer,
                    'status'=>$status,
                ]);
            }
        }
        return response()->json(['data'=>$status,'message'=>$message,'url'=>$url,'true'=>$cekiRelasi['true'],'key'=>$cekiRelasi['key']]);
    }
}