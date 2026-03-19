<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapports</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background: #f3f3f3; }
    </style>
</head>
<body>
    <h2>Rapport des ventes</h2>
    <table>
        <tr><th>Factures</th><th>CA HT</th><th>TVA</th><th>CA TTC</th><th>Aujourd'hui</th></tr>
        <tr>
            <td>{{ $ventes['factures'] ?? 0 }}</td>
            <td>{{ $ventes['ht'] ?? 0 }}</td>
            <td>{{ $ventes['tva'] ?? 0 }}</td>
            <td>{{ $ventes['ttc'] ?? 0 }}</td>
            <td>{{ $ventes['jour'] ?? 0 }}</td>
        </tr>
    </table>

    <h2>Rapport des achats</h2>
    <table>
        <tr><th>Total</th><th>Mois</th><th>Année</th></tr>
        <tr>
            <td>{{ $achats['total'] ?? 0 }}</td>
            <td>{{ $achats['mois'] ?? 0 }}</td>
            <td>{{ $achats['annee'] ?? 0 }}</td>
        </tr>
    </table>

    <h2>Rapport financier</h2>
    <table>
        <tr><th>Encaissé</th><th>Avoirs</th><th>Payées</th><th>Impayées</th><th>Balance</th></tr>
        <tr>
            <td>{{ $financier['encaisse'] ?? 0 }}</td>
            <td>{{ $financier['avoirs'] ?? 0 }}</td>
            <td>{{ $financier['payees'] ?? 0 }}</td>
            <td>{{ $financier['impayees'] ?? 0 }}</td>
            <td>{{ $financier['balance'] ?? 0 }}</td>
        </tr>
    </table>

    <h2>Rapport stock</h2>
    <table>
        <tr><th>Produit</th><th>Stock</th><th>Valeur</th></tr>
        @foreach($stocks as $s)
        <tr>
            <td>{{ $s->produit->designation ?? '—' }}</td>
            <td>{{ $s->quantite }}</td>
            <td>{{ ($s->produit->prix_vente ?? 0) * $s->quantite }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
