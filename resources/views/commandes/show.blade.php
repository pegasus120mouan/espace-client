@extends('layouts.main')

@section('title', 'Détail Commande')
@section('page_title', 'Détail de la commande')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-box mr-2"></i>
                            Commande #{{ $commande->id }}
                        </h3>
                        <div class="card-tools">
                            @php
                                $badgeClass = match($commande->statut) {
                                    'Livré' => 'success',
                                    'Non livré', 'Non Livré' => 'danger',
                                    'Retour' => 'secondary',
                                    'En cours' => 'primary',
                                    default => 'warning'
                                };
                            @endphp
                            <span class="badge badge-{{ $badgeClass }}" style="font-size: 14px;">{{ $commande->statut ?? 'En attente' }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3"><i class="fas fa-map-marker-alt mr-2"></i>Informations livraison</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th style="width: 40%;">Commune</th>
                                        <td>{{ $commande->communes ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-muted mb-3"><i class="fas fa-calendar mr-2"></i>Dates</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <th style="width: 40%;">Date réception</th>
                                        <td>{{ $commande->date_reception ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date livraison</th>
                                        <td>{{ $commande->date_livraison ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date retour</th>
                                        <td>{{ $commande->date_retour ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white"><i class="fas fa-money-bill mr-2"></i>Montants</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <th>Coût réel</th>
                                <td class="text-right">{{ number_format($commande->cout_reel ?? 0, 0, ',', ' ') }} XOF</td>
                            </tr>
                            <tr>
                                <th>Frais livraison</th>
                                <td class="text-right">{{ number_format($commande->cout_livraison ?? 0, 0, ',', ' ') }} XOF</td>
                            </tr>
                            <tr class="bg-light">
                                <th><strong>Total</strong></th>
                                <td class="text-right"><strong>{{ number_format($commande->cout_global ?? 0, 0, ',', ' ') }} XOF</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-truck mr-2"></i>Livreur</h3>
                    </div>
                    <div class="card-body">
                        @if($commande->livreur)
                            <div class="d-flex align-items-center">
                                <img src="{{ $commande->livreur->avatar ?? 'https://via.placeholder.com/50' }}" class="img-circle mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <strong>{{ $commande->livreur->nom ?? '' }} {{ $commande->livreur->prenoms ?? '' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $commande->livreur->contact ?? '-' }}</small>
                                </div>
                            </div>
                        @else
                            <p class="text-muted mb-0">Non assigné</p>
                        @endif
                    </div>
                </div>

                <a href="{{ route('commandes.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </div>
@endsection
