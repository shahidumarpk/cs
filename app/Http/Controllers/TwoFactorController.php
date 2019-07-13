<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
class TwoFactorController extends Controller
{
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);
        
        $user_id=$request->session()->get('user_id');
        $user =  User::where('id', $user_id)->firstOrFail();
        //echo $user->fname;
        
        $otp=$request->session()->get('otp');
        if($request->input('otp') == $otp){   
            
            $remember=$request->session()->get('remember');
            //Forgot the session which create during OTP
            $request->session()->forget('user_id');
            $request->session()->forget('remember');
            $request->session()->forget('fname');
            $request->session()->forget('lname');
            $request->session()->forget('otp');

            //Login User with Id once validated the OTP code.
            Auth::loginUsingId($user_id, $remember);
            //Remove the OTP code from the DB
            $userotp=\App\User::find($user->id);         
            $userotp->otp=0;
            $userotp->save();
            return redirect('/home');
        } else {
            return redirect()->back()->with('error', 'Incorrect code. Please try again.');
        }
    }

    public function showTwoFactorForm()
    {
        return view('otp');
    } 
    
}
