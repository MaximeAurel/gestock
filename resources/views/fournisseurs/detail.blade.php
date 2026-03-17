@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Détail fournisseur</h1>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift mb-3">
    <div class="card-body">
      <div class="row mb-2">
        <div class="col-md-3"><p class="text-muted mb-1">Nom</p><h6 class="fw-semibold">{{ $fournisseur->nom }}</h6></div>
        <div class="col-md-3"><p class="text-muted mb-1">Email</p><h6 class="fw-semibold">{{ $fournisseur->email ?? '' }}</h6></div>
        <div class="col-md-3"><p class="text-muted mb-1">Téléphone</p><h6 class="fw-semibold">{{ $fournisseur->telephone ?? '' }}</h6></div>
        <div class="col-md-3"><p class="text-muted mb-1">Adresse</p><h6 class="fw-semibold">{{ $fournisseur->adresse ?? '' }}</h6></div>
      </div>
      <p class="text-muted mb-1">Notes</p>
      <p class="mb-0">{{ $fournisseur->notes ?? '' }}</p>
    </div>
  </div>

  <div class="card card-hover-lift">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="card-title mb-0">Achats liés</h5>
        <span class="text-muted small">{{ $achats->count() }} achats</span>
      </div>
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Référence</th>
            <th>Date</th>
            <th>Total TTC</th>
            <th>Statut</th>
          </tr>
        </thead>
        <tbody>
          @foreach($achats as $achat)
          <tr>
            <td>{{ $achat->reference ?? '' }}</td>
            <td>{{ $achat->date_achat }}</td>
            <td>{{ number_format($achat->total_ttc ?? 0, 0, ',', ' ') }} FCFA</td>
            <td><span class="badge {{ $achat->statut === 'annule' ? 'bg-danger' : 'bg-success' }}">{{ ucfirst($achat->statut ?? 'valide') }}</span></td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="d-flex justify-content-end">
        <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary">Retour</a>
      </div>
    </div>
  </div>
</section>
@endsection
