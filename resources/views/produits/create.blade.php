@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Nouveau produit</h1>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift">
    <div class="card-body">
      <form action="{{ route('produits.store') }}" method="POST" class="row g-3 pt-3">
        @csrf
        <div class="col-md-6">
          <label class="form-label">Désignation</label>
          <input type="text" name="designation" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Prix de vente</label>
          <input type="number" step="0.01" name="prix_vente" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Catégorie</label>
          <select name="categorie_id" class="form-select" required>
            <option value="">Choisir</option>
            @foreach($categories as $c)
            <option value="{{ $c->id }}">{{ $c->nom }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Unité</label>
          <select name="unite_id" class="form-select" required>
            <option value="">Choisir</option>
            @foreach($unites as $u)
            <option value="{{ $u->id }}">{{ $u->nom }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Quantité</label>
          <input type="number" name="quantite_initiale" min="0" value="0" class="form-control">
        </div>
        <input type="hidden" name="stock_min" value="1">
        <div class="col-12 d-flex justify-content-end gap-2">
          <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">Annuler</a>
          <button type="submit" class="btn btn-gradient-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
