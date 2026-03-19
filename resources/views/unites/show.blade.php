@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Unité</h1>
    <p class="text-muted mb-0">Détails de l’unité de mesure.</p>
</div>

<section class="section fade-slide-up">
    <div class="card card-hover-lift">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ $unite->nom }}</h5>
                <a href="{{ route('unites.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-short me-1"></i>Retour
                </a>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-light">
                        <div class="text-muted small">Nom</div>
                        <div class="fs-5 fw-semibold">{{ $unite->nom }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-light">
                        <div class="text-muted small">Abréviation</div>
                        <div class="fs-5 fw-semibold">{{ $unite->abr }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded border bg-light">
                        <div class="text-muted small">Produits utilisant cette unité</div>
                        <div class="fs-5 fw-semibold">{{ $unite->produits_count ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
