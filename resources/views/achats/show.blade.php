@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Détail achat</h1>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-4">
          <p class="text-muted mb-1">Fournisseur</p>
          <h6 class="fw-semibold">{{ $achat->fournisseur->nom ?? '�' }}</h6>
        </div>
        <div class="col-md-4">
          <p class="text-muted mb-1">Date</p>
          <h6 class="fw-semibold">{{ $achat->date_achat }}</h6>
        </div>
      <div class="col-md-4">
        <p class="text-muted mb-1">Référence</p>
        <h6 class="fw-semibold">{{ $achat->numero ?? '' }}</h6>
      </div>
      </div>

      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>TVA %</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($achat->lignes as $l)
          <tr>
            <td>{{ $l->produit->designation ?? '�' }}</td>
            <td>{{ $l->quantite }}</td>
            <td>{{ number_format($l->prix_unitaire, 0, ',', ' ') }}</td>
            <td>{{ $l->tva }}</td>
            <td>{{ number_format($l->total, 0, ',', ' ') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('achats.index') }}" class="btn btn-outline-secondary">Retour</a>
      </div>
    </div>
  </div>
</section>
@endsection
