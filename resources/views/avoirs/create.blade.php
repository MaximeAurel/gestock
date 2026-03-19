@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Nouvel avoir</h1>
</div>

<section class="section fade-slide-up">
    <div class="card card-hover-lift">
        <div class="card-body">
            <form action="{{ route('avoirs.store') }}" method="POST" class="row g-3">
                @csrf

                @php
                    $role = strtolower(Auth::user()->role->nom ?? '');
                    $isAdmin = in_array($role, ['admin', 'administrateur'], true);
                @endphp

                <div class="col-md-4">
                    <label class="form-label">Numéro (optionnel)</label>
                    <input type="text" name="numero" class="form-control" placeholder="Auto si vide">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Facture</label>
                    <select name="facture_id" id="factureSelect" class="form-select" required>
                        <option value="">Choisir</option>
                        @foreach($factures as $f)
                        <option value="{{ $f->id }}"
                            data-montant="{{ $f->reste_a_payer ?? $f->total_ttc ?? (($f->total_ht ?? 0) + ($f->total_tva ?? 0)) }}"
                            @selected(old('facture_id') == $f->id)>
                            {{ $f->numero }} - {{ $f->client->nom ?? '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" name="date_avoir" class="form-control" value="{{ now()->toDateString() }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Montant</label>
                    <input type="number" step="0.01" name="montant" id="montantInput" class="form-control" @unless($isAdmin) readonly @endunless required>
                    @unless($isAdmin)
                    <small class="text-muted">Montant verrouillé (réservé à l'administrateur).</small>
                    @endunless
                </div>
                <div class="col-md-8">
                    <label class="form-label">Motif</label>
                    <input type="text" name="motif" class="form-control" placeholder="Optionnel">
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('avoirs.index') }}" class="btn btn-outline-secondary">Annuler</a>
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
    const select = document.getElementById('factureSelect');
    const montantInput = document.getElementById('montantInput');
    if (!select || !montantInput) return;

    const applyMontant = () => {
        const option = select.options[select.selectedIndex];
        const montant = option ? Number(option.dataset.montant ?? 0) : 0;
        montantInput.value = Number.isFinite(montant) ? montant.toFixed(2) : '';
    };

    select.addEventListener('change', applyMontant);

    // Applique dès le chargement si une facture est déjà sélectionnée (old input)
    if (select.value) applyMontant();
})();
</script>
@endsection
