<?php
namespace App\Http\Controllers\Director;

use App\Director;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function user(Request $request)
    {
        return response()->json(Auth::user());
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $director = Director::where('email', $request->email)->first();

        if ($director) {
            if (Hash::check($request->password, $director->password)) {
                if (Hash::check("taxi-office", $director->password)) {
                    $tokenResult = $director->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    if ($request->remember_me) {
                        $token->expires_at = Carbon::now()->addWeeks(1);
                    }

                    $token->save();
                    return response()->json([
                        'changePassword'=>true,
                        'access_token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString(),
                    ]);
                }
                else{
                    $tokenResult = $director->createToken('Personal Access Token');
                    $token = $tokenResult->token;
                    if ($request->remember_me) {
                        $token->expires_at = Carbon::now()->addWeeks(1);
                    }

                    $token->save();
                    return response()->json([
                        'changePassword'=>false,
                        'access_token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString(),
                    ]);
                }
            } else {
                $response = "Password missmatch";
                return response($response, 401);
            }

        } else {
            $response = 'User does not exist ' . $request->email;
            return response($response, 401);
        }

    }

    public function logout(Request $request)
    {

        $token = $request->user()->token();
        $token->delete();

        $response = 'You have been succesfully logged out!';
        return response($response, 200);

    }

    public function changePassword(Request $request)
    {
        if (!(Hash::check($request->get('oldPassword'), Auth::user()->password))) {
            // The passwords matches
            $response = 'Your current password does not matches with the password you provided. Please try again.';
            return response($response, 400);
        }
        if (strcmp($request->get('oldPassword'), $request->get('newPassword')) == 0) {
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
