<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('portal.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:5',
            'role' => 'required|in:faculty,student',
        ]);

        // 1. Check if email exists inside registration nodes at all
        $userExists = User::where('email', $credentials['email'])->exists();

        if (!$userExists) {
            return back()->withErrors([
                'email' => 'This email is not registered yet. Please click "Sign Up" below to create an account first.',
            ])->withInput();
        }

        // 2. Structural role verification check block
        $userWithRole = User::where('email', $credentials['email'])
                            ->where('role', $credentials['role'])
                            ->first();

        if (!$userWithRole) {
            return back()->withErrors([
                'email' => 'Account found, but it is not registered as a ' . ucfirst($credentials['role']) . '. Please select your correct role.',
            ])->withInput();
        }

        // 3. Complete authentication verification handshake
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'role' => $credentials['role']])) {
            $request->session()->regenerate();
            return redirect()->route('portal.dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'password' => 'The password you entered is incorrect.',
        ])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|confirmed',
            'role' => 'required|in:faculty,student',
        ]);

        // Force strict format lookup pattern if role is student during signup
        if ($request->role === 'student') {
            if (!$request->has('reg_no') || !preg_match('/^[A-Z]{2}\d{2}-[A-Z]{3}-\d{3}$/', strtoupper($request->reg_no))) {
                return back()->withInput()->withErrors(['reg_no' => 'Invalid Registration Number Formatt.']);
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
