<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

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

    public function edit(Request $request, User $user)
    {
        $request->validate([
            'fio' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);
        $user->fio = $request->fio;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->update();
        return redirect()->back();
    }
    public function editAvatar(Request $request, User $user)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
            $user->update();
        }
        return back();
    }
}
