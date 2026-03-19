@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up d-flex justify-content-between align-items-center">
    <div>
        <h1 class="fw-bold text-primary">Devis {{ $devi->numero ?? ('DEV-'.$devi->id) }}</h1>
        <p class="text-muted mb-0">Détails et exports.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('devis.export.pdf', $devi->id) }}" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-filetype-pdf me-1"></i>PDF
        </a>
        <a href="{{ route('devis.export.excel', $devi->id) }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>Excel
        </a>
        <a href="{{ route('devis.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left-short me-1"></i>Retour
        </a>
    </div>
</div>

<section class="section fade-slide-up">
    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card card-hover-lift">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Informations</h6>
                    <div class="mb-2"><span class="text-muted small">Client :</span> <strong>{{ $devi->client->nom ?? '—' }}</strong></div>
                    <div class="mb-2"><span class="text-muted small">Date devis :</span> {{ $devi->date_devis }}</div>
                    <div class="mb-2"><span class="text-muted small">Expiration :</span> {{ $devi->date_expiration }}</div>
                    <div class="mb-2"><span class="text-muted small">Statut :</span>
                        <span class="badge {{ $devi->statut === 'annule' ? 'bg-danger' : ($devi->statut === 'valide' ? 'bg-success' : 'bg-warning') }}">
                            {{ ucfirst(str_replace('_',' ', $devi->statut ?? '')) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card card-hover-lift">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Totaux</h6>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total HT</span>
                        <strong>{{ number_format($devi->total_ht ?? 0, 0, ',', ' ') }} FCFA</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>TVA</span>
                        <strong>{{ number_format($devi->total_tva ?? 0, 0, ',', ' ') }} FCFA</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total TTC</span>
                        <strong class="text-primary">{{ number_format($devi->total_ttc ?? 0, 0, ',', ' ') }} FCFA</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card card-hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted mb-0">Lignes</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-end">Quantité</th>
                                    <th class="text-end">Prix</th>
                                    <th class="text-end">TVA</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($devi->lignes as $ligne)
                                <tr>
                                    <td>{{ $ligne->produit->designation ?? '—' }}</td>
                                    <td class="text-end">{{ $ligne->quantite }}</td>
                                    <td class="text-end">{{ number_format($ligne->prix_unitaire ?? 0, 0, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($ligne->tva ?? 0, 0, ',', ' ') }}</td>
                                    <td class="text-end fw-semibold">{{ number_format($ligne->total ?? 0, 0, ',', ' ') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
