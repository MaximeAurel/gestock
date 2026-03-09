<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /* ========================= LOGIN ========================= */

    public function showLogin()
    {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
}

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required','email'],
        'password' => ['required']
    ]);

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        $user = Auth::user();

        return redirect()->route('dashboard')->with([
            'success' => 'Connexion réussie',
            'user_name' => $user->nom,
            'user_role' => $user->role->nom ?? 'Utilisateur'
        ]);
    }

    return back()->with('error', 'Email ou mot de passe incorrect');
}

    /* ========================= REGISTER ========================= */

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','confirmed','min:4']
        ]);

        User::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('success','Compte créé avec succès');
    }

    /* ========================= LOGOUT ========================= */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success','Déconnexion réussie');
    }
}
