@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Rapports</h1>
    <p class="text-muted mb-0">Vue consolidée ventes, achats, stocks et finances.</p>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">Rapports</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row g-3">

        <!-- Rapports ventes -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0">Rapport des ventes</h5>
                        <span class="badge bg-primary-subtle text-primary">Factures: {{ $ventes['factures'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex gap-2 mb-3">
                        <a href="{{ route('rapports.export.excel') }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
                        <a href="{{ route('rapports.export.pdf') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
                    </div>
                    <div class="row text-center mb-3">
                        <div class="col-md-3">
                            <div class="fs-6 text-muted">CA HT</div>
                            <div class="fs-4 fw-semibold">{{ number_format($ventes['ht'] ?? 0,0,',',' ') }} FCFA</div>
                        </div>
                        <div class="col-md-3">
                            <div class="fs-6 text-muted">TVA collectée</div>
                            <div class="fs-4 fw-semibold">{{ number_format($ventes['tva'] ?? 0,0,',',' ') }} FCFA</div>
                        </div>
                        <div class="col-md-3">
                            <div class="fs-6 text-muted">CA TTC</div>
                            <div class="fs-4 fw-semibold text-primary">{{ number_format($ventes['ttc'] ?? 0,0,',',' ') }} FCFA</div>
                        </div>
                        <div class="col-md-3">
                            <div class="fs-6 text-muted">Aujourd'hui</div>
                            <div class="fs-4 fw-semibold">{{ number_format($ventes['jour'] ?? 0,0,',',' ') }} FCFA</div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr><th>Produit</th><th>Quantité</th><th>Total</th></tr>
                            </thead>
                            <tbody>
                                @forelse($topProduitsVendus as $p)
                                <tr>
                                    <td>{{ $p->produit->designation ?? '—' }}</td>
                                    <td>{{ $p->qte }}</td>
                                    <td>{{ number_format($p->total,0,',',' ') }} FCFA</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-muted text-center">Aucune donnée</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <h6 class="fw-bold mt-3">Meilleurs clients</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light"><tr><th>Client</th><th>Factures</th><th>Total TTC</th></tr></thead>
                            <tbody>
                                @forelse($topClients as $c)
                                <tr>
                                    <td>{{ $c->client->nom ?? '—' }}</td>
                                    <td>{{ $c->factures }}</td>
                                    <td>{{ number_format($c->total,0,',',' ') }} FCFA</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-muted text-center">Aucune donnée</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapports achats -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0">Rapport des achats</h5>
                        <span class="badge bg-success-subtle text-success">Achats: {{ $achats['achats'] ?? 0 }}</span>
                    </div>
                    <div class="row text-center mb-3">
                        <div class="col-md-4">
                            <div class="fs-6 text-muted">Total achats</div>
                            <div class="fs-4 fw-semibold">{{ number_format($achats['total'] ?? 0,0,',',' ') }} FCFA</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fs-6 text-muted">Ce mois</div>
                            <div class="fs-4 fw-semibold">{{ number_format($achats['mois'] ?? 0,0,',',' ') }} FCFA</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fs-6 text-muted">Cette année</div>
                            <div class="fs-4 fw-semibold">{{ number_format($achats['annee'] ?? 0,0,',',' ') }} FCFA</div>
                        </div>
                    </div>
                    <h6 class="fw-bold">Fournisseurs les plus utilisés</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light"><tr><th>Fournisseur</th><th>Achats</th><th>Total</th></tr></thead>
                            <tbody>
                                @forelse($topFournisseurs as $f)
                                <tr>
                                    <td>{{ $f->fournisseur->nom ?? '—' }}</td>
                                    <td>{{ $f->achats }}</td>
                                    <td>{{ number_format($f->total,0,',',' ') }} FCFA</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-muted text-center">Aucune donnée</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <h6 class="fw-bold mt-3">Produits les plus achetés</h6>
                    <ul class="list-group list-group-flush">
                        @forelse($topProduitsAchetes as $p)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $p->produit->designation ?? '—' }}</span>
                            <span class="fw-semibold">{{ $p->qte }} u</span>
                        </li>
                        @empty
                        <li class="list-group-item text-muted">Aucune donnée</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Rapport stock -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0">Rapport du stock</h5>
                        <div class="d-flex gap-3">
                            <span class="badge bg-warning-subtle text-warning">Ruptures: {{ $ruptures->count() ?? 0 }}</span>
                            <span class="badge bg-danger-subtle text-danger">Sous seuil: {{ $sousSeuil->count() ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="fs-6 text-muted">Valeur totale</div>
                            <div class="fs-4 fw-semibold">{{ number_format($stockValue ?? 0,0,',',' ') }} FCFA</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fs-6 text-muted">Produits en rupture</div>
                            <div class="fs-4 fw-semibold text-danger">{{ $ruptures->count() ?? 0 }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fs-6 text-muted">Produits sous seuil</div>
                            <div class="fs-4 fw-semibold text-warning">{{ $sousSeuil->count() ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light"><tr><th>Produit</th><th>Stock</th><th>Valeur</th></tr></thead>
                            <tbody>
                                @forelse($stocks as $s)
                                <tr>
                                    <td>{{ $s->produit->designation ?? '—' }}</td>
                                    <td>{{ $s->quantite }}</td>
                                    <td>{{ number_format(($s->produit->prix_vente ?? 0) * $s->quantite,0,',',' ') }} FCFA</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-muted text-center">Aucune donnée</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapport financier -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Rapport financier</h5>
                    <div class="row text-center mb-3">
                        <div class="col-md-3">
                            <div class="fs-6 text-muted">Encaissé</div>
                            <div class="fs-4 fw-semibold">{{ number_format($financier['encaisse'] ?? 0,0,',',' ') }} FCFA</div>
                        </div>
                        <div class="col-md-3">
                            <div class="fs-6 text-muted">Factures payées</div>
                            <div class="fs-4 fw-semibold">{{ $financier['payees'] ?? 0 }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="fs-6 text-muted">Factures impayées</div>
                            <div class="fs-4 fw-semibold text-warning">{{ $financier['impayees'] ?? 0 }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="fs-6 text-muted">Total avoirs</div>
                            <div class="fs-4 fw-semibold">{{ number_format($financier['avoirs'] ?? 0,0,',',' ') }} FCFA</div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="fs-6 text-muted">Balance</div>
                        <div class="fs-3 fw-bold text-primary">{{ number_format($financier['balance'] ?? 0,0,',',' ') }} FCFA</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients / Fournisseurs -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title mb-2">Rapport clients</h5>
                            <p class="mb-1">Nombre de clients : <strong>{{ $clientsCount }}</strong></p>
                            <ul class="list-group list-group-flush">
                                @forelse($topClients as $c)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $c->client->nom ?? '—' }}</span>
                                    <span class="fw-semibold">{{ number_format($c->total,0,',',' ') }} FCFA</span>
                                </li>
                                @empty
                                <li class="list-group-item text-muted">Aucune donnée</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title mb-2">Rapport fournisseurs</h5>
                            <p class="mb-1">Nombre de fournisseurs : <strong>{{ $fournisseursCount }}</strong></p>
                            <ul class="list-group list-group-flush">
                                @forelse($topFournisseurs as $f)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $f->fournisseur->nom ?? '—' }}</span>
                                    <span class="fw-semibold">{{ number_format($f->total,0,',',' ') }} FCFA ({{ $f->achats }} achats)</span>
                                </li>
                                @empty
                                <li class="list-group-item text-muted">Aucune donnée</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
