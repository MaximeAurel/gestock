@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Achats</h1>
    <p class="text-muted mb-0">Enregistrements fournisseurs + impact stock.</p>
</div>

<section class="section fade-slide-up">
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card card-hover-lift">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Total achats</p>
                        <h4 class="fw-bold">{{ $achats->count() }}</h4>
                    </div>
                    <span class="badge badge-soft rounded-pill">Fournisseurs</span>
                </div>
            </div>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center gap-2">
            <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalCreateAchat">
                <i class="bi bi-bag-plus me-2"></i>Nouvel achat
            </button>
        </div>
    </div>

    <div class="card card-hover-lift">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0">Liste des achats</h5>
                <span class="text-muted small">Historique</span>
            </div>
            <table id="achatsTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Référence</th>
                        <th>Fournisseur</th>
                        <th>Date</th>
                        <th>Total TTC</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($achats as $achat)
                    <tr>
                        <td class="fw-semibold">{{ $achat->numero }}</td>
                        <td>{{ $achat->fournisseur->nom ?? '-' }}</td>
                        <td>{{ $achat->date_achat }}</td>
                        <td>{{ number_format($achat->total_ttc ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <span class="badge {{ $achat->statut === 'annule' ? 'bg-danger' : 'bg-success' }}">
                                {{ ucfirst($achat->statut) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('achats.edit', $achat->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('achats.annuler', $achat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-warning btn-sm" onclick="return confirm('Annuler cet achat ?');">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                <form action="{{ route('achats.destroy', $achat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer cet achat ?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
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

<!-- Modal creation achat -->
<div class="modal fade" id="modalCreateAchat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content glow-border">
            <div class="modal-header">
                <h5 class="modal-title">Nouvel achat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('achats.store') }}" method="POST">
                @csrf
                <div class="modal-body row g-3">
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
                        <label class="form-label">Référence</label>
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
                                    <th>Total ligne</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="addLigne">
                            <i class="bi bi-plus"></i> Ajouter ligne
                        </button>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-3 align-items-center mt-2">
                            <div class="text-end">
                                <p class="mb-1 text-muted">Sous-total</p>
                                <h6 class="fw-bold" id="subtotalText">0</h6>
                            </div>
                            <div class="text-end">
                                <p class="mb-1 text-muted">TVA</p>
                                <h6 class="fw-bold" id="tvaText">0</h6>
                            </div>
                            <div class="text-end">
                                <p class="mb-1 text-muted">Total TTC</p>
                                <h5 class="fw-bold text-primary" id="ttcText">0</h5>
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
    $('#achatsTable').DataTable({ pageLength: 10 });

    const produits = @json($produits);
    const defaultTva = 19.25;
    const tbody = $('#lignesTable tbody');
    const subtotalText = $('#subtotalText');
    const tvaText = $('#tvaText');
    const ttcText = $('#ttcText');
    let rowIndex = 0; // Garantit des noms de champs groupés pour chaque ligne

    function format(number) {
        return Number(number || 0).toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function optionHtml() {
        return produits.map(p => `<option value="${p.id}" data-prix="${p.prix_vente ?? 0}">${p.designation}</option>`).join('');
    }

    function addRow() {
        const index = rowIndex++;
        const row = $(`
        <tr>
            <td><select name="lignes[${index}][produit_id]" class="form-select select-produit" required>
                <option value="">Choisir</option>${optionHtml()}</select>
            </td>
            <td><input type="number" name="lignes[${index}][quantite]" min="1" class="form-control qty-field" value="1" required></td>
            <td><input type="number" step="0.01" name="lignes[${index}][prix_unitaire]" min="0" class="form-control prix-field" required></td>
            <td><input type="number" step="0.01" name="lignes[${index}][tva]" min="0" class="form-control tva-field" value="${defaultTva}"></td>
            <td class="fw-semibold line-total">0</td>
            <td><button type="button" class="btn btn-outline-danger btn-sm btn-remove"><i class="bi bi-x"></i></button></td>
        </tr>`);
        tbody.append(row);
    }

    function recalcRow(row) {
        const qty = parseFloat(row.find('.qty-field').val() || 0);
        const price = parseFloat(row.find('.prix-field').val() || 0);
        const tva = parseFloat(row.find('.tva-field').val() || defaultTva);
        const totalHt = qty * price;
        const totalTtc = totalHt * (1 + tva / 100);
        row.find('.line-total').text(format(totalTtc));
        return { totalHt, totalTtc };
    }

    function recalcAll() {
        let subtotal = 0;
        let ttc = 0;
        tbody.find('tr').each(function() {
            const { totalHt, totalTtc } = recalcRow($(this));
            subtotal += totalHt;
            ttc += totalTtc;
        });
        const tva = ttc - subtotal;
        subtotalText.text(format(subtotal));
        tvaText.text(format(tva));
        ttcText.text(format(ttc));
    }

    $('#addLigne').on('click', function() { addRow(); recalcAll(); });
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
