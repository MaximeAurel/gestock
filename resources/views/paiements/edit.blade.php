@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Modifier paiement</h1>
</div>

<section class="section fade-slide-up">
    <div class="card card-hover-lift">
        <div class="card-body">
            <form action="{{ route('paiements.update', $paiement->id) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Facture</label>
                    <select name="facture_id" id="factureSelect" class="form-select" required>
                        @foreach($factures as $f)
                        <option value="{{ $f->id }}" data-montant="{{ $f->reste_a_payer ?? $f->total_ttc ?? (($f->total_ht ?? 0)+($f->total_tva ?? 0)) }}"
                            @selected($paiement->facture_id == $f->id)>
                            {{ $f->numero }} - {{ $f->client->nom ?? '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date paiement</label>
                    <input type="date" name="date_paiement" class="form-control" value="{{ $paiement->date_paiement }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Montant</label>
                    <input type="number" step="0.01" name="montant" id="montantInput" class="form-control" value="{{ $paiement->montant }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mode</label>
                    <select name="mode_paiement" id="modeSelect" class="form-select" required>
                        <option value="">Choisir</option>
                        <option value="Espece" @selected($paiement->mode_paiement === 'Espece')>Espèce</option>
                        <option value="Cheque" @selected($paiement->mode_paiement === 'Cheque')>Chèque</option>
                        <option value="Virement" @selected($paiement->mode_paiement === 'Virement')>Virement</option>
                        <option value="Airtel Money" @selected($paiement->mode_paiement === 'Airtel Money')>Airtel Money</option>
                        <option value="Moov Money" @selected($paiement->mode_paiement === 'Moov Money')>Moov Money</option>
                    </select>
                </div>
                <div class="col-md-4" id="referenceGroup">
                    <label class="form-label" id="referenceLabel">Référence</label>
                    <input type="text" name="reference" id="referenceInput" class="form-control" value="{{ $paiement->reference }}" placeholder="Référence / numéro">
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('paiements.index') }}" class="btn btn-outline-secondary">Retour</a>
                    <button type="submit" class="btn btn-gradient-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
(function() {
    const factureSelect = document.getElementById('factureSelect');
    const montantInput = document.getElementById('montantInput');
    const modeSelect = document.getElementById('modeSelect');
    const referenceGroup = document.getElementById('referenceGroup');
    const referenceLabel = document.getElementById('referenceLabel');
    const referenceInput = document.getElementById('referenceInput');

    const applyMontant = () => {
        const opt = factureSelect?.options[factureSelect.selectedIndex];
        const montant = opt ? Number(opt.dataset.montant ?? 0) : 0;
        if (montantInput && !montantInput.value) {
            montantInput.value = Number.isFinite(montant) ? montant.toFixed(2) : '';
        }
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

    applyMontant();
    toggleReference();
})();
</script>
@endsection
