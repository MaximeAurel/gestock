@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Unités</h1>
</div>

<section class="section fade-slide-up">
  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="card card-hover-lift">
        <div class="card-body d-flex justify-content-between align-items-start">
          <div>
            <p class="text-muted mb-1">Total unités</p>
            <h4 class="fw-bold">{{ $unites->count() }}</h4>
          </div>
          <span class="badge badge-soft rounded-pill">Catalogue</span>
        </div>
      </div>
    </div>
    <div class="col-md-8 d-flex justify-content-end align-items-center">
      <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalCreateUnite">
        <i class="bi bi-plus-circle me-2"></i>Ajouter une unitée
      </button>
    </div>
  </div>

  <div class="card card-hover-lift">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="card-title mb-0">Liste des unités</h5>
        <span class="text-muted small">Abréviations et libellés</span>
      </div>
      <table id="unitesTable" class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Nom</th>
            <th>Abréviation</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($unites as $unite)
          <tr>
            <td class="fw-semibold">{{ $unite->nom }}</td>
            <td><span class="badge bg-light text-dark">{{ $unite->abr }}</span></td>
            <td class="text-end">
              <div class="btn-group">
                <a href="{{ route('unites.edit', ['unite' => $unite->id]) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>
                <button class="btn btn-outline-danger btn-sm btn-delete" data-id="{{ $unite->id }}"><i class="bi bi-trash"></i></button>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Modal cr�ation unit� -->
<div class="modal fade" id="modalCreateUnite" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content glow-border">
      <div class="modal-header">
        <h5 class="modal-title">Nouvelle unitée</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('unites.store') }}" method="POST">
        @csrf
        <div class="modal-body row g-3">
          <div class="col-12">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" required>
          </div>
          <div class="col-12">
            <label class="form-label">Abréviation</label>
            <input type="text" name="abr" class="form-control" maxlength="10" required>
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
  $('#unitesTable').DataTable({ pageLength: 10 });

  $('.btn-delete').on('click', function() {
    const id = $(this).data('id');
    Swal.fire({
      title: 'Supprimer cette unité ?',
      text: 'Action irréversible',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#1d4ed8',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Oui, supprimer'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = $('<form>', { method: 'POST', action: '/unites/' + id })
          .append('@csrf')
          .append('@method("DELETE")');
        $('body').append(form);
        form.submit();
      }
    });
  });
});
</script>
@endsection
