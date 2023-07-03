<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

use  App\Models\User;
use  App\Models\Evalution;
use  Validator;
use DB;

class FormController extends Controller
{

  public function setJson( Request $request){
    $id=$request->evaluation_id;
    $json=$request->json;
    $oldJson = Evalution::where('evaluation_id',$id)->first();
    if(!empty($oldJson->json)){
      $mergeJson = json_encode(array_merge(json_decode($oldJson->json, true),json_decode($json, true)));
    }else{
      $mergeJson =$json;
    }
    $evedata=Evalution::where('evaluation_id',$id)->update(['json'=>$mergeJson]);
    if($evedata==1){
      return response()->json( ['success' => true,'message' => 'Json Insert Successfully...'], 200 );
    }
  }

  public function getJson( Request $request,$id=null){
    $data = Evalution::where('evaluation_id',$id)->first();
    return response()->json( ['success' => true,'message' => 'Evaluation Record','data'=>$data], 200 );
  }

  public function getSteps(Request $request,$id=null){
    $data = Evalution::where('evaluation_id',$id)->first();
    $json = json_decode($data->json);
    $keyArr=[];
    foreach($json as $key => $val) {
     if($key!='User'){ 
     $keyArr[]=$key;
     }
   }
   $url =  end($keyArr);
   return $url;
 }
  
  public function checkAllFormSubmited(Request $request,$id=null){
    $data = Evalution::where('evaluation_id',$id)->first();
    $json = json_decode($data->json);
    $keyArr=[];
    foreach($json as $key => $val) {
     if($key!='User'){ 
     $keyArr[]=$key;
     }
   }
   $checkPages = [
                 'Initiative',
                 'dimentionalProblem',
                 'customeractorstakeholder',
                 'buyermotivation',
                 'problemscorecard',
                 'problemvalidationscore',
                 'solutionscorecard',
                 'cruxcompetitive',
                 'cruxalignment',
                 'cruximpact',
                 'purchasedecisionalignment',
                 'revenuescore',
                 'solutionriskscore',
                 'fundingscore',
                 'markatingscore',
                 'pmcmalignment',
                 'demandpeak'
                 ];
   $result = (sizeof(array_intersect($keyArr, $checkPages))==17)?1:0;
   $resultdiff = array_diff($checkPages,$keyArr);
   return  array($result,$resultdiff);
  }

}