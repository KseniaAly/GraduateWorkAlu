<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $user = new User();
        $user->fio = $request->fio;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('authorization');
    }
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
