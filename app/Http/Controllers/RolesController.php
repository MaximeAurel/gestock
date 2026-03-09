<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Requests;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(RoleRequest $request)
    {
        try {
            Role::create($request->validated());

            return redirect()->route('roles.index')
                ->with('success', 'Rôle créé avec succès !'); // SweetAlert via JS
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du rôle.');
        }
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        try {
            $role->update($request->validated());

            return redirect()->route('roles.index')
                ->with('success', 'Rôle mis à jour avec succès !'); // SweetAlert
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du rôle.');
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return redirect()->route('roles.index')
                ->with('success', 'Rôle supprimé avec succès !'); // SweetAlert
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du rôle.');
        }
    }
}
