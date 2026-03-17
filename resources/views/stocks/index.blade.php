@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Stocks</h1>
  <p class="text-muted mb-0">Entrees, sorties, alertes et historiques.</p>
</div>

<section class="section fade-slide-up">
  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="card card-hover-lift">
        <div class="card-body d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted mb-1">Produits suivis</p>
            <h4 class="fw-bold">{{ $produitsCount }}</h4>
          </div>
          <span class="badge badge-soft rounded-pill">Stock</span>
        </div>
      </div>
    </div>
    <div class="col-md-8 d-flex justify-content-end gap-2 align-items-center">
      <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalEntree">
        <i class="bi bi-box-arrow-in-down me-2"></i>Entree stock
      </button>
      <a href="{{ route('mouvement-stocks.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-journal-text me-2"></i>Journal complet
      </a>
    </div>
  </div>

  <div class="card card-hover-lift mb-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="card-title mb-0">État des stocks</h5>
        <span class="text-muted small">Quantités actuelles</span>
      </div>
      <table id="stocksTable" class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Stock min</th>
            <th>Emplacement</th>
            <th class="text-end">Statut</th>
          </tr>
        </thead>
        <tbody>
          @foreach($produits as $produit)
          @php
            $min = $produit->stock_min ?? 0;
            $qte = $produit->quantite ?? 0;
            $alert = $qte <= $min;
            $emplacements = $produit->stocks->pluck('emplacement')->filter()->unique()->implode(', ');
          @endphp
          <tr>
            <td class="fw-semibold">{{ $produit->designation ?? '—' }}</td>
            <td><span class="badge bg-light text-dark">{{ $qte }}</span></td>
            <td>{{ $min }}</td>
            <td>{{ $emplacements ?: 'principal' }}</td>
            <td class="text-end">
              @if($alert)
                <span class="badge bg-danger-subtle text-danger">Bas</span>
              @else
                <span class="badge bg-success-subtle text-success">OK</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</section>

<!-- Modal Entree -->
<div class="modal fade" id="modalEntree" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content glow-border">
      <div class="modal-header">
        <h5 class="modal-title">Entree de stock</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('stocks.entree') }}" method="POST">
        @csrf
        <div class="modal-body row g-3">
          <div class="col-12">
            <label class="form-label">Produit</label>
            <select name="produit_id" class="form-select" required>
              <option value="">Choisir</option>
              @foreach(\App\Models\Produit::orderBy('designation')->get() as $p)
              <option value="{{ $p->id }}">{{ $p->designation }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6">
            <label class="form-label">Quantite</label>
            <input type="number" name="quantite" min="1" class="form-control" required>
          </div>
          <div class="col-6">
            <label class="form-label">Motif</label>
            <input type="text" name="motif" class="form-control" placeholder="Optionnel">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-gradient-primary">Valider l'entree</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Sortie -->
{{-- Modal Sortie retiré sur demande --}}
@endsection

@section('scripts')
<script>
$(function() {
  $('#stocksTable').DataTable({ pageLength: 10 });
});
</script>
@endsection


