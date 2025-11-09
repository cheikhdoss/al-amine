<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport d'activité</title>
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
    <h1>Rapport d'activité</h1>
    <p class="muted">Période du {{ \\Carbon\\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \\Carbon\\Carbon::parse($dateFin)->format('d/m/Y') }}</p>

    <table>
        <tr>
            <th>Indicateur</th>
            <th>Valeur</th>
        </tr>
        <tr>
            <td>Rendez-vous programmés</td>
            <td>{{ $data['rdv_total'] }}</td>
        </tr>
        <tr>
            <td>Consultations réalisées</td>
            <td>{{ $data['consultations_total'] }}</td>
        </tr>
        <tr>
            <td>Nouveaux patients</td>
            <td>{{ $data['nouveaux_patients'] }}</td>
        </tr>
        <tr>
            <td>Taux de présence</td>
            <td>{{ $data['taux_presence'] }} %</td>
        </tr>
    </table>

    <h2>Commentaires</h2>
    <p class="muted">Rapport généré automatiquement le {{ now()->format('d/m/Y à H:i') }}.</p>
</body>
</html>
