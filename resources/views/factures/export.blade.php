<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 24mm 18mm 20mm 18mm; }
        body{font-family: DejaVu Sans, sans-serif; font-size:12px; color:#333;}
        .brand-bar{height:8px;background:#2f3542;margin:-12mm -18mm 16px -18mm;}
        .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;}
        .logo{height:60px;}
        .title{font-size:26px;font-weight:700;color:#2f3542;margin:0;}
        .meta{margin-top:8px;font-size:12px;}
        .two-col{display:flex;justify-content:space-between;gap:24px;margin-bottom:14px;}
        .box h4{margin:0;font-size:14px;font-weight:700;color:#2f3542;}
        .box div{font-size:12px;line-height:1.5;}
        table{width:100%;border-collapse:collapse;margin-top:12px;}
        th,td{padding:8px;border:1px solid #e2e8f0;}
        th{background:#2f3542;color:white;font-weight:600;font-size:12px;}
        tfoot td{border:none;padding:4px 0;font-size:12px;}
        .text-end{text-align:right;}
        .totaux{margin-top:14px;width:320px;float:right;}
        .totaux .line{display:flex;justify-content:space-between;padding:4px 0;}
        .totaux .total{font-weight:700;font-size:14px;color:#2f3542;border-top:1px solid #e2e8f0;margin-top:6px;padding-top:6px;}
        .totaux .due{font-weight:800;font-size:14px;color:#000;}
    </style>
</head>
<body>
    <div class="brand-bar"></div>
    <div class="header">
        <div>
            <h1 class="title">Facture</h1>
            <div class="meta">N° {{ $facture->numero ?? ('FAC-'.$facture->id) }} · Date {{ $facture->date_facture }}</div>
        </div>
        @php
            $logoFile = null;
            if(!empty($settings['logo'])) {
                $possible = public_path(str_replace(url('/').'/', '', $settings['logo']));
                if(file_exists($possible)) $logoFile = $possible;
            }
            if(!$logoFile){
                $fallback = public_path('assets/img/logo.png');
                if(file_exists($fallback)) $logoFile = $fallback;
            }
        @endphp
        @if($logoFile)
            <img class="logo" src="{{ $logoFile }}" alt="Logo">
        @endif
    </div>

    <div class="two-col">
        <div class="box">
            <h4>De</h4>
            <div>{{ $settings['nom_entreprise'] ?? config('app.name') }}</div>
            <div>{{ config('app.url') }}</div>
        </div>
        <div class="box" style="text-align:right;">
            <h4>À</h4>
            <div>{{ $facture->client->nom ?? 'Client' }}</div>
            <div>{{ $facture->client->email ?? '' }}</div>
            <div>{{ $facture->client->adresse ?? '' }}</div>
            <div>{{ $facture->client->telephone ?? '' }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40%;">Description</th>
                <th class="text-end" style="width:15%;">Prix unitaire</th>
                <th class="text-end" style="width:15%;">Quantité</th>
                <th class="text-end" style="width:15%;">TVA</th>
                <th class="text-end" style="width:15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facture->lignes as $l)
            <tr>
                <td>{{ $l->produit->designation ?? '—' }}</td>
                <td class="text-end">{{ number_format($l->prix ?? 0, 2, ',', ' ') }}</td>
                <td class="text-end">{{ $l->quantite }}</td>
                <td class="text-end">{{ number_format($l->tva ?? 0, 2, ',', ' ') }}</td>
                <td class="text-end">{{ number_format($l->total ?? 0, 2, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totaux">
        <div class="line"><span>Sous-total </span><span>{{ number_format($facture->total_ht ?? 0, 2, ',', ' ') }} FCFA</span></div>
        <div class="line"><span>TVA </span><span>{{ number_format($facture->total_tva ?? 0, 2, ',', ' ') }} FCFA</span></div>
        <div class="line total"><span>Total </span><span>{{ number_format($facture->total_ttc ?? 0, 2, ',', ' ') }} FCFA</span></div>
        <div class="line due"><span>Solde dû </span><span>{{ number_format($facture->reste_a_payer ?? 0, 2, ',', ' ') }} FCFA</span></div>
    </div>
</body>
</html>
