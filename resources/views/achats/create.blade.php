@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
  <h1 class="fw-bold text-primary">Nouvel achat</h1>
</div>

<section class="section fade-slide-up">
  <div class="card card-hover-lift">
    <div class="card-body">
      <form action="{{ route('achats.store') }}" method="POST" class="row g-3 pt-3">
        @csrf
        <div class="col-md-4">
          <label class="form-label">Fournisseur</label>
          <select name="fournisseur_id" class="form-select" required>
            <option value="">Choisir</option>
            @foreach($fournisseurs as $f)
              <option value="{{ $f->id }}">{{ $f->nom }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Date achat</label>
          <input type="date" name="date_achat" class="form-control" value="{{ now()->toDateString() }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Numéro</label>
          <input type="text" name="numero" class="form-control" placeholder="Optionnel">
        </div>

        <div class="col-12">
          <h6 class="mt-2">Lignes</h6>
          <table class="table align-middle" id="lignesTable">
            <thead class="table-light">
              <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>TVA %</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
          <button type="button" class="btn btn-outline-primary btn-sm" id="addLigne"><i class="bi bi-plus"></i> Ajouter ligne</button>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2">
          <a href="{{ route('achats.index') }}" class="btn btn-outline-secondary">Annuler</a>
          <button type="submit" class="btn btn-gradient-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
(function() {
  const produits = @json(\App\Models\Produit::select('id','designation')->orderBy('designation')->get());
  const tbody = $('#lignesTable tbody');

  function optionHtml() {
    return produits.map(p => `<option value="${p.id}">${p.designation}</option>`).join('');
  }

  function addRow() {
    const row = $(`
      <tr>
        <td><select name="lignes[][produit_id]" class="form-select" required><option value="">Choisir</option>${optionHtml()}</select></td>
        <td><input type="number" name="lignes[][quantite]" min="1" class="form-control" required></td>
        <td><input type="number" step="0.01" name="lignes[][prix_unitaire]" min="0" class="form-control" required></td>
        <td><input type="number" step="0.01" name="lignes[][tva]" min="0" value="19.25" class="form-control"></td>
        <td><button type="button" class="btn btn-outline-danger btn-sm btn-remove"><i class="bi bi-x"></i></button></td>
      </tr>`);
    tbody.append(row);
  }

  $('#addLigne').on('click', addRow);
  tbody.on('click', '.btn-remove', function() {
    $(this).closest('tr').remove();
  });

  addRow();
})();
</script>
@endsection
