@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Modifier catégorie</h1>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift">
    <div class="card-body">
      <form action="{{ route('categories.update', ['category' => $categorie->id]) }}" method="POST" class="row g-3 pt-3">
        @csrf
        @method('PUT')
        <div class="col-md-6">
          <label class="form-label">Nom</label>
          <input type="text" name="nom" class="form-control" value="{{ $categorie->nom }}" required>
        </div>
        <div class="col-md-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3">{{ $categorie->description }}</textarea>
        </div>
        <div class="col-12 d-flex justify-content-end gap-2">
          <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Annuler</a>
          <button type="submit" class="btn btn-gradient-primary">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
