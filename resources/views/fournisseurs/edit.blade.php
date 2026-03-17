@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Modifier fournisseur</h1>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift">
    <div class="card-body">
      <form action="{{ route('fournisseurs.update', ['fournisseur' => $fournisseur->id]) }}" method="POST" class="row g-3 pt-3">
        @csrf
        @method('PUT')
        <div class="col-md-6">
          <label class="form-label">Nom</label>
          <input type="text" name="nom" class="form-control" value="{{ $fournisseur->nom }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ $fournisseur->email }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Téléphone</label>
          <input type="text" name="telephone" class="form-control" value="{{ $fournisseur->telephone }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Adresse</label>
          <input type="text" name="adresse" class="form-control" value="{{ $fournisseur->adresse }}">
        </div>
        <div class="col-12">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-control" rows="3">{{ $fournisseur->notes }}</textarea>
        </div>
        <div class="col-12 d-flex justify-content-end gap-2">
          <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary">Annuler</a>
          <button type="submit" class="btn btn-gradient-primary">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
