<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color:#000; }
        .sheet { width: 100%; }
        .header { text-align:center; font-size:18px; font-weight:bold; padding:12px 0; border:2px solid #000; margin-bottom:8px; }
        .meta { width:100%; margin-bottom:8px; }
        .meta td { padding:3px; font-size:12px; }
        .box { border:1px solid #000; padding:6px; }
        .label { width:70px; font-weight:bold; }
        table.detail { width:100%; border-collapse:collapse; margin-top:10px; }
        table.detail th, table.detail td { border:1px solid #000; padding:6px; }
        table.detail th { background:#d9d9d9; font-weight:bold; text-align:center; }
        .right { text-align:right; }
        .center { text-align:center; }
        .totaux { width:280px; float:right; margin-top:8px; }
        .totaux td { padding:4px; }
        .totaux .label { font-weight:bold; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            FACTURE N° {{ $facture->numero ?? ('FAC-'.$facture->id) }}
        </div>

        <table class="meta">
            <tr>
                <td class="label">Date :</td>
                <td>{{ $facture->date_facture }}</td>
                <td class="label">Client :</td>
                <td>{{ $facture->client->nom ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Adresse :</td>
                <td>{{ $facture->client->adresse ?? '' }}</td>
                <td class="label">Téléphone :</td>
                <td>{{ $facture->client->telephone ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Code client :</td>
                <td>{{ $facture->client->code ?? '' }}</td>
                <td class="label">Devise :</td>
                <td>{{ $settings['devise'] ?? 'FCFA' }}</td>
            </tr>
        </table>

        <table class="detail">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Description</th>
                    <th>PU HT</th>
                    <th>Quantité</th>
                    <th>Montant HT</th>
                    <th>Taux TVA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facture->lignes as $l)
                <tr>
                    <td class="center">{{ $l->produit->code ?? '' }}</td>
                    <td>{{ $l->produit->designation ?? '—' }}</td>
                    <td class="right">{{ number_format($l->prix ?? 0, 2, ',', ' ') }}</td>
                    <td class="center">{{ $l->quantite }}</td>
                    <td class="right">{{ number_format($l->quantite * ($l->prix ?? 0), 2, ',', ' ') }}</td>
                    <td class="center">{{ number_format($l->tva ?? 0, 2, ',', ' ') }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totaux">
            <tr><td class="label">Sous-total</td><td class="right">{{ number_format($facture->total_ht ?? 0, 2, ',', ' ') }} {{ $settings['devise'] ?? 'FCFA' }}</td></tr>
            <tr><td class="label">TVA</td><td class="right">{{ number_format($facture->total_tva ?? 0, 2, ',', ' ') }} {{ $settings['devise'] ?? 'FCFA' }}</td></tr>
            <tr><td class="label">Total TTC</td><td class="right"><strong>{{ number_format($facture->total_ttc ?? 0, 2, ',', ' ') }} {{ $settings['devise'] ?? 'FCFA' }}</strong></td></tr>
            <tr><td class="label">Solde dû</td><td class="right"><strong>{{ number_format($facture->reste_a_payer ?? 0, 2, ',', ' ') }} {{ $settings['devise'] ?? 'FCFA' }}</strong></td></tr>
        </table>
    </div>
</body>
</html>
