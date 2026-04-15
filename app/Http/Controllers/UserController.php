<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function registration(Request $request)
    {
        $request->validate([
            'fio' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required',
        ]);
//        $code = rand(100000, 999999);
        $user = new User();
        $user->fio = $request->fio;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
//        $user->verification_code = $code;
//        $user->code_expires_at = Carbon::now()->addMinutes(10);
        $user->save();
        return redirect()->route('authorization');
//        Mail::to($user->email)->send(new VerificationCodeMail($code));
//        return redirect()->route('verify.page')->with('email', $user->email);
    }
//    public function verifyCode(Request $request)
//    {
//        $user = User::where('email', $request->email)->first();
//        if (!$user){
//            return back()->with('error', 'Пользователь не найден');
//        }
//        if (Carbon::now()->greaterThan($user->code_expires_at)){
//            return back()->with('error', 'Код устарел');
//        }
//        if ($user->verification_code != $request->code){
//            return back()->with('error', 'Неверный код');
//        }
//        $user->email_verified_at = now();
//        $user->verification_code = null;
//        $user->code_expires_at = null;
//        $user->save();
//        return redirect()->route('authorization');
//    }
//    public function resendCode(Request $request)
//    {
//        $user = User::where('email', $request->email)->first();
//        $code = rand(100000, 999999);
//        $user->verification_code = $code;
//        $user->code_expires_at = now()->addMinutes(10);
//        $user->save();
//        Mail::to($user->email)->send(new VerificationCodeMail($code));
//        return back()->with('success', 'Код отправлен повторно');
//    }
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $user = User::query()
            ->where('email', $request->email)
            ->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect()->route('welcome');
        } else {
            return redirect()->back()->with('error', 'Неверная почта или пароль');
        }
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('authorization')->with('success', 'Вы вышли из аккаунта');
    }
}
