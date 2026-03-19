@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Factures</h1>
    <p class="text-muted mb-0">Ventes et règlements.</p>
</div>

<section class="section fade-slide-up">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalCreateFacture">
            <i class="bi bi-receipt me-2"></i>Nouvelle facture
        </button>
    </div>

    <div class="card card-hover-lift">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0">Liste des factures</h5>
                <span class="text-muted small">Historique</span>
            </div>
            <table id="facturesTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Numéro</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Total TTC</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($factures as $f)
                    <tr>
                        <td class="fw-semibold">{{ $f->numero ?? ('FAC-'.$f->id) }}</td>
                        <td>{{ $f->client->nom ?? '—' }}</td>
                        <td>{{ $f->date_facture }}</td>
                        <td>{{ number_format($f->total_ttc ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <span class="badge {{ $f->statut === 'annule' ? 'bg-danger' : ($f->statut === 'payee' ? 'bg-success' : 'bg-warning') }}">
                                {{ ucfirst(str_replace('_',' ', $f->statut ?? '')) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('factures.show', $f->id) }}" class="btn btn-outline-secondary btn-sm" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('factures.edit', $f->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('factures.annuler', $f->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-outline-warning btn-sm" onclick="return confirm('Annuler cette facture ?');"><i class="bi bi-x-circle"></i></button>
                                </form>
                                <form action="{{ route('factures.destroy', $f->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer cette facture ?');"><i class="bi bi-trash"></i></button>
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

<!-- Modal création facture -->
<div class="modal fade" id="modalCreateFacture" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content glow-border">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle facture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('factures.store') }}" method="POST">
                @csrf
                <div class="modal-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Client</label>
                        <select name="client_id" class="form-select" required>
                            <option value="">Choisir</option>
                            @foreach($clients as $c)
                            <option value="{{ $c->id }}">{{ $c->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date facture</label>
                        <input type="date" name="date_facture" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Référence (optionnel)</label>
                        <input type="text" name="reference" class="form-control" placeholder="Ref interne/commande">
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
(function() {
    $('#facturesTable').DataTable({ pageLength: 10 });

    const produits = @json($produits);
    const defaultTva = 18;
    const tbody = $('#lignesFactureTable tbody');
    const subtotalText = $('#subtotalFactureText');
    const tvaText = $('#tvaFactureText');
    const ttcText = $('#ttcFactureText');
    let rowIndex = 0;

    function format(n) {
        return Number(n || 0).toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function optionHtml() {
        return produits.map(p => `<option value="${p.id}" data-prix="${p.prix_vente ?? 0}">${p.designation}</option>`).join('');
    }

    function addRow() {
        const i = rowIndex++;
        const row = $(`
        <tr>
            <td><select name="lignes[${i}][produit_id]" class="form-select select-produit" required>
                <option value="">Choisir</option>${optionHtml()}</select>
            </td>
            <td><input type="number" name="lignes[${i}][quantite]" min="1" class="form-control qty-field" value="1" required></td>
            <td><input type="number" step="0.01" name="lignes[${i}][prix_unitaire]" min="0" class="form-control prix-field" required></td>
            <td><input type="number" step="0.01" name="lignes[${i}][tva]" min="0" class="form-control tva-field" value="${defaultTva}" required></td>
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
        let subtotal = 0;
        let ttc = 0;
        tbody.find('tr').each(function() {
            const { ht, ttc: lttc } = recalcRow($(this));
            subtotal += ht;
            ttc += lttc;
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

    addRow();
    recalcAll();
})();
</script>
@endsection
