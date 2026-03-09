@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Mon profil</h1>
</div>

<section class="section profile">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                    <h2>{{ $user->nom }}</h2>
                    <h3>{{ $user->role->nom ?? 'Utilisateur' }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body pt-3">
                    <h5 class="card-title">Informations du compte</h5>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label">Nom</div>
                        <div class="col-lg-9 col-md-8">{{ $user->nom }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label">Email</div>
                        <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label">Role</div>
                        <div class="col-lg-9 col-md-8">{{ $user->role->nom ?? 'Non defini' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-3 col-md-4 label">Statut</div>
                        <div class="col-lg-9 col-md-8">{{ $user->statut ? 'Actif' : 'Inactif' }}</div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Retour dashboard</a>
                        @if(in_array(strtolower(trim(auth()->user()?->role?->nom ?? '')), ['admin', 'administrateur'], true))
                            <a href="{{ route('users.index') }}" class="btn btn-primary">Gestion utilisateurs</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
