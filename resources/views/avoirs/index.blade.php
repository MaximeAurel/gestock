@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Avoirs</h1>
    <p class="text-muted mb-0">Crédits accordés sur factures.</p>
</div>

<section class="section fade-slide-up">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalCreateAvoir">
            <i class="bi bi-receipt-cutoff me-2"></i>Nouvel avoir
        </button>
    </div>

    <div class="card card-hover-lift">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0">Liste des avoirs</h5>
                <span class="text-muted small">Historique</span>
            </div>
            <table id="avoirsTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Numéro</th>
                        <th>Facture</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Motif</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($avoirs as $a)
                    <tr>
                        <td class="fw-semibold">{{ $a->numero }}</td>
                        <td>{{ $a->facture->numero ?? '—' }}</td>
                        <td>{{ $a->facture->client->nom ?? '—' }}</td>
                        <td>{{ $a->date_avoir }}</td>
                        <td>{{ number_format($a->montant, 0, ',', ' ') }} FCFA</td>
                        <td>{{ $a->motif ?? '—' }}</td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('avoirs.edit', $a->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('avoirs.destroy', $a->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer cet avoir ?');">
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

<!-- Modal création avoir -->
<div class="modal fade" id="modalCreateAvoir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glow-border">
            <div class="modal-header">
                <h5 class="modal-title">Nouvel avoir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('avoirs.store') }}" method="POST">
                @csrf
                <div class="modal-body row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Numéro (optionnel)</label>
                        <input type="text" name="numero" class="form-control" placeholder="Auto si vide">
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" name="date_avoir" class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Montant</label>
                        <input type="number" step="0.01" name="montant" id="montantInputModal" class="form-control" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Motif</label>
                        <input type="text" name="motif" class="form-control" placeholder="Optionnel">
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
    $('#avoirsTable').DataTable({ pageLength: 10 });

    const select = document.getElementById('factureSelectModal');
    const montantInput = document.getElementById('montantInputModal');
    if (select && montantInput) {
        const applyMontant = () => {
            const opt = select.options[select.selectedIndex];
            const montant = opt ? Number(opt.dataset.montant ?? 0) : 0;
            montantInput.value = Number.isFinite(montant) ? montant.toFixed(2) : '';
        };
        select.addEventListener('change', applyMontant);
        if (select.value) applyMontant();
    }
});
</script>
@endsection
