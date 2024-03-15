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

    protected function newConnection($db_name)
    {
        $new_connection = 'database.connections.' . $db_name;
        Config::set($new_connection, [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $db_name,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ]);
    }

    public function get(Request $request)
    {
        $dbName = $request->db_name;
        $action = $request->action;
        //retrive relationship
        $qry = DB::table('information_schema.key_column_usage')
                ->select('TABLE_NAME', 'COLUMN_NAME', 'REFERENCED_TABLE_NAME', 'REFERENCED_COLUMN_NAME')
                ->where('constraint_schema', '=', $dbName)
                ->whereNotNull('REFERENCED_TABLE_SCHEMA')
                ->whereNotNull('REFERENCED_TABLE_NAME')
                ->whereNotNull('REFERENCED_COLUMN_NAME')
                ->get();
        $getData = json_decode(json_encode($qry),true);
        $result = '';
        $status = true;
        $mtom = 'false';
        if(empty($getData))
        {
            $status = false;
            $result = '<p align="center" style="color:red">Mohon maaf database anda tidak memiliki table atau relasi mohon gunakan database yg mempunyai table dan relasi</p>';
        }else{
            //return $getData;
            $arrDt = [];
            foreach ($getData as $key => $value) 
            {
                array_push($arrDt, $value['TABLE_NAME']);
            }
            $arrDt = array_count_values($arrDt);
            $stripTags = [];
            $arrData = [];
            //$arrCard = [];
            $arrResquest = json_decode(json_encode($request->all()),true);
            foreach ($getData as $key => $value) 
            {
               $tableName = $value['TABLE_NAME'];
               $columnName = $value['COLUMN_NAME'];
               $referenceT = $value['REFERENCED_TABLE_NAME'];
               $referenceC = $value['REFERENCED_COLUMN_NAME'];
               $cardinality = $this->getCardinality($tableName,$columnName,$referenceT,$referenceC,$dbName);
               //$arrCard[$tableName][$referenceT] = $cardinality;
               if($action == 'table_list')
               {
                   $last = '';
                   $label = '';
                   if($arrDt[$value['TABLE_NAME']] > 1)
                   {
                        //if(!isset($stripTags[$value['TABLE_NAME']]))
                        //{
                             $mtom = 'true';
                             $last = '<td 
                                          style="text-align:center;padding: 7%;border: 1px solid #666;">
                                        <input type="checkbox" onchange="fixMtom('.$key.')" id="mtom_'.$key.'" data-table="'.$value['TABLE_NAME'].'_'.$key.'"> M:M ?
                                      </td>';
                            $label = '<td  style="text-align:center;padding: 7%;border: 1px solid #666;">
                                        <input type="text" class="form-control label_fix" placeholder="Isi Label" 
                                        data-table="label_'.$key.'">   
                                     </td>';
                        //}
                        $stripTags[$value['TABLE_NAME']] = 1;
                   }else
                   {
                    $last = '<td style="text-align:center;border: 1px solid #666;">-</td>';
                     if($cardinality['1:1'] != null)
                     {
                        $last = '<td style="text-align:center;border: 1px solid #666;">-</td>';
                     }elseif($cardinality['M:1'] != null){
                        $last = '<td style="text-align:center;border: 1px solid #666;">-</td>';
                     }

                     $label = '<td  style="text-align:center;padding: 7%;border: 1px solid #666;">
                                        <input type="text" class="form-control label_fix" placeholder="Isi Label" 
                                        data-table="label_'.$key.'">   
                              </td>';
                   }
                   $result .= '<tr>
                                    <td style="text-align:center;border: 1px solid #666;">'.$value['TABLE_NAME'].'</td>
                                    <td style="text-align:center;border: 1px solid #666;">
                                        <select class="form-control cardinality_fix" data-table="'.$value['TABLE_NAME'].'_'.$value['REFERENCED_TABLE_NAME'].'">
                                            <option value="" selected disabled>Pilih Relasi</option>
                                            <option value="one_to_one" '.$cardinality['1:1'].'>1:1</option>
                                            <option value="one_to_many" '.$cardinality['M:1'].'>1:M</option>
                                            <option value="many_to_one" '.$cardinality['M:1'].'>M:1</option>
                                        </select>
                                    </td>
                                    <td style="text-align:center;border: 1px solid #666;">'.$value['REFERENCED_TABLE_NAME'].'</td>
                                    '.$last.'
                                    '.$label.'
                             </tr>';
               }else
               {
                    
                    $arrData[$key]['table_source'] = $value['TABLE_NAME'];
                    $arrData[$key]['table_refrence'] = $value['REFERENCED_TABLE_NAME'];
                    $cardinalityBySelect = $arrResquest[$value['TABLE_NAME'].'_'.$value['REFERENCED_TABLE_NAME']];
                    $arrData[$key]['cardinality'] = $cardinalityBySelect;
                    $arrData[$key]['label'] = $arrResquest['label_'.$key];
                    $arrData[$key]['many_to_many'] = false;
               }
            }
        }
        if($action == 'table_list')
        {
            return response()->json(['data'=>$result,'status'=>$status,'mtom'=>$mtom]);
        }else{
            if($request->data != null)
            {
                $imp = explode(',', $request->data);
                foreach ($arrData as $key => $value) 
                {
                    if(in_array($value['table_source'].'_'.$key, $imp))
                    {
                        $arrData[$key]['many_to_many'] = true;
                    }
                }
            }
            $createObjectJson = $this->createObjectJson($arrData);
            return response()->json(['joinjs'=>$createObjectJson,'arrData'=>$arrData]);
        }
    }

    public function createObjectJson($data)
    {
        $result  = [];
        $nambah = 0;
        $index = -1;
        foreach ($data as $key => $value) 
        {
           $nambah += 70;

               $index++;
               //table source shape
               $idSource = strval($value['table_source'].'_'.$value['table_refrence']);
               $result[$key]['source']['type'] = 'standard.Rectangle';
               $result[$key]['source']['position']['x'] = 100 + $nambah;
               $result[$key]['source']['position']['y'] = 57 + $nambah;
               $result[$key]['source']['size']['width'] = 100;
               $result[$key]['source']['size']['height'] = 40;
               $result[$key]['source']['angle'] = 0;
               $result[$key]['source']['z'] = 1;
               $result[$key]['source']['id'] = $idSource;
               $result[$key]['source']['attrs']['body']['fill'] = '#6777ef';
               $result[$key]['source']['attrs']['label']['fill'] = 'white';
               $result[$key]['source']['attrs']['label']['magnet'] = true;
               $result[$key]['source']['attrs']['label']['text'] = $value['table_source'];

               //table refrence shape
               $idReference = strval($value['table_refrence'].'_'.$value['table_source']);
               $result[$key]['reference']['type'] = 'standard.Rectangle';
               $result[$key]['reference']['position']['x'] = 499 + $nambah;
               $result[$key]['reference']['position']['y'] = 59 + $nambah;
               $result[$key]['reference']['size']['width'] = 100;
               $result[$key]['reference']['size']['height'] = 40;
               $result[$key]['reference']['angle'] = 0;
               $result[$key]['reference']['z'] = 1;
               $result[$key]['reference']['id'] = $idReference;
               $result[$key]['reference']['attrs']['body']['fill'] = '#6777ef';
               $result[$key]['reference']['attrs']['label']['fill'] = 'white';
               $result[$key]['reference']['attrs']['label']['magnet'] = true;
               $result[$key]['reference']['attrs']['label']['text'] = $value['table_refrence'];

               //relation shape
               $idRelation = $idSource.'-and-'.$idReference;
               $result[$key]['relation']['type'] = 'erd.Relationship';
               $result[$key]['relation']['position']['x'] = 290 + $nambah;
               $result[$key]['relation']['position']['y'] = 50 + $nambah;
               $result[$key]['relation']['size']['width'] = 100;
               $result[$key]['relation']['size']['height'] = 50;
               $result[$key]['relation']['z'] = 2;
               $result[$key]['relation']['id'] = $idRelation;
               //attr
               $result[$key]['relation']['attrs']['.outer']['fill'] = '#6777ef';
               $result[$key]['relation']['attrs']['.outer']['stroke'] = 'none';
               $result[$key]['relation']['attrs']['.outer']['filter']['name'] = 'dropShadow';
               $result[$key]['relation']['attrs']['.outer']['filter']['args']['dx'] = 0;
               $result[$key]['relation']['attrs']['.outer']['filter']['args']['dy'] = 2;
               $result[$key]['relation']['attrs']['.outer']['filter']['args']['blur'] = 1;
               $result[$key]['relation']['attrs']['.outer']['filter']['args']['color'] = '#333333';
               $result[$key]['relation']['attrs']['text']['text'] = $value['label'];
               $result[$key]['relation']['attrs']['text']['fill'] = '#ffffff';
               $result[$key]['relation']['attrs']['text']['letterSpacing'] = 0;
               $result[$key]['relation']['attrs']['text']['style']['textShadow'] = '1px 0 1px #333333';
               //ports
               //in
               $result[$key]['relation']['ports']['groups']['in']['position']['name'] = 'left';
               $result[$key]['relation']['ports']['groups']['in']['attrs']['portBody']['magnet'] = true;
               $result[$key]['relation']['ports']['groups']['in']['attrs']['portBody']['r'] = 10;
               $result[$key]['relation']['ports']['groups']['in']['attrs']['portBody']['fill'] = '#023047';
               $result[$key]['relation']['ports']['groups']['in']['attrs']['portBody']['stroke'] = '#023047';

               $result[$key]['relation']['ports']['groups']['in']['label']['position']['name'] = 'left';
               $result[$key]['relation']['ports']['groups']['in']['label']['position']['text'] = 'oke';
               $result[$key]['relation']['ports']['groups']['in']['label']['position']['args']['y'] = 6;

               $markupInLabel = [];
               $markupInLabel[0]['tagName'] = 'text';
               $markupInLabel[0]['selector'] = 'label';
               $markupInLabel[0]['className'] = 'label-text';
               $result[$key]['relation']['ports']['groups']['in']['label']['markup'] = $markupInLabel;

               $markupIn = [];
               $markupIn[0]['tagName'] = 'circle';
               $markupIn[0]['selector'] = 'portBody';
               $result[$key]['relation']['ports']['groups']['in']['markup'] = $markupIn;
               //out
               $result[$key]['relation']['ports']['groups']['out']['position']['name'] = 'right';
               $result[$key]['relation']['ports']['groups']['out']['attrs']['portBody']['magnet'] = true;
               $result[$key]['relation']['ports']['groups']['out']['attrs']['portBody']['r'] = 10;
               $result[$key]['relation']['ports']['groups']['out']['attrs']['portBody']['fill'] = '#E6A502';
               $result[$key]['relation']['ports']['groups']['out']['attrs']['portBody']['stroke'] = '#E6A502';
               $result[$key]['relation']['ports']['groups']['out']['label']['position']['name'] = 'right';
               $result[$key]['relation']['ports']['groups']['out']['label']['position']['args']['y'] = 6;

               $markupOutLabel = [];
               $markupOutLabel[0]['tagName'] = 'text';
               $markupOutLabel[0]['selector'] = 'label';
               $markupOutLabel[0]['className'] = 'label-text';
               $result[$key]['relation']['ports']['groups']['out']['label']['markup'] = $markupOutLabel;

               $markupOut = [];
               $markupOut[0]['tagName'] = 'circle';
               $markupOut[0]['selector'] = 'portBody';
               $result[$key]['relation']['ports']['groups']['out']['markup'] = $markupOut;

               $cardinality = explode('_', $value['cardinality']);
               //port group in
               $idRelationIn = $idRelation.'_in';
               $result[$key]['relation']['ports']['items'][0]['id'] = $idRelationIn = $idRelation.'_in';
               $result[$key]['relation']['ports']['items'][0]['group'] = 'in';
               
               if($value['many_to_many']){
                   $result[$key]['relation']['ports']['items'][0]['attrs']['label']['text'] = 'many'; //many
               }else{
                    $result[$key]['relation']['ports']['items'][0]['attrs']['label']['text'] = $cardinality[0]; 
               }
               $result[$key]['relation']['ports']['items'][0]['attrs']['label']['fill'] = 'black';

               //port group out
               $idRelationOut = $idRelation.'_out';
               $result[$key]['relation']['ports']['items'][1]['id'] = $idRelationOut;
               $result[$key]['relation']['ports']['items'][1]['group'] = 'out';
               
               if($value['many_to_many']){
                   $result[$key]['relation']['ports']['items'][1]['attrs']['label']['text'] = 'many'; //many
               }else{
                    $result[$key]['relation']['ports']['items'][1]['attrs']['label']['text'] = $cardinality[2]; 
               }
               $result[$key]['relation']['ports']['items'][1]['attrs']['label']['fill'] = 'black';

               //link
               if(isset($cardinality[0]))
               {
                    $idLinkOne = $idRelation.'_link_'.ucwords($cardinality[0]);
               }else{
                    $idLinkOne = $idRelation.'_link_'.ucwords('many');
               }
               
               $result[$key]['link_one']['type'] = 'link';
               $result[$key]['link_one']['id'] = $idLinkOne;
               $result[$key]['link_one']['z'] = 4;
               $result[$key]['link_one']['attrs'] = new \stdClass();
               $result[$key]['link_one']['source']['id'] = $idSource;
               $result[$key]['link_one']['source']['magnet'] = 'label';
               $result[$key]['link_one']['target']['id'] = $idRelation;
               $result[$key]['link_one']['target']['magnet'] = 'portBody';
               $result[$key]['link_one']['target']['port'] = $idRelationIn;

               if(isset($cardinality[2]))
               {
                    $idLinkTwo = $idRelation.'_link_'.ucwords($cardinality[2]);
               }else{
                    $idLinkTwo = $idRelation.'_link_'.ucwords('many');
               }
              
               $result[$key]['link_two']['type'] = 'link';
               $result[$key]['link_two']['id'] = $idLinkTwo;
               $result[$key]['link_two']['z'] = 5;
               $result[$key]['link_two']['attrs'] = new \stdClass();
               $result[$key]['link_two']['target']['id'] = $idReference;
               $result[$key]['link_two']['source']['id'] = $idRelation;
               $result[$key]['link_two']['source']['magnet'] = 'portBody';
               $result[$key]['link_two']['source']['port'] = $idRelationOut;
        }

        $final = [];
        $attr = ['source','reference','relation','link_one','link_two'];
        $index = -1;
        foreach ($result as $resultKey => $resultValue) {
            foreach ($attr as $attrKey => $attrValue) {
               $index++;
               if(isset($resultValue[$attrValue]))
               {
                    $final['cells'][$index] = $resultValue[$attrValue];
               }
            }
        }
        $final['data'] = $data;
        return $final;
    }

    public function getCardinality($table,$column_table,$reference,$column_reference,$dbName)
    {
        $this->newConnection($dbName);
        $connection = DB::connection($dbName);
        $qry = $connection->table($table)
                ->join($reference,$reference.'.'.$column_reference,'=',$table.'.'.$column_table)
                ->select($table.'.'.$column_table)
                ->groupBy($reference.'.'.$column_reference)
                ->orderBy($table.'.'.$column_table,'DESC')
                ->get();
        $result = ['1:1'=>null,'M:1'=>null];
        if(!$qry->isEmpty())
        {
            if(count($qry) > 1)
            {
                $result['M:1'] = 'selected';
            }else{
                $result['1:1'] = 'selected';
            }
        }

        return $result;

    }

    public function store(StoreErdRelationnShip $request)
    {
        $validated = $request->validated();
        $validated['type'] = 'ERD';

        (new Exercise)->create($validated);
        $url = url('/').'/packages/'.$request['package_id'].'';
        return response()->json(['message'=>'success','url'=>$url]);
    }

    public function show($exercise)
    {
       if($exercise){
            $data = json_decode(json_encode($exercise),true);
            $data['answer'] = json_decode($exercise->answer,true);
            $general = $data['answer']['data'];
            $type = 'erd';
            $typeErd = 'relationship';
            $databaseSelected = Database::find($exercise->database_id);
            $oke = json_decode(json_encode($exercise),true);
            $test = json_decode($oke['answer'],true);
            $gas = $test;
            foreach ($gas['cells'] as $key => $value) 
            {
              if($value['type'] == 'link')
              {
                unset($gas['cells'][$key]);
              }
            }
            $fixJson =  json_encode($gas);
            return view('packages.exercises.categories.ERD.show', 
                  compact('type', 'typeErd','data','general','databaseSelected','exercise','fixJson'));
        }
        return redirect()->back();
    }

    public function edit($exercise)
    {
        $databases = Database::all();
        if($exercise){
            $data = json_decode(json_encode($exercise),true);
            $data['answer'] = json_decode($exercise->answer,true);
            $general = $data['answer']['data'];
            $type = 'erd';
            $typeErd = 'relationship';
            $databaseSelected = Database::find($exercise->database_id);
            return view('packages.exercises.categories.ERD.edit', 
                  compact('databases', 'type', 'typeErd','data','general','databaseSelected'));
        }
        return redirect()->back();
    }

    public function update(UpdateErdRelationnShip $request)
    {
        $validated = $request->validated();
        $validated['type'] = 'ERD';
        Exercise::find($request->id)->update($validated);
        $url = url('/').'/packages/'.$request['package_id'].'';
        return response()->json(['message'=>'success','url'=>$url]);
    }

    public function destroy($id)
    {

    }

    //exercise
    public function nextStep(Request $request)
    {
        $exerciseId = $request->exercise_id;
        $exerciseAnswer = json_decode($request->answer,true);
        $scheduleId = $request->schedule_id;
        $exercise = Exercise::where('id',$exerciseId)->first();
        $question = $request->question;
        $answerKey = json_decode(json_encode($exercise),true);
        $answerKey = json_decode($answerKey['answer'],true);
        //$answerKey = json_decode($answerKey,true);
        $step = $request->step;
        $contag = $request->confident;
        $status = 'incorrect';
        $message = 'Jawaban salah, silahkan periksa kembali';
        $url = null;
        //validated
        $answerLink = [];
        $answerLinkNo = 0;
        foreach ($answerKey['cells'] as $key => $value) 
        {
            if($value['type'] == 'link')
            {
                $target = '';
                $source = '';
                $relation = '';
                $table = '';
                if(isset($value['target']))
                {
                    if(isset($value['target']['port']))
                    {
                        $target = $value['target']['port'];
                    }else
                    {
                        if(isset($value['target']['id']))
                        {
                            $target = $value['target']['id'];
                        }
                    }
                }

                if(isset($value['source']))
                {
                    if(isset($value['source']['port']))
                    {
                        $source = $value['source']['port'];
                    }else
                    {
                        if(isset($value['source']['id']))
                        {
                            $source = $value['source']['id'];
                        }
                    }
                }

                $answerLink[$answerLinkNo]['source'] = $source;
                $answerLink[$answerLinkNo]['target'] = $target;
                $answerLinkNo++;
            }
        }

        $exerciseLink = [];
        $exerciseLinkNo = 0;
        foreach ($exerciseAnswer['cells'] as $key => $value) 
        {
            $target = '';
            $source = '';
            if($value['type'] == 'link')
            {
                if(isset($value['target']))
                {
                    if(isset($value['target']['port']))
                    {
                        $target = $value['target']['port'];
                    }else
                    {
                        if(isset($value['target']['id']))
                        {
                            $target = $value['target']['id'];
                        }
                    }
                }

                if(isset($value['source']))
                {
                    if(isset($value['source']['port']))
                    {
                        $source = $value['source']['port'];
                    }else
                    {
                        if(isset($value['source']['id']))
                        {
                            $source = $value['source']['id'];
                        }
                    }
                }

                $exerciseLink[$exerciseLinkNo]['source'] = $source;
                $exerciseLink[$exerciseLinkNo]['target'] = $target;
                $exerciseLinkNo++;
            }   
        }

        //extract in out
        //validate answer between answerKey and answerLink
        $rightAnswer = 0;
        $check = [];
        $analisa = 0;
        foreach ($exerciseLink as $exerciseLinkKey => $exerciseLinkValue) 
        {
            

            foreach ($answerLink as $answerLinkKey => $answerLinkValue) 
            {
                // $analisa[$exerciseLinkKey][$exerciseLinkValue['target']][$answerLinkValue['target']] = '0';
                // $analisa[$exerciseLinkKey][$exerciseLinkValue['source']][$answerLinkValue['source']] = '0';
                // $analisa[$exerciseLinkKey][$exerciseLinkValue['target']][$answerLinkValue['source']] = '0';
                // $analisa[$exerciseLinkKey][$exerciseLinkValue['source']][$answerLinkValue['target']] = '0';
                if($exerciseLinkValue['target'] == $answerLinkValue['target'])
                {
                    $analisa++;
                    if($exerciseLinkValue['source'] == $answerLinkValue['source'])
                    {
                        $analisa++;
                        $rightAnswer++;
                    }else{
                        $check['1'] = true;
                    }
                }elseif($exerciseLinkValue['source'] == $answerLinkValue['source'])
                {
                    $analisa++;
                    if($exerciseLinkValue['target'] == $answerLinkValue['target'])
                    {
                        $analisa++;
                        $rightAnswer++;
                    }else{
                        $check['2'] = true;
                    }
                }elseif($exerciseLinkValue['target'] == $answerLinkValue['source'])
                {
                    $analisa++;
                    if($exerciseLinkValue['source'] == $answerLinkValue['target'])
                    {
                        $analisa++;
                        $rightAnswer++;
                    }else{
                        $check['3'] = true;
                    }
                }elseif($exerciseLinkValue['source'] == $answerLinkValue['target'])
                {
                    $analisa++;
                    if($exerciseLinkValue['target'] == $answerLinkValue['source'])
                    {
                        $analisa++;
                        $rightAnswer++;
                    }else{
                        $check['4'] = true;
                    }
                }
            }
        }
        $hasilAnalisa = false;

        if($analisa % 2 == 0 && $rightAnswer % 2 == 0)
        {
            $hasilAnalisa = true;
        }
        // return response()->json([
        //     'data'=>$status,
        //     'message'=>$message,
        //     'url'=>$url,
        //     // 'Jawaban'=>$exerciseLink,
        //     // 'benar'=>$answerLink,
        //     // 'check'=>$check,
        //     'harus'=>count($answerLink),
        //     'analisa'=>$hasilAnalisa,
        //     'hasil'=>$rightAnswer
        // ]);
        $keyAnswer = count($answerLink);
        if($hasilAnalisa)
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
        //return $rightAnswer;
        //save to log exercise
        $studentId = Student::where('user_id',Auth::user()->id)->first();
        if($studentId)
        {
            LogExercise::create([
                'student_id'=>$studentId->id,
                'exercise_id'=>$exerciseId,
                'schedule_id'=>$scheduleId,
                'time'=>'00:00:00',
                'answer'=>$request->answer,
                'status'=>$status,
                'confident'=>$contag
            ]);

            if($step == 'last')
            {
                $package_id = Schedule::findOrFail($scheduleId)->package_id;
                $totalExercise = Exercise::where('package_id', $package_id)->count();
                $score = 100 / $totalExercise;
                $score = $score * $totalExercise;
                Score::create([
                    'student_id'=>$studentId->id,
                    'schedule_id'=>$scheduleId,
                    'score'=>$score
                ]);
            }
        }
        return response()->json(['data'=>$status,'message'=>$message,'url'=>$url]);
    }
}