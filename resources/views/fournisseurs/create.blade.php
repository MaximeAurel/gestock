@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Nouveau fournisseur</h1>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift">
    <div class="card-body">
      <form action="{{ route('fournisseurs.store') }}" method="POST" class="row g-3 pt-3">
        @csrf
        <div class="col-md-6">
          <label class="form-label">Nom</label>
          <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" placeholder="optionnel">
        </div>
        <div class="col-md-6">
          <label class="form-label">Téléphone</label>
          <input type="text" name="telephone" class="form-control" placeholder="optionnel">
        </div>
        <div class="col-md-6">
          <label class="form-label">Adresse</label>
          <input type="text" name="adresse" class="form-control" placeholder="optionnel">
        </div>
        <div class="col-12">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-control" rows="3" placeholder="Optionnel"></textarea>
        </div>
        <div class="col-12 d-flex justify-content-end gap-2">
          <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary">Annuler</a>
          <button type="submit" class="btn btn-gradient-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
