<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSmartschool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = UserSmartschool::where('username', $credentials['username'])->first();

        if ($user) {
            $passwordValid = false;
            if (sha1($credentials['password']) === $user->password) {
                $passwordValid = true;
            } else {
                try {
                    if (Hash::check($credentials['password'], $user->password)) {
                        $passwordValid = true;
                    }
                } catch (\RuntimeException $e) {
                    // Password is not a bcrypt hash — already checked SHA1 above
                }
            }

            if ($passwordValid) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
