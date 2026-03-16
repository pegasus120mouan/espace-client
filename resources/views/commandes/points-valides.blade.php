@extends('layouts.main')

@section('title', 'Points validés')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-check-circle mr-2 text-success"></i>Mes points validés</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date de livraison</th>
                                <th>Nombre de colis</th>
                                <th class="text-right">Montant</th>
                                <th class="text-center">Statut validation</th>
                                <th>Date de validation</th>
                                <th class="text-center">Statut paiement</th>
                                <th>Opérateur</th>
                                <th>Date de paiement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pointsValides as $point)
                            <tr>
                                <td>
                                    <strong>{{ $point->date_livraison ? \Carbon\Carbon::parse($point->date_livraison)->format('d-m-Y') : 'N/A' }}</strong>
                                </td>
                                <td>{{ $point->nombre_colis }} colis</td>
                                <td class="text-right">
                                    <strong class="text-success">{{ number_format($point->montant_total, 0, ',', ' ') }} XOF</strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success p-2">
                                        <i class="fas fa-check mr-1"></i>Validé
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $point->date_validation_point ? \Carbon\Carbon::parse($point->date_validation_point)->format('d-m-Y à H:i') : 'N/A' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    @if($point->paiement_effectue)
                                        <span class="badge badge-success p-2">
                                            <i class="fas fa-money-bill-wave mr-1"></i>Payé
                                        </span>
                                    @else
                                        <span class="badge badge-warning p-2">
                                            <i class="fas fa-clock mr-1"></i>En attente
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($point->operateur_paiement)
                                        @php
                                            $operateur = strtolower(str_replace([' ', '_'], '', $point->operateur_paiement));
                                            $logoMap = [
                                                'mtn' => 'mtn.png',
                                                'mtnmoney' => 'mtn.png',
                                                'mtnmobilemoney' => 'mtn.png',
                                                'wave' => 'wave.png',
                                                'orange' => 'orange.png',
                                                'orangemoney' => 'orange.png',
                                                'moov' => 'moov.png',
                                                'moovmoney' => 'moov.png',
                                            ];
                                            $logo = $logoMap[$operateur] ?? null;
                                        @endphp
                                        @if($logo)
                                            <img src="{{ asset('img/money/' . $logo) }}" alt="{{ $point->operateur_paiement }}" style="height: 45px; width: auto;">
                                        @else
                                            <span class="badge badge-info">{{ $point->operateur_paiement }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($point->date_paiement)
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            {{ \Carbon\Carbon::parse($point->date_paiement)->format('d-m-Y à H:i') }}
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">Aucun point validé pour le moment</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pointsValides->hasPages())
                <div class="card-footer">
                    {{ $pointsValides->links('vendor.pagination.bootstrap-4') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
