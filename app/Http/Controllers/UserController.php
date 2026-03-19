<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function ensureAdmin(): void
    {
        $roleName = strtolower(trim(auth()->user()?->role?->nom ?? ''));
        $isAdmin = in_array($roleName, ['admin', 'administrateur'], true);
        abort_if(!$isAdmin, 403, 'Acces refuse');
    }

    public function index()
    {
        $this->ensureAdmin();
        $users = User::with('role')->get();
        $roles = Role::all();
        $adminRoleId = auth()->user()->role_id;

        return view('users.index', compact('users', 'roles', 'adminRoleId'));
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
        $this->ensureAdmin();
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        $this->ensureAdmin();
        try {
            User::create([
                'nom' => $request->nom,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Utilisateur cree avec succes !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la creation de l\'utilisateur.');
        }
    }

    public function edit(User $user)
    {
        $this->ensureAdmin();
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UserRequest $request, User $user)
    {
        $this->ensureAdmin();
        try {
            $user->update([
                'nom' => $request->nom,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => $request->password ? Hash::make($request->password) : $user->password
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Utilisateur mis a jour avec succes !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise a jour de l\'utilisateur.');
        }
    }

    public function destroy(User $user)
    {
        $this->ensureAdmin();
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
        $this->ensureAdmin();
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Mot de passe mis a jour avec succes !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise a jour du mot de passe.');
        }
    }

    public function updateRole(Request $request, User $user)
    {
        $this->ensureAdmin();

        // Interdiction de modifier un utilisateur du même rôle (ex : autre admin)
        if ($user->role_id === auth()->user()->role_id) {
            abort(403, "Vous ne pouvez pas modifier un utilisateur ayant le même rôle.");
        }

        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update(['role_id' => $data['role_id']]);

        return back()->with('success', 'Rôle mis à jour avec succès.');
    }
}
