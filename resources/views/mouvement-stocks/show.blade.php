@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Détail mouvement</h1>
    <p class="text-muted mb-0">Informations sur l'opération de stock.</p>
</div>

<section class="section fade-slide-up">
    <div class="card card-hover-lift">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="text-muted mb-1">Produit</p>
                    <h5 class="fw-semibold">{{ $mouvementStock->produit->designation ?? '—' }}</h5>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Type</p>
                    @if($mouvementStock->type === 'entree')
                        <span class="badge bg-success">Entrée</span>
                    @else
                        <span class="badge bg-danger">Sortie</span>
                    @endif
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Quantité</p>
                    <span class="badge bg-light text-dark">{{ $mouvementStock->quantite }}</span>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1">Motif</p>
                    <p class="mb-0">{{ $mouvementStock->motif ?? '—' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Créé le</p>
                    <p class="mb-0">{{ $mouvementStock->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="col-md-3">
                    <p class="text-muted mb-1">Mis à jour</p>
                    <p class="mb-0">{{ $mouvementStock->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('mouvement-stocks.index') }}" class="btn btn-outline-secondary">Retour</a>
            </div>
        </div>
    </div>
</section>
@endsection
