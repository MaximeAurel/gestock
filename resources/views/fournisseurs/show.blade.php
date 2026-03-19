@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Fournisseur</h1>
    <p class="text-muted mb-0">Fiche détaillée du fournisseur.</p>
</div>

<section class="section fade-slide-up">
    <div class="card card-hover-lift">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ $fournisseur->nom }}</h5>
                <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-short me-1"></i>Retour
                </a>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-light">
                        <div class="text-muted small">Nom</div>
                        <div class="fs-5 fw-semibold">{{ $fournisseur->nom }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-light">
                        <div class="text-muted small">Email</div>
                        <div class="fs-6">{{ $fournisseur->email ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-light">
                        <div class="text-muted small">Téléphone</div>
                        <div class="fs-6">{{ $fournisseur->telephone ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-light">
                        <div class="text-muted small">Adresse</div>
                        <div class="fs-6">{{ $fournisseur->adresse ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded border bg-light">
                        <div class="text-muted small">Produits fournis</div>
                        <div class="fs-5 fw-semibold">{{ $fournisseur->produits_count ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded border bg-light">
                        <div class="text-muted small">Achats réalisés</div>
                        <div class="fs-5 fw-semibold">{{ $fournisseur->achats_count ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
