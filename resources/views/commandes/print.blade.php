<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Point du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            color: #666;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .info-row strong {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 6px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background: #f5f5f5;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 500;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .totals {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .totals-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            padding-top: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        @media print {
            body {
                padding: 10px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 30px; background: #007bff; color: #fff; border: none; border-radius: 25px; cursor: pointer; font-size: 14px; margin-right: 10px;">
            <i class="fas fa-print"></i> Imprimer
        </button>
        <button onclick="validerPoint()" style="padding: 10px 30px; background: #28a745; color: #fff; border: none; border-radius: 25px; cursor: pointer; font-size: 14px;">
            <i class="fas fa-check"></i> Valider le point
        </button>
    </div>

    <script>
        function validerPoint() {
            if (confirm('Êtes-vous sûr de vouloir valider ce point ?')) {
                window.location.href = '{{ route("commandes.valider", ["date" => $date]) }}';
            }
        }
    </script>

    <div class="header">
        <h1>{{ $boutique['nom'] ?? ($client['nom'] . ' ' . $client['prenoms']) }}</h1>
        <p>Point des commandes du {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
    </div>

    <div class="info-row">
        <div><strong>Boutique:</strong> {{ $boutique['nom'] ?? ($client['nom'] . ' ' . $client['prenoms']) }}</div>
        <div><strong>Date d'impression:</strong> {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    @if($commandes->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Commune</th>
                    <th class="text-right">Montant</th>
                    <th class="text-center">Statut</th>
                    <th class="text-center">Date réception</th>
                    <th class="text-center">Date livraison</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commandes as $index => $cmd)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $cmd->communes ?? '-' }}</td>
                        <td class="text-right">{{ number_format($cmd->cout_reel ?? 0, 0, ',', ' ') }}</td>
                        <td class="text-center">
                            @if($cmd->statut == 'Livré')
                                <span class="badge badge-success">Livré</span>
                            @else
                                <span class="badge badge-danger">{{ $cmd->statut }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $cmd->date_reception ? \Carbon\Carbon::parse($cmd->date_reception)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">{{ $cmd->date_livraison ? \Carbon\Carbon::parse($cmd->date_livraison)->format('d-m-Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <span>Nombre de commandes:</span>
                <span>{{ $commandes->count() }}</span>
            </div>
            <div class="totals-row">
                <span>Total Montant:</span>
                <span>{{ number_format($commandes->where('statut', 'Livré')->sum('cout_reel'), 0, ',', ' ') }} XOF</span>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #999;">
            <p>Aucune commande trouvée pour cette date.</p>
        </div>
    @endif

    <div class="footer">
        <p>OVL Delivery - Point généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
