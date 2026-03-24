<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string|min:4|max:50|unique:users,login_id',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'login_id' => $request->login_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'global_role' => 'user',
        ]);
        
        Auth::login($user);
        
        return redirect('/')->with('success', '회원가입이 완료되었습니다.');
    }
    
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $credentials = [
            'login_id' => $request->login_id,
            'password' => $request->password,
        ];
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect('/');
        }
        
        return back()
            ->withInput($request->only('login_id'))
            ->with('error', '아이디 또는 비밀번호가 올바르지 않습니다.');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
