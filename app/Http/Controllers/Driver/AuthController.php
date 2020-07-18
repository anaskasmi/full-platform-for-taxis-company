<?php
namespace App\Http\Controllers\Driver;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Driver;
class AuthController extends Controller
{
    
    
    public function user(Request $request)
    {
        return response()->json(Auth::user());
    }







    public function login (Request $request) {
        $request->validate([
            'PermitNumber' => 'required',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $driver = Driver::where('PermitNumber', $request->PermitNumber)->first();
    
        if ($driver) {
            if (Hash::check($request->password, $driver->password)) {
                // $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                // $response = ['token' => $token];
                // return response($response, 200);
                if(Hash::check("taxi-office", $driver->password)){

                    $tokenResult = $driver->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    if ($request->remember_me)
                        $token->expires_at = Carbon::now()->addWeeks(10);
                    $token->save();
                    return response()->json([
                        'changePassword'=>true,
                        'access_token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString()
                    ]);
                }
                else{
                    
                    $tokenResult = $driver->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    if ($request->remember_me)
                        $token->expires_at = Carbon::now()->addWeeks(10);
                    $token->save();
                    return response()->json([
                        'changePassword'=>false,
                        'access_token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString()
                    ]);
                }

            } else {
                $response = "Password missmatch";
                return response($response, 401);
            }
    
        } else {
            $response = 'User does not exist '.$request->email;
            return response($response, 401);
        }
    
    }



    public function logout (Request $request) {

        $token = $request->user()->token();
        $token->delete();
    
        $response = 'You have been succesfully logged out!';
        return response($response, 200);
    
    }




    public function changePassword(Request $request){
        if (!(Hash::check($request->get('oldPassword'), Auth::user()->password))) {
            // The passwords matches
            $response = 'Your current password does not matches with the password you provided. Please try again.';
            return response($response, 400);
        }
        if(strcmp($request->get('oldPassword'), $request->get('newPassword')) == 0){
            //Current password and new password are same
            $response = 'New Password cannot be same as your current password. Please choose a different password.';
            return response($response, 400);
        }
        $validatedData = $request->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('newPassword'));
        $user->save();
        $response = 'Password changed successfully.';
        return response($response, 200);

    }





}