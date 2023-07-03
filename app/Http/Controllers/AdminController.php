<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Forgot;
use  App\Models\User;
use  App\Models\Evalution;
use  Validator;
use DB;

class AdminController extends Controller
{

  public function getevaluation( Request $request , $id=null ){
   $sql=$date='';
   $user= auth()->user();
   $fromdate=$request->fromdate;
   $todate=$request->todate;

   if($id){
     $sql = "select * FROM evaluation where user_id = ".$user->id." AND  evaluation_id = ".$id;
   }
   if($user){
     if(!empty($fromdate) && !empty($todate)){
      $date = "WHERE eval.created_at BETWEEN '".$fromdate."' AND '".$todate."' ";
    }
    $sql = "select eval.*,user.email,user.country,user.typefree,user.typeconsultation,user.name FROM evaluation as eval left join users as user on user.id = eval.user_id ".$date." order by evaluation_id "; 
  }

  $results = DB::select($sql);

  return response()->json( [
    'success' => true,
    'data' => $results
    ], 200 );

}

public function dublicateCopy(Request $request , $id=null){

  $selected = Evalution::find($id);
  $copy = $selected->replicate()->fill(
    [
    'initiative_name' => str_replace("-copy","",$selected->initiative_name).'-copy',
    'scorecard_name' => str_replace("-copy","",$selected->scorecard_name).'-copy',
    'version' => $selected->version + 1,
    ]
    );
  $copy->save();

  return response()->json( [
    'success' => true,
    'data' => $copy
    ], 200 );
}

public function getusers( Request $request , $id=null ){
 $sql='';
 $user= auth()->user();

 $sql = "select * FROM users";

 $results = DB::select($sql);

 return response()->json( [
  'success' => true,
  'data' => $results
  ], 200 );

}


}