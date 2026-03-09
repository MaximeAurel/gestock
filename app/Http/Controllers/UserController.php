<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        $authUser = auth()->user();
        $authRole = strtolower(trim($authUser?->role?->nom ?? ''));
        $isAdmin = in_array($authRole, ['admin', 'administrateur'], true);

        if (!$isAdmin && $authUser?->id !== $user->id) {
            abort(403, 'Acces refuse');
        }

        $user->load('role');

        return view('users.show', compact('user'));
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
                ->with('success', 'Utilisateur cree avec succes !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la creation de l\'utilisateur.');
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
                ->with('success', 'Utilisateur mis a jour avec succes !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise a jour de l\'utilisateur.');
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'Utilisateur supprime avec succes !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de l\'utilisateur.');
        }
    }

    /**
     * Changement de mot de passe specifique
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
                ->with('success', 'Mot de passe mis a jour avec succes !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise a jour du mot de passe.');
        }
    }
}
