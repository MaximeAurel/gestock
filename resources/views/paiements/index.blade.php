@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Paiements</h1>
    <p class="text-muted mb-0">Encaissements sur factures.</p>
</div>

<section class="section fade-slide-up">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalCreatePaiement">
            <i class="bi bi-cash-coin me-2"></i>Nouveau paiement
        </button>
    </div>

    <div class="card card-hover-lift">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0">Liste des paiements</h5>
                <span class="text-muted small">Historique</span>
            </div>
            <table id="paiementsTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Facture</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Référence</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paiements as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->facture->numero ?? '—' }}</td>
                        <td>{{ $p->facture->client->nom ?? '—' }}</td>
                        <td>{{ $p->date_paiement }}</td>
                        <td>{{ number_format($p->montant, 0, ',', ' ') }} FCFA</td>
                        <td>{{ $p->mode_paiement }}</td>
                        <td>{{ $p->reference ?? '—' }}</td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('paiements.edit', $p->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('paiements.destroy', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ce paiement ?');">
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

<!-- Modal création paiement -->
<div class="modal fade" id="modalCreatePaiement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glow-border">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('paiements.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Facture</label>
                        <select name="facture_id" id="factureSelectModal" class="form-select" required>
                            <option value="">Choisir</option>
                            @foreach($factures as $f)
                            <option value="{{ $f->id }}" data-montant="{{ $f->reste_a_payer ?? $f->total_ttc ?? (($f->total_ht ?? 0)+($f->total_tva ?? 0)) }}">
                                {{ $f->numero }} - {{ $f->client->nom ?? '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date paiement</label>
                        <input type="date" name="date_paiement" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Montant</label>
                        <input type="number" step="0.01" name="montant" id="montantInputModal" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mode</label>
                        <select name="mode_paiement" id="modeSelectModal" class="form-select" required>
                            <option value="">Choisir</option>
                            <option value="Espece">Espèce</option>
                            <option value="Cheque">Chèque</option>
                            <option value="Virement">Virement</option>
                            <option value="Airtel Money">Airtel Money</option>
                            <option value="Moov Money">Moov Money</option>
                        </select>
                    </div>
                    <div class="col-md-4" id="referenceGroupModal">
                        <label class="form-label" id="referenceLabelModal">Référence</label>
                        <input type="text" name="reference" id="referenceInputModal" class="form-control" placeholder="Référence / numéro">
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
    $('#paiementsTable').DataTable({ pageLength: 10 });
});

(function() {
    const factureSelect = document.getElementById('factureSelectModal');
    const montantInput = document.getElementById('montantInputModal');
    const modeSelect = document.getElementById('modeSelectModal');
    const referenceGroup = document.getElementById('referenceGroupModal');
    const referenceLabel = document.getElementById('referenceLabelModal');
    const referenceInput = document.getElementById('referenceInputModal');

    const applyMontant = () => {
        const opt = factureSelect?.options[factureSelect.selectedIndex];
        const montant = opt ? Number(opt.dataset.montant ?? 0) : 0;
        if (montantInput) montantInput.value = Number.isFinite(montant) ? montant.toFixed(2) : '';
    };

    const toggleReference = () => {
        const mode = modeSelect.value;
        let required = false;
        let placeholder = 'Référence / numéro';
        if (mode === 'Cheque') {
            required = true;
            referenceLabel.textContent = 'Numéro de chèque';
            placeholder = 'Ex: 123456';
        } else if (mode === 'Airtel Money' || mode === 'Moov Money') {
            required = true;
            referenceLabel.textContent = 'Numéro Mobile Money';
            placeholder = 'Ex: 07 xx xx xx xx';
        } else {
            referenceLabel.textContent = 'Référence';
        }
        referenceInput.placeholder = placeholder;
        referenceInput.required = required;
        referenceGroup.style.display = mode ? 'block' : 'none';
    };

    factureSelect?.addEventListener('change', applyMontant);
    modeSelect?.addEventListener('change', toggleReference);

    if (factureSelect?.value) applyMontant();
    toggleReference();
})();
</script>
@endsection
