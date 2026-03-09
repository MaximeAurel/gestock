<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        try {
            User::create([
                'nom' => $request->nom,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'mot_de_passe' => Hash::make($request->mot_de_passe)
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Utilisateur créé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de l’utilisateur.');
        }
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            $user->update([
                'nom' => $request->nom,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'mot_de_passe' => $request->mot_de_passe ? Hash::make($request->mot_de_passe) : $user->mot_de_passe
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Utilisateur mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de l’utilisateur.');
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'Utilisateur supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de l’utilisateur.');
        }
    }

    /**
     * Changement de mot de passe spécifique
     */
    public function changerMotDePasse(Request $request, User $user)
    {
        $request->validate([
            'mot_de_passe' => 'required|string|min:6|confirmed'
        ]);

        try {
            $user->update([
                'mot_de_passe' => Hash::make($request->mot_de_passe)
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Mot de passe mis à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du mot de passe.');
        }
    }
}
