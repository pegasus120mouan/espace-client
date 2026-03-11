@extends('layouts.main')

@section('title', 'Mes Commandes')
@section('page_title', 'Mes Commandes')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($stats['total'] ?? 0, 0, ',', ' ') }}</h3>
                        <p>Total</p>
                    </div>
                    <div class="icon"><i class="ion ion-ios-list"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($stats['livres'] ?? 0, 0, ',', ' ') }}</h3>
                        <p>Livrés</p>
                    </div>
                    <div class="icon"><i class="ion ion-checkmark-circled"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($stats['en_cours'] ?? 0, 0, ',', ' ') }}</h3>
                        <p>En cours</p>
                    </div>
                    <div class="icon"><i class="ion ion-clock"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format($stats['non_livres'] ?? 0, 0, ',', ' ') }}</h3>
                        <p>Non livrés</p>
                    </div>
                    <div class="icon"><i class="ion ion-close-circled"></i></div>
                </div>
            </div>
        </div>

        <div class="card mb-3 shadow-sm" style="border: none; border-radius: 12px;">
            <div class="card-body py-3 px-4">
                <div class="d-flex flex-wrap align-items-center" style="gap: 12px;">
                    <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#createCommandeModal" style="border-radius: 25px; padding: 10px 20px; font-weight: 500; font-size: 13px;">
                        <i class="fas fa-plus mr-2"></i>Nouvelle commande
                    </button>
                    <button type="button" class="btn btn-success shadow-sm" data-toggle="modal" data-target="#printModal" style="border-radius: 25px; padding: 10px 20px; font-weight: 500; font-size: 13px;">
                        <i class="fas fa-print mr-2"></i>Imprimer
                    </button>
                    <button type="button" class="btn btn-info shadow-sm text-white" data-toggle="modal" data-target="#searchModal" style="border-radius: 25px; padding: 10px 20px; font-weight: 500; font-size: 13px;">
                        <i class="fas fa-search mr-2"></i>Rechercher
                    </button>
                    <button type="button" class="btn btn-warning shadow-sm" style="border-radius: 25px; padding: 10px 20px; font-weight: 500; font-size: 13px;">
                        <i class="fas fa-file-excel mr-2"></i>Exporter Excel
                    </button>
                    <div class="ml-auto d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 10px 20px; border-radius: 25px; font-size: 13px; font-weight: 500;">
                        <i class="fas fa-box mr-2"></i>Total: <strong class="ml-1">{{ $stats['total'] ?? 0 }}</strong>&nbsp;commandes
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Enregistrer Commande -->
        <div class="modal fade" id="createCommandeModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Enregistrer une commande</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('commandes.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Communes</label>
                                <input type="text" name="communes" class="form-control" placeholder="Commune destination" required>
                            </div>
                            <div class="form-group">
                                <label>Coût Global</label>
                                <input type="number" name="cout_global" class="form-control" placeholder="Coût global Colis" min="0" required>
                            </div>
                            <div class="form-group">
                                <label>Coût Livraison</label>
                                <select name="cout_livraison" class="form-control" required>
                                    <option value="1000">1000</option>
                                    <option value="1500">1500</option>
                                    <option value="2000">2000</option>
                                    <option value="2500">2500</option>
                                    <option value="3000">3000</option>
                                    <option value="3500">3500</option>
                                    <option value="4000">4000</option>
                                    <option value="5000">5000</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Imprimer -->
        <div class="modal fade" id="printModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imprimer un point</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('commandes.print') }}" method="GET" target="_blank">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Client</label>
                                <select name="client" class="form-control">
                                    <option value="">{{ session('boutique.nom') ?? session('client.nom') . ' ' . session('client.prenoms') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Sélectionner la date</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Imprimer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Recherche -->
        <div class="modal fade" id="searchModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-search mr-2"></i>Rechercher</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('commandes.index') }}" method="GET">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Recherche</label>
                                <input type="text" name="search" class="form-control" placeholder="ID, commune..." value="{{ request('search') }}">
                            </div>
                            <div class="form-group">
                                <label>Statut</label>
                                <select name="statut" class="form-control">
                                    <option value="">Tous</option>
                                    <option value="En attente" {{ request('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="En cours" {{ request('statut') == 'En cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="Livré" {{ request('statut') == 'Livré' ? 'selected' : '' }}>Livré</option>
                                    <option value="Non livré" {{ request('statut') == 'Non livré' ? 'selected' : '' }}>Non livré</option>
                                    <option value="Retour" {{ request('statut') == 'Retour' ? 'selected' : '' }}>Retour</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date début</label>
                                        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date fin</label>
                                        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('commandes.index') }}" class="btn btn-secondary"><i class="fas fa-times mr-1"></i>Reset</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i>Rechercher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-box mr-2"></i>Liste des commandes</h3>
            </div>
            <div class="card-body table-responsive p-0">
                @if($commandes->count() > 0)
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Commune</th>
                                <th>Montant Global</th>
                                <th>Frais livraison</th>
                                <th>Montant réel</th>
                                <th>Statut</th>
                                <th>Livreur</th>
                                <th>Date réception</th>
                                <th>Date livraison</th>
                                <th>Date retour</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commandes as $cmd)
                                <tr>
                                    <td>{{ $cmd->communes ?? '-' }}</td>
                                    <td><strong>{{ number_format($cmd->cout_global ?? 0, 0, ',', ' ') }}</strong></td>
                                    <td>{{ number_format($cmd->cout_livraison ?? 0, 0, ',', ' ') }}</td>
                                    <td>{{ number_format($cmd->cout_reel ?? 0, 0, ',', ' ') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($cmd->statut) {
                                                'Livré' => 'success',
                                                'Non livré', 'Non Livré' => 'danger',
                                                'Retour' => 'secondary',
                                                'En cours' => 'primary',
                                                default => 'warning'
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $badgeClass }}">{{ $cmd->statut ?? 'En attente' }}</span>
                                    </td>
                                    <td>{{ $cmd->livreur ? $cmd->livreur->nom . ' ' . $cmd->livreur->prenoms : '-' }}</td>
                                    <td>{{ $cmd->date_reception ? \Carbon\Carbon::parse($cmd->date_reception)->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $cmd->date_livraison ? \Carbon\Carbon::parse($cmd->date_livraison)->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $cmd->date_retour ? \Carbon\Carbon::parse($cmd->date_retour)->format('d-m-Y') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('commandes.show', $cmd->id) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                        <p>Aucune commande trouvée</p>
                    </div>
                @endif
            </div>
            @if($commandes->hasPages())
                <div class="card-footer">
                    {{ $commandes->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
@endsection
