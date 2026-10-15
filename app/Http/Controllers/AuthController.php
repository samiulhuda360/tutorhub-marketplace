<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  // pages
  public function showRegisterSelector() { return view('auth.register'); }
  public function showRegisterTutor() { return view('auth.register_tutor'); }
  public function showRegisterParent() { return view('auth.register_parent'); }
  public function showLogin() { return view('auth.login'); }

  // actions
  public function registerTutor(Request $request)
  {
    $data = $request->validate([
      'name' => ['required','string','max:255'],
      'email' => ['required','email','max:255','unique:users,email'],
      'password' => ['required','string','min:6','confirmed'],
    ]);

    $user = User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
      'role' => 'tutor',
      'is_admin' => 0,
    ]);

    Auth::login($user);
    return redirect()->route('tutor.dashboard');
  }

  public function registerParent(Request $request)
  {
    $data = $request->validate([
      'name' => ['required','string','max:255'],
      'email' => ['required','email','max:255','unique:users,email'],
      'password' => ['required','string','min:6','confirmed'],
    ]);

    $user = User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
      'role' => 'parent',
      'is_admin' => 0,
    ]);

    Auth::login($user);
    return redirect()->route('parent.dashboard');
  }

 public function login(Request $request)
{
    $data = $request->validate([
        'email' => ['required','email'],
        'password' => ['required','string'],
    ]);

    if (Auth::attempt($data, $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Redirect based on user role
        $role = auth()->user()->role;
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'tutor') {
            return redirect()->route('tutor.dashboard');
        } else {
            return redirect()->route('parent.dashboard');
        }
    }

    return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
}


  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
  }
}
