@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Stock produit</h1>
  <p class="text-muted mb-0">Historique cible sur {{ $produit->designation }}.</p>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="card-title mb-0">Mouvements</h5>
        <span class="text-muted small">{{ $mouvements->count() }} enregistrements</span>
      </div>
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Type</th>
            <th>Quantite</th>
            <th>Motif</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @foreach($mouvements as $mv)
          <tr>
            <td>
              @if($mv->type === 'entree')
                <span class="badge bg-success">Entree</span>
              @else
                <span class="badge bg-danger">Sortie</span>
              @endif
            </td>
            <td><span class="badge bg-light text-dark">{{ $mv->quantite }}</span></td>
            <td>{{ $mv->motif ?? '—' }}</td>
            <td>{{ $mv->created_at->format('d/m/Y H:i') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="d-flex justify-content-end">
        <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">Retour</a>
      </div>
    </div>
  </div>
</section>
@endsection
