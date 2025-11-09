<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport financier</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        h2 { font-size: 16px; margin-top: 20px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; text-transform: uppercase; font-size: 11px; }
        .muted { color: #6b7280; font-size: 11px; }
    </style>
</head>
<body>
    <h1>Rapport financier</h1>
    <p class="muted">Période du {{ \\Carbon\\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \\Carbon\\Carbon::parse($dateFin)->format('d/m/Y') }}</p>

    <table>
        <tr>
            <th>Indicateur</th>
            <th>Valeur</th>
        </tr>
        <tr>
            <td>Chiffre d'affaires total</td>
            <td>{{ number_format($data['ca_total'], 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td>Factures émises</td>
            <td>{{ $data['factures_emises'] }}</td>
        </tr>
        <tr>
            <td>Factures payées</td>
            <td>{{ $data['factures_payees'] }}</td>
        </tr>
        <tr>
            <td>Montants par méthode</td>
            <td>
                <ul style="margin:0; padding-left: 16px;">
                    @forelse($data['paiements_par_methode'] as $ligne)
                        <li>{{ $ligne->methode_paiement ?? 'Inconnue' }} : {{ number_format($ligne->total, 0, ',', ' ') }} FCFA</li>
                    @empty
                        <li>Aucun paiement enregistré</li>
                    @endforelse
                </ul>
            </td>
        </tr>
    </table>

    <h2>Commentaires</h2>
    <p class="muted">Rapport généré automatiquement le {{ now()->format('d/m/Y à H:i') }}.</p>
</body>
</html>
