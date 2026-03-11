@extends('layouts.main')

@section('title', 'Tableau de bord - Mois en cours')
@section('page_title', 'Tableau de bord - Mois en cours')

@section('content')
    <style>
        .ovl-kpi {
            border: 1px solid rgba(0, 0, 0, 0.07);
            background: #ffffff;
            padding: 14px;
            height: 100%;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .ovl-kpi .ovl-kpi-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }
        .ovl-kpi .ovl-kpi-value {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.1;
        }
        .ovl-tile-green {
            background: #36b58f;
            color: #ffffff;
            padding: 14px;
            height: 100%;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.10);
        }
        .ovl-tile-green .ovl-tile-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            opacity: 0.95;
        }
        .ovl-tile-green .ovl-tile-value {
            font-size: 56px;
            font-weight: 800;
            line-height: 1;
            margin-top: 8px;
        }
        .ovl-list-compact {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }
        .ovl-list-compact li {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 3px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            font-size: 13px;
        }
        .ovl-list-compact li:last-child {
            border-bottom: 0;
        }
        .ovl-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            transform: translateY(1px);
        }
        .ovl-donut-wrap {
            position: relative;
            height: 170px;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($nbColisRecusMois ?? 0, 0, ',', ' ') }}</h3>
                        <p>Colis reçus ce mois</p>
                        @if(($variationRecus ?? 0) != 0)
                            <small class="{{ $variationRecus > 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas fa-{{ $variationRecus > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ abs($variationRecus) }}% vs mois préc.
                            </small>
                        @endif
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($nbColisLivresMois ?? 0, 0, ',', ' ') }}</h3>
                        <p>Colis livrés ce mois</p>
                        @if(($variationLivres ?? 0) != 0)
                            <small class="{{ $variationLivres > 0 ? 'text-light' : 'text-warning' }}">
                                <i class="fas fa-{{ $variationLivres > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ abs($variationLivres) }}% vs mois préc.
                            </small>
                        @endif
                    </div>
                    <div class="icon">
                        <i class="ion ion-checkmark-circled"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($nbColisNonLivresMois ?? 0, 0, ',', ' ') }}</h3>
                        <p>Non livrés ce mois</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-close-circled"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format($nbColisRetoursMois ?? 0, 0, ',', ' ') }}</h3>
                        <p>Retours ce mois</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-arrow-return-left"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6">
                <div class="ovl-kpi">
                    <div class="ovl-kpi-label">Stats colis (mois en cours)</div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <ul class="ovl-list-compact">
                                <li>
                                    <div><span class="ovl-dot" style="background:#17a2b8"></span>Reçus</div>
                                    <div style="font-weight:700;">{{ number_format($nbColisRecusMois ?? 0, 0, ',', ' ') }}</div>
                                </li>
                                <li>
                                    <div><span class="ovl-dot" style="background:#28a745"></span>Livrés</div>
                                    <div style="font-weight:700;">{{ number_format($nbColisLivresMois ?? 0, 0, ',', ' ') }}</div>
                                </li>
                                <li>
                                    <div><span class="ovl-dot" style="background:#dc3545"></span>Non Livrés</div>
                                    <div style="font-weight:700;">{{ number_format($nbColisNonLivresMois ?? 0, 0, ',', ' ') }}</div>
                                </li>
                                <li>
                                    <div><span class="ovl-dot" style="background:#ffc107"></span>Retours</div>
                                    <div style="font-weight:700;">{{ number_format($nbColisRetoursMois ?? 0, 0, ',', ' ') }}</div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <div class="ovl-tile-green" style="background:#2bb3c0; border-radius:2px;">
                                <div class="ovl-tile-label">Total colis reçus (mois)</div>
                                <div class="ovl-tile-value" style="font-size:46px;">{{ number_format($nbColisRecusMois ?? 0, 0, ',', ' ') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="ovl-kpi">
                    <div class="ovl-kpi-label">Synthèse financière (mois en cours)</div>
                    <div class="row mt-3" style="gap: 10px;">
                        <div class="col-12 col-md" style="padding: 0;">
                            <div class="ovl-kpi" style="border-left: 6px solid #28a745; padding: 10px 12px; box-shadow: none;">
                                <div class="ovl-kpi-label" style="margin-bottom: 4px;">Montant livré</div>
                                <div class="ovl-kpi-value" style="font-size: 20px;">{{ number_format($montantLivresMois ?? 0, 0, ',', ' ') }} XOF</div>
                            </div>
                        </div>
                        <div class="col-12 col-md" style="padding: 0;">
                            <div class="ovl-kpi" style="border-left: 6px solid #17a2b8; padding: 10px 12px; box-shadow: none;">
                                <div class="ovl-kpi-label" style="margin-bottom: 4px;">Points validés</div>
                                <div class="ovl-kpi-value" style="font-size: 20px;">{{ number_format($montantPointsValidesMois ?? 0, 0, ',', ' ') }} XOF</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3" style="gap: 10px;">
                        <div class="col-12 col-md" style="padding: 0;">
                            <div class="ovl-kpi" style="border-left: 6px solid #0d6efd; padding: 10px 12px; box-shadow: none;">
                                <div class="ovl-kpi-label" style="margin-bottom: 4px;">Paiements reçus</div>
                                <div class="ovl-kpi-value" style="font-size: 20px;">{{ number_format($montantPaiementsEffectuesMois ?? 0, 0, ',', ' ') }} XOF</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="ovl-kpi">
                    <div class="ovl-kpi-label">Évolution des livraisons sur l'année</div>
                    <div class="mt-3" style="height: 280px;">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6">
                <div class="ovl-kpi">
                    <div class="ovl-kpi-label">Top 5 des communes - Livraisons (mois en cours)</div>
                    <div class="mt-3" style="height: 220px;">
                        <canvas id="topCommunesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ovl-kpi">
                    <div class="ovl-kpi-label">Top 5 des communes - Expéditions (mois en cours)</div>
                    <div class="mt-3" style="height: 220px;">
                        <canvas id="topExpeditionsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
<script>
(function() {
    var topCommunesLabels = @json(($topCommunesMois ?? collect())->pluck('communes'));
    var topCommunesData = @json(($topCommunesMois ?? collect())->pluck('total'));
    var topCommunesColors = ['#36b58f', '#2bb3c0', '#7b5cd6', '#ff6b6b', '#ffa94d'];

    var topCommunesCanvas = document.getElementById('topCommunesChart');
    if (topCommunesCanvas && topCommunesLabels.length) {
        new Chart(topCommunesCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: topCommunesLabels,
                datasets: [{
                    label: 'Livraisons',
                    data: topCommunesData,
                    backgroundColor: topCommunesColors,
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { display: false }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    var topExpeditionsLabels = @json(($topExpeditionsMois ?? collect())->pluck('communes'));
    var topExpeditionsData = @json(($topExpeditionsMois ?? collect())->pluck('total'));
    var topExpeditionsColors = ['#17a2b8', '#28a745', '#fd7e14', '#dc3545', '#6c757d'];

    var topExpeditionsCanvas = document.getElementById('topExpeditionsChart');
    if (topExpeditionsCanvas && topExpeditionsLabels.length) {
        new Chart(topExpeditionsCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: topExpeditionsLabels,
                datasets: [{
                    label: 'Expéditions',
                    data: topExpeditionsData,
                    backgroundColor: topExpeditionsColors,
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { display: false }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
    // Graphique d'évolution mensuelle
    var evolutionLabels = @json($moisLabels ?? []);
    var evolutionData = @json($evolutionMensuelle ?? []);

    var evolutionCanvas = document.getElementById('evolutionChart');
    if (evolutionCanvas && evolutionLabels.length) {
        new Chart(evolutionCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: evolutionLabels,
                datasets: [{
                    label: 'Livraisons',
                    data: evolutionData,
                    borderColor: '#36b58f',
                    backgroundColor: 'rgba(54, 181, 143, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#36b58f',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    }
                }
            }
        });
    }
})();
</script>
@endsection
