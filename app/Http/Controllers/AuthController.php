<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Forgot;
use App\Models\User;
use App\Models\Evalution;
use Validator;
use DB;

class AuthController extends Controller
{



  public function register( Request $request )
  {

    $validator = Validator::make( $request->all(),
      [
      'name' => 'required',
      'email' => 'required|unique:users,email',
      'password' => 'required',
      'working'=>'required',
      'country'=>'required'
      ] );

    if ( $validator->fails() ) {
      return response()->json( [ 'error'=>$validator->errors() ], 401 );
    }


    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make( $request->password );
    $user->working = $request->working;
    $user->country = $request->country;
    $user->role='user';
    $user->typefree= $request->typefree;
    $user->typeconsultation= $request->typeconsultation;
    $user->company= $request->company;
    $user->designation= $request->designation;
    $user->save();


    return response()->json( [
      'success' => true,
      'data' => 'Add Successfully. Login with your Email or Password'
      ], 200 );
  }

  public function login( Request $request )
  {

    $this->validate( $request, [
      'email' => 'required|string',
      'password' => 'required|string',
      ] );

    $credentials = $request->only( [ 'email', 'password' ] );

    if ( ! $token = Auth::attempt( $credentials ) ) {
      return response()->json( [ 'message' => 'Unauthorized' ], 401 );
    }

    return $this->respondWithToken( $token );
  }


  public function forgotpassword(Request $request){
   $validator = Validator::make( $request->all(),
    [
    'email' => 'required',
    ] );

   if ( $validator->fails() ) {
    return response()->json( [ 'error'=>$validator->errors() ], 401 );
  }else{
    $password=rand(00000,99999);
    $email=$request->email;
    $results = DB::table('users')->where('email',$email)->get();
    if(!empty($results[0])){
      DB::table('users')->where('email',$email)->update(['password'=>Hash::make( $password )]);
    }
    $to_mail_name = $results[0]->name;
    $to_mail_address = $results[0]->email;
    $maildata = array("name" => $to_mail_name,"password" => $password);

    Mail::send('forgot', $maildata, function($message) use ($to_mail_name, $to_mail_address ) {
      $message->to($to_mail_address , $to_mail_name)->subject('Forgot Password Mail');
      $message->from('dreamstepshrms@gmail.com','DreamSteps');
    });

    return response()->json( [ 'message' => 'Reset password send to your email '.$to_mail_address ] );
  }
}

public function profile()
{
  return response()->json( [
    'success' => true,
    'data' =>auth()->user()
    ], 200 );

}

public function logout()
{
  auth()->logout();

  return response()->json( [ 'message' => 'Successfully logged out' ] );
}

public function refresh()
{
  return $this->respondWithToken( auth()->refresh() );
}

protected function respondWithToken( $token )
{
  return response()->json( [
    'access_token' => $token,
    'token_type' => 'bearer',
    'user' => auth()->user(),
    'expires_in' => auth()->factory()->getTTL() * 60 * 24
    ] );
}

public function setevaluation( Request $request , $id=null ){

  $validator = Validator::make( $request->all(),
    [
    'initiative_name' => 'required',
    'scorecard_name' => 'required',
    'situation' => 'required',
    ] );

  if ( $validator->fails() ) {
    return response()->json( [ 'error'=>$validator->errors() ], 401 );
  }

  $user= auth()->user();
  $msg= '';
  if($id){
    $eve = Evalution::find($id);
    $msg='Your Initiative Record Update Successfully';
  }else{
   $eve = new Evalution();
   $eve->user_id = $user->id;
   $msg='Your Initiative Record Save Successfully';
 }

 $eve->initiative_name = $request->initiative_name;
 $eve->scorecard_name = $request->scorecard_name;
 $eve->situation = $request->situation;
 $eve->opt_score = $request->opt_score;
 $eve->execution_score = $request->execution_score;
 $eve->save();


 return response()->json( [
  'success' => true,
  'message' => $msg,
  'data'=>['evaluation_id'=> $eve->evaluation_id]
  ], 200 );

}
public function getevaluation( Request $request , $id=null ){
 $sql='';
 $user= auth()->user();
 if($id){
   $sql = "select * FROM evaluation where  evaluation_id = ".$id;
 }else{
   $sql = "select * FROM evaluation where user_id = ".$user->id."  order by evaluation_id "; 
 }

 $results = DB::select($sql);

 return response()->json( [
  'success' => true,
  'data' => $results
  ], 200 );

}

public function deleteRow(Request $request){
 $user= auth()->user();
 $id = $request->evaluation_id;
 
 if($user->role=="user"){
  DB::table('evaluation')->where(['evaluation_id'=> $id,'user_id'=>$user->id])->delete();
}

if($user->role=="admin"){
  DB::table('evaluation')->where(['evaluation_id'=> $id])->delete();
}


return response()->json( [
  'success' => true,
  'data' => 'Delete Successfully...'
  ], 200 );
}



}