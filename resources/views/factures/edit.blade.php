@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Modifier la facture</h1>
    <p class="text-muted mb-0">#{{ $facture->numero ?? ('FAC-'.$facture->id) }}</p>
</div>

<section class="section fade-slide-up">
    <div class="card card-hover-lift">
        <div class="card-body">
            <form action="{{ route('factures.update', $facture->id) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-4">
                    <label class="form-label">Client</label>
                    <select name="client_id" class="form-select" required>
                        <option value="">Choisir</option>
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}" @selected($facture->client_id == $c->id)>{{ $c->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date facture</label>
                    <input type="date" name="date_facture" class="form-control"
                        value="{{ \Illuminate\Support\Carbon::parse($facture->date_facture ?? now())->toDateString() }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Référence (optionnel)</label>
                    <input type="text" name="reference" class="form-control" value="{{ $facture->reference }}">
                </div>

                <div class="col-12">
                    <h6 class="mt-2">Lignes</h6>
                    <table class="table align-middle" id="lignesFactureTable">
                        <thead class="table-light">
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>TVA %</th>
                                <th>Total ligne</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addLigneFacture">
                        <i class="bi bi-plus"></i> Ajouter ligne
                    </button>
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-end gap-3 align-items-center mt-2">
                        <div class="text-end">
                            <p class="mb-1 text-muted">Sous-total</p>
                            <h6 class="fw-bold" id="subtotalFactureText">0</h6>
                        </div>
                        <div class="text-end">
                            <p class="mb-1 text-muted">TVA</p>
                            <h6 class="fw-bold" id="tvaFactureText">0</h6>
                        </div>
                        <div class="text-end">
                            <p class="mb-1 text-muted">Total TTC</p>
                            <h5 class="fw-bold text-primary" id="ttcFactureText">0</h5>
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('factures.index') }}" class="btn btn-outline-secondary">Annuler</a>
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
    const produits = @json($produits);
    const existingLignes = @json($lignes);
    const defaultTva = 18;
    const tbody = $('#lignesFactureTable tbody');
    const subtotalText = $('#subtotalFactureText');
    const tvaText = $('#tvaFactureText');
    const ttcText = $('#ttcFactureText');
    let rowIndex = 0;

    function format(n) {
        return Number(n || 0).toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function optionHtml(selectedId = null) {
        return produits.map(p => `<option value="${p.id}" data-prix="${p.prix_vente ?? 0}" ${selectedId == p.id ? 'selected' : ''}>${p.designation}</option>`).join('');
    }

    function addRow(data = {}) {
        const i = rowIndex++;
        const produitId = data.produit_id ?? '';
        const qty = data.quantite ?? 1;
        const prix = data.prix_unitaire ?? 0;
        const tva = data.tva ?? defaultTva;

        const row = $(`
        <tr>
            <td><select name="lignes[${i}][produit_id]" class="form-select select-produit" required>
                <option value="">Choisir</option>${optionHtml(produitId)}</select>
            </td>
            <td><input type="number" name="lignes[${i}][quantite]" min="1" class="form-control qty-field" value="${qty}" required></td>
            <td><input type="number" step="0.01" name="lignes[${i}][prix_unitaire]" min="0" class="form-control prix-field" value="${prix}" required></td>
            <td><input type="number" step="0.01" name="lignes[${i}][tva]" min="0" class="form-control tva-field" value="${tva}" required></td>
            <td class="fw-semibold line-total">0</td>
            <td><button type="button" class="btn btn-outline-danger btn-sm btn-remove"><i class="bi bi-x"></i></button></td>
        </tr>`);
        tbody.append(row);
    }

    function recalcRow(row) {
        const qty = parseFloat(row.find('.qty-field').val() || 0);
        const price = parseFloat(row.find('.prix-field').val() || 0);
        const tva = parseFloat(row.find('.tva-field').val() || defaultTva);
        const ht = qty * price;
        const ttc = ht * (1 + tva / 100);
        row.find('.line-total').text(format(ttc));
        return { ht, ttc };
    }

    function recalcAll() {
        let subtotal = 0, ttc = 0;
        tbody.find('tr').each(function() {
            const { ht, ttc: lttc } = recalcRow($(this));
            subtotal += ht; ttc += lttc;
        });
        const tva = ttc - subtotal;
        subtotalText.text(format(subtotal));
        tvaText.text(format(tva));
        ttcText.text(format(ttc));
    }

    $('#addLigneFacture').on('click', function() { addRow(); recalcAll(); });
    tbody.on('click', '.btn-remove', function() { $(this).closest('tr').remove(); recalcAll(); });
    tbody.on('change keyup', '.qty-field, .prix-field, .tva-field', recalcAll);
    tbody.on('change', '.select-produit', function() {
        const prix = $(this).find(':selected').data('prix') ?? 0;
        const row = $(this).closest('tr');
        row.find('.prix-field').val(prix);
        row.find('.tva-field').val(defaultTva);
        recalcAll();
    });

    if (existingLignes.length) {
        existingLignes.forEach(l => addRow(l));
    } else {
        addRow();
    }
    recalcAll();
})();
</script>
@endsection
