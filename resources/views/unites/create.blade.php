@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Nouvelle unité</h1>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift">
    <div class="card-body">
      <form action="{{ route('unites.store') }}" method="POST" class="row g-3 pt-3">
        @csrf
        <div class="col-md-6">
          <label class="form-label">Nom</label>
          <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Abréviation</label>
          <input type="text" name="abr" class="form-control" maxlength="10" required>
        </div>
        <div class="col-12 d-flex justify-content-end gap-2">
          <a href="{{ route('unites.index') }}" class="btn btn-outline-secondary">Annuler</a>
          <button type="submit" class="btn btn-gradient-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
