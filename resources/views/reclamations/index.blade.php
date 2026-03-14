@extends('layouts.main')

@section('title', 'Mes Réclamations')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-exclamation-triangle text-warning mr-2"></i>Mes Réclamations</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Mes Réclamations</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Historique de mes réclamations</h3>
            </div>
            <div class="card-body table-responsive p-0">
                @if($reclamations->count() > 0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Commune</th>
                                <th>Type</th>
                                <th>Montant actuel</th>
                                <th>Montant réclamé</th>
                                <th>Nouveau montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reclamations as $reclamation)
                                <tr>
                                    <td>{{ $reclamation->created_at ? $reclamation->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td>{{ $reclamation->commande->communes ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-warning">{{ $reclamation->type_label }}</span>
                                    </td>
                                    <td><strong>{{ number_format($reclamation->montant_actuel ?? 0, 0, ',', ' ') }} XOF</strong></td>
                                    <td>
                                        @if($reclamation->montant_reclame)
                                            <strong class="text-primary">{{ number_format($reclamation->montant_reclame, 0, ',', ' ') }} XOF</strong>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($reclamation->statut == 'acceptee')
                                            <strong class="text-success">{{ number_format($reclamation->commande->cout_reel ?? 0, 0, ',', ' ') }} XOF</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $reclamation->statut_badge_class }}">{{ $reclamation->statut_label }}</span>
                                    </td>
                                </tr>

                                <!-- Modal Réponse -->
                                @if($reclamation->reponse_admin)
                                <div class="modal fade" id="reponseModal{{ $reclamation->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-{{ $reclamation->statut_badge_class }}">
                                                <h5 class="modal-title text-white">
                                                    <i class="fas fa-reply mr-2"></i>Réponse à votre réclamation
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <strong>Commune:</strong> {{ $reclamation->commande->communes ?? '-' }}<br>
                                                    <strong>Type:</strong> {{ $reclamation->type_label }}<br>
                                                    <strong>Statut:</strong> <span class="badge badge-{{ $reclamation->statut_badge_class }}">{{ $reclamation->statut_label }}</span>
                                                </div>
                                                <hr>
                                                <div class="alert alert-{{ $reclamation->statut === 'acceptee' ? 'success' : 'danger' }}">
                                                    <strong>Réponse de l'équipe OVL:</strong><br>
                                                    {{ $reclamation->reponse_admin }}
                                                </div>
                                                @if($reclamation->date_traitement)
                                                    <small class="text-muted">Traité le {{ $reclamation->date_traitement->format('d/m/Y à H:i') }}</small>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">Aucune réclamation en attente</p>
                    </div>
                @endif
            </div>
            @if($reclamations->hasPages())
                <div class="card-footer">
                    {{ $reclamations->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
