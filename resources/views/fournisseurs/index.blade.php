@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Fournisseurs</h1>
</div>

<section class="section fade-slide-up">
  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="card card-hover-lift">
        <div class="card-body d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted mb-1">Total fournisseurs</p>
            <h4 class="fw-bold">{{ $fournisseurs->count() }}</h4>
          </div>
          <span class="badge badge-soft rounded-pill">Réseau</span>
        </div>
      </div>
    </div>
    <div class="col-md-8 d-flex justify-content-end align-items-center">
      <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalCreateFournisseur">
        <i class="bi bi-person-plus me-2"></i>Ajouter un fournisseur
      </button>
    </div>
  </div>

  <div class="card card-hover-lift">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="card-title mb-0">Liste des fournisseurs</h5>
      </div>
      <table id="fournisseursTable" class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Adresse</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($fournisseurs as $f)
          <tr>
            <td class="fw-semibold">{{ $f->nom }}</td>
            <td>{{ $f->email ?? '�' }}</td>
            <td>{{ $f->telephone ?? '�' }}</td>
            <td>{{ $f->adresse ?? '�' }}</td>
            <td class="text-end">
              <div class="btn-group">
                <a href="{{ route('fournisseurs.edit', parameters: ['fournisseur' => $f->id]) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('fournisseurs.destroy', ['fournisseur' => $f->id]) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ce fournisseur ?');"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Modal creation fournisseur -->
<div class="modal fade" id="modalCreateFournisseur" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content glow-border">
      <div class="modal-header">
        <h5 class="modal-title">Nouveau fournisseur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('fournisseurs.store') }}" method="POST">
        @csrf
        <div class="modal-body row g-3">
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
  $('#fournisseursTable').DataTable({ pageLength: 10 });
});
</script>
@endsection
