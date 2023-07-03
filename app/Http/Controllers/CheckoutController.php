<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkoutdata;
use App\Models\Soldpackage;
use App\Models\Assignusers;
use  App\Models\Evalution;
use App\Models\User;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{   
    private $enableSandbox = true;

    public function fetchRequest (Request $request) {
        $formData = $request->all();
        $validator = Validator::make( $request->all(),
        [
        'first_name' => 'required',
        'last_name' => 'required',
        'address' => 'required',
        'zip' => 'required',
        'country' => 'required',
        'email' => 'required',
        'city' => 'required',
        'package_type' => 'required',
        'price' => 'required',
        'u_email' => 'required'
        ] );

        if ( $validator->fails() ) {
            return response()->json( [ 'error'=>$validator->errors() ], 401 );
        }
        else {
            $user = User::where('email',$formData['u_email'])->first();
            
            if(!empty($user)) {
                
                $checkData = Checkoutdata::where('user_id', $user->id)->first();
                
                if(!empty($checkData)) {
                    $checkoutData = new Checkoutdata();
                    $checkoutData->user_id = $user->id;
                    $checkoutData->first_name = $formData['first_name'];
                    $checkoutData->last_name = $formData['last_name'];
                    $checkoutData->address = $formData['address'];
                    $checkoutData->zip = $formData['zip'];
                    $checkoutData->country = $formData['country'];
                    $checkoutData->email = $formData['email'];
                    $checkoutData->city = $formData['city'];
                    $checkoutData->package_type = $formData['package_type'];
                    $checkoutData->price = $formData['price'];
                    $checkoutData->save();
                    
                    if($formData['package_type'] == 'one') {
                        $package_limit = 1;
                        $scorecard_limit = 2;
                    }
                    elseif($formData['package_type'] == 'ten') {
                        $package_limit = 10;
                        $scorecard_limit = 10;
                    }
                    elseif($formData['package_type'] == 'unlimited') {
                        $package_limit = -1;
                        $scorecard_limit = -1;
                    }
                    
                    DB::table('sold_packages')
                        ->where('user_id', $user->id)
                        ->update(array('package_limit' => $package_limit, 'scorecard_limit' => $scorecard_limit, 'updated_at' => date("Y-m-d H:i:s", strtotime('now'))));
                }
                else {
                    $checkoutData = new Checkoutdata();
                    $checkoutData->user_id = $user->id;
                    $checkoutData->first_name = $formData['first_name'];
                    $checkoutData->last_name = $formData['last_name'];
                    $checkoutData->address = $formData['address'];
                    $checkoutData->zip = $formData['zip'];
                    $checkoutData->country = $formData['country'];
                    $checkoutData->email = $formData['email'];
                    $checkoutData->city = $formData['city'];
                    $checkoutData->package_type = $formData['package_type'];
                    $checkoutData->price = $formData['price'];
                    $checkoutData->save();
                    
                    $token = Str::random(10);
                    $soldPackages = new Soldpackage();
                    $soldPackages->user_id = $user->id;
                    $soldPackages->package_token = $token;
                    $soldPackages->created_at = date("Y-m-d H:i:s", strtotime('now'));
                    $soldPackages->updated_at = date("Y-m-d H:i:s", strtotime('now'));
                    if($formData['package_type'] == 'one') {
                        $soldPackages->package_limit = 1;
                        $soldPackages->scorecard_limit = 2;
                    }
                    elseif($formData['package_type'] == 'ten') {
                        $soldPackages->package_limit = 10;
                        $soldPackages->scorecard_limit = 10;
                    }
                    elseif($formData['package_type'] == 'unlimited') {
                        $soldPackages->package_limit = -1;
                        $soldPackages->scorecard_limit = -1;
                    }
                    // dd($soldPackages);
                    $soldPackages->save();
                    if(!empty($soldPackages)) {
                        DB::table('assigned_users')->insert([
                            'user_id' => $user->id,
                            'package_token' => $token
                        ]);
                    }
                }
                
                return response()->json( [ 'success'=>['user' => 'Data saved successfully'] ], 200 );
            }
            else {
                return response()->json( [ 'error'=>['user' => 'User not found'] ], 500 );
            }
        }
    }
    public function assignUsers(Request $request) {
        $formData = $request->all();
        $user = User::where('email',$formData['u_email'])->first();
        $package = Soldpackage::where('package_token', $formData['token'])->get();
        $getAssigned = Assignusers::where('package_token', $formData['token'])->count();
        foreach($package as $k => $v) {
            $package_limit = $v->package_limit;
        }

        if($package_limit == -1) {
                $checkAssigned = Assignusers::where(array('user_id'=> $user->id, 'package_token'=> $formData['token'] ))->get();
                if($checkAssigned != 0) {
                    return response()->json( [ 'errorUser'=>['response' => 'User Already exists'] ], 200 );
                }
                else {
                    DB::table('assigned_users')->insert([
                    'user_id' => $user->id,
                    'package_token' => $formData['token']
                ]);
                return response()->json( [ 'success'=>['response' => 'Data saved successfully'] ], 200 );
                }
                
        }
        elseif($package_limit != 0) {
            if($package_limit > $getAssigned) {
                
                $checkAssigned = Assignusers::where(array('user_id'=> $user->id, 'package_token'=> $formData['token'] ))->count();
                
                if($checkAssigned != 0) {
                    return response()->json( [ 'errorUser'=>['response' => 'User Already exists'] ], 200 );
                }
                else {
                    DB::table('assigned_users')->insert([
                    'user_id' => $user->id,
                    'package_token' => $formData['token']
                ]);
                return response()->json( [ 'success'=>['response' => 'Data saved successfully'] ], 200 );
                }
            }
            else {
                return response()->json( [ 'error'=>['response' => 'Package limit exceeded'] ], 200 );
            }
            
        }
    }
    public function freeTrail(Request $request) {
        $formData = $request->all();
        $user = User::where('email',$formData['u_email'])->first();
        
        $token = Str::random(10);
        $soldPackages = new Soldpackage();
        $soldPackages->user_id = $user->id;
        $soldPackages->package_token = $token;
        $soldPackages->created_at = date("Y-m-d H:i:s", strtotime('now'));
        $soldPackages->updated_at = date("Y-m-d H:i:s", strtotime('now'));
        $soldPackages->package_limit = 0;
        $soldPackages->scorecard_limit = 1;
        $soldPackages->save();
        
        DB::table('assigned_users')->insert([
            'user_id' => $user->id,
            'package_token' => $token
        ]);
        return response()->json( [ 'success'=>['response' => 'Data saved successfully'] ], 200 );
    }
    public function checkFreetrail(Request $request) {
        $formData = $request->all();
        $user = User::where('email',$formData['u_email'])->first();
        $assignUserget = Soldpackage::where('user_id', $user->id)->where('package_limit', 0)->count();
        if(!empty($assignUserget)) {
            return response()->json( ['response' => 'error'], 200 );
        }
        else {
            return response()->json( ['response' => 'success'], 200 );
        }
    }
    public function getPackages(Request $request) {
        $formData = $request->all();
        $user = User::where('email',$formData['u_email'])->first();
        $assignUserget = Soldpackage::where('user_id', $user->id)->first();
        
        return json_encode($assignUserget);
    }
    public function getAssignedUsers(Request $request) {
        $formData = $request->all();
        $user = User::where('email',$formData['u_email'])->first();
        $assignUserget = Soldpackage::where('user_id', $user->id)->first();
        if(!empty($assignUserget)) {
            $assign = Assignusers::where('package_token',$assignUserget->package_token)->get();
        
        foreach($assign as $key => $v) {
            $ids[] = $v->user_id;
        }
        $users = User::whereIn('id', $ids)->get();
        
        return json_encode($users);
        }
        else {
            return json_encode([]);
        }
        
    }
    public function deleteAssignedUser (Request $request) {
        $formData = $request->all();
        
        $user = User::where('email',$formData['u_email'])->first();
        $affectedRows = Assignusers::where('user_id', $user->id)->where('package_token', $formData['token'])->delete();
        
        return response()->json( [ 'success'=>['response' => 'User Delinked Successfully'] ], 200 );
    }
    public function getIfAssigned(Request $request) {
        $formData = $request->all();
        
        $user = User::where('email',$formData['u_email'])->first();
        
        $assignUserget = Assignusers::where('user_id', $user->id)->first();
        if(!empty($assignUserget)) {
            $assign = Soldpackage::where('package_token',$assignUserget->package_token)->get();
            
            foreach($assign as $k => $v) {
                $id = $v->user_id;
            }
            $user = User::find($id);
            
            if(!empty($assign)) {
                return json_encode($user);
            }
            else {
                return json_encode(array('error' => 'empty'));
            }
        }
        else {
            return json_encode(array('error' => 'empty'));
        }
    }
    public function checkScorecard (Request $request) {
        $formData = $request->all();
        // dd($formData);
        $user = User::where('email',$formData['u_email'])->first();
        // dd($user->id);
        $getScorecards = Evalution::where('user_id', $user->id)->count();
        $package = Soldpackage::where('user_id', $user->id)->get();
        if(!empty($package)) {
            foreach($package as $k => $v) {
                $limit = $v->scorecard_limit;
            }
            if($limit > $getScorecards) {
                return json_encode(array('success' => 'Scorecard can be added in this package'));
            }
            else {
                return json_encode(array('errorLimit' => 'Limit of adding scorecard exceeds'));
            }
            
        }
        else {
            return json_encode(array('errorPackage' => 'No package found for this user'));
        }
    } 
} 