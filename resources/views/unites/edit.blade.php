@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Modifier unité</h1>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift">
    <div class="card-body">
      <form action="{{ route('unites.update', ['unite' => $unite->id]) }}" method="POST" class="row g-3 pt-3">
        @csrf
        @method('PUT')
        <div class="col-md-6">
          <label class="form-label">Nom</label>
          <input type="text" name="nom" class="form-control" value="{{ $unite->nom }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Abréviation</label>
          <input type="text" name="abr" class="form-control" value="{{ $unite->abr }}" maxlength="10" required>
        </div>
        <div class="col-12 d-flex justify-content-end gap-2">
          <a href="{{ route('unites.index') }}" class="btn btn-outline-secondary">Annuler</a>
          <button type="submit" class="btn btn-gradient-primary">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
