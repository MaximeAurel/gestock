@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Produits</h1>
  <br>
</div>

<section class="section fade-slide-up">
  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <div class="card card-hover-lift">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <p class="text-muted mb-1">Total produits</p>
              <h4 class="fw-bold">{{ $produits->count() }}</h4>
            </div>
            <span class="badge badge-soft rounded-pill">Live</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card card-hover-lift">
        <div class="card-body">
          <p class="text-muted mb-1">Sous seuil (stock_min)</p>
          <h4 class="fw-bold text-warning">{{ $produits->where('stock_min','>',0)->count() }}</h4>
        </div>
      </div>
    </div>
    <div class="col-md-6 d-flex justify-content-end align-items-center">
      <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalCreateProduit">
        <i class="bi bi-plus-circle me-2"></i>Ajouter un produit
      </button>
    </div>
  </div>

  <div class="card card-hover-lift">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="card-title mb-0">Catalogue</h5>
      </div>

      <table id="produitsTable" class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Désignation</th>
            <th>Catégorie</th>
            <th>Unité</th>
            <th>Prix vente</th>
            <th>Quantité</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($produits as $produit)
          <tr>
            <td class="fw-semibold">{{ $produit->designation }}</td>
            <td>{{ $produit->categorie?->nom ?? '—' }}</td>
            <td>{{ $produit->unite?->nom ?? '—' }}</td>
            <td>{{ number_format($produit->prix_vente ?? 0, 0, ',', ' ') }} FCFA</td>
            <td><span class="badge bg-light text-dark">{{ $produit->stocks->sum('quantite') ?? 0 }}</span></td>
            <td class="text-end">
              <div class="btn-group">
                <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>
                <button class="btn btn-outline-danger btn-sm btn-delete" data-id="{{ $produit->id }}"><i class="bi bi-trash"></i></button>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Modal création produit -->
<div class="modal fade" id="modalCreateProduit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content glow-border">
      <div class="modal-header">
        <h5 class="modal-title">Nouveau produit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('produits.store') }}" method="POST">
        @csrf
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label class="form-label">Désignation</label>
            <input type="text" name="designation" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Prix vente</label>
            <input type="number" step="0.01" name="prix_vente" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Catégorie</label>
            <select name="categorie_id" class="form-select" required>
              <option value="">Choisir</option>
              @foreach($categories ?? [] as $c)
              <option value="{{ $c->id }}">{{ $c->nom }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Unité</label>
            <select name="unite_id" class="form-select" required>
              <option value="">Choisir</option>
              @foreach($unites ?? [] as $u)
              <option value="{{ $u->id }}">{{ $u->nom }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Quantité</label>
            <input type="number" name="quantite_initiale" min="0" class="form-control" value="0">
          </div>
          <input type="hidden" name="stock_min" value="1">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-gradient-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
$(function() {
  $('#produitsTable').DataTable({
    pageLength: 10
  });

  $('.btn-delete').on('click', function() {
    if(confirm('Supprimer ce produit ?')) {
      const id = $(this).data('id');
      $('<form>', { method: 'POST', action: `/produits/${id}` })
        .append('@csrf')
        .append('<input type="hidden" name="_method" value="DELETE">')
        .appendTo('body')
        .submit();
    }
  });
});
</script>
@endsection
