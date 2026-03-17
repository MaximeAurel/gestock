@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Modifier achat</h1>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift">
    <div class="card-body">
      <form action="{{ route('achats.update', ['achat' => $achat->id]) }}" method="POST" class="row g-3 pt-3">
        @csrf
        @method('PUT')
        <div class="col-md-4">
          <label class="form-label">Fournisseur</label>
          <select name="fournisseur_id" class="form-select" required>
            @foreach($fournisseurs as $f)
              <option value="{{ $f->id }}" @selected($achat->fournisseur_id == $f->id)>{{ $f->nom }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Date achat</label>
          <input type="date" name="date_achat" class="form-control" value="{{ $achat->date_achat }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Numéro</label>
          <input type="text" name="numero" class="form-control" value="{{ $achat->numero }}">
        </div>

        <div class="col-12">
          <h6 class="mt-2">Lignes (Modification simple)</h6>
          <p class="text-muted small">Pour une mise à jour fine des lignes, annuler puis recrer l'achat.</p>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2">
          <a href="{{ route('achats.index') }}" class="btn btn-outline-secondary">Annuler</a>
          <button type="submit" class="btn btn-gradient-primary">Mettre � jour</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
