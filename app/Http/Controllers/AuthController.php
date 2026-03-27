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
            'global_role' => User::ROLE_USER,
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
    
    public function showFindIdForm()
    {
        return view('auth.find-id');
    }
    
    public function findId(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $user = User::query()
            ->where('email', $request->email)
            ->first();
        
        return view('auth.find-id', [
            'foundLoginId' => $user ? $user->login_id : null,
            'searchedEmail' => $request->email,
            'hasSearched' => true,
        ]);
    }
    
    public function showPasswordResetRequestForm()
    {
        return view('auth.password-reset-request');
    }
    
    public function checkPasswordResetRequest(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'email' => 'required|email',
        ]);
        
        $user = User::query()
            ->where('login_id', $request->login_id)
            ->where('email', $request->email)
            ->first();
        
        if (!$user) {
            return back()
                ->withInput()
                ->withErrors([
                    'login_id' => '입력한 아이디와 이메일이 일치하는 계정을 찾을 수 없습니다.',
                ]);
        }
        
        return redirect()->route('auth.password.reset.form', [
            'login_id' => $user->login_id,
            'email' => $user->email,
        ]);
    }
    
    public function showPasswordResetForm(Request $request)
    {
        $loginId = $request->query('login_id');
        $email = $request->query('email');
        
        if (empty($loginId) || empty($email)) {
            return redirect()->route('auth.password.request.form')
            ->with('error', '비밀번호 재설정 요청부터 다시 진행해주세요.');
        }
        
        return view('auth.password-reset-form', [
            'loginId' => $loginId,
            'email' => $email,
        ]);
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::query()
            ->where('login_id', $request->login_id)
            ->where('email', $request->email)
            ->first();
        
        if (!$user) {
            return redirect()
                ->route('auth.password.request.form')
                ->with('error', '비밀번호 재설정 요청부터 다시 진행해주세요.');
        }
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()
            ->route('login.form')
            ->with('success', '비밀번호가 재설정되었습니다. 새 비밀번호로 로그인해주세요.');
    }
}
