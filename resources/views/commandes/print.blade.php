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
        @if($pointDejaValide)
            <button onclick="showDejaValideModal()" style="padding: 10px 30px; background: #6c757d; color: #fff; border: none; border-radius: 25px; cursor: not-allowed; font-size: 14px;">
                <i class="fas fa-check-double"></i> Point déjà validé
            </button>
        @elseif($reclamationsEnAttente > 0)
            <button onclick="showReclamationsEnAttenteModal()" style="padding: 10px 30px; background: #ffc107; color: #000; border: none; border-radius: 25px; cursor: not-allowed; font-size: 14px;">
                <i class="fas fa-exclamation-triangle"></i> Réclamations en cours ({{ $reclamationsEnAttente }})
            </button>
        @else
            <button onclick="validerPoint()" style="padding: 10px 30px; background: #28a745; color: #fff; border: none; border-radius: 25px; cursor: pointer; font-size: 14px;">
                <i class="fas fa-check"></i> Valider le point
            </button>
        @endif
    </div>

    <!-- Modal Point déjà validé -->
    <div id="dejaValideModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: #fff; border-radius: 16px; padding: 0; width: 420px; max-width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; animation: modalSlide 0.3s ease;">
            <div style="background: linear-gradient(135deg, #f0ad4e 0%, #ec971f 100%); padding: 25px 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                    <svg width="30" height="30" fill="#fff" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                </div>
                <h3 style="color: #fff; margin: 0; font-size: 20px; font-weight: 600;">Point déjà validé</h3>
            </div>
            <div style="padding: 30px; text-align: center;">
                <p style="color: #333; font-size: 16px; margin: 0 0 10px;">Ce point a déjà été validé.</p>
                <p style="color: #666; font-size: 13px; margin: 0;">Le point du <strong>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</strong> a été validé précédemment. Vous ne pouvez pas le valider à nouveau.</p>
            </div>
            <div style="padding: 20px 30px 25px; display: flex; gap: 12px; justify-content: center; border-top: 1px solid #eee;">
                <button onclick="closeDejaValideModal()" style="padding: 12px 40px; background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: #fff; border: none; border-radius: 25px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.2s;">
                    Compris
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Réclamations en attente -->
    <div id="reclamationsEnAttenteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: #fff; border-radius: 16px; padding: 0; width: 450px; max-width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; animation: modalSlide 0.3s ease;">
            <div style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); padding: 25px 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.3); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                    <svg width="30" height="30" fill="#000" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                </div>
                <h3 style="color: #000; margin: 0; font-size: 20px; font-weight: 600;">Validation impossible</h3>
            </div>
            <div style="padding: 30px; text-align: center;">
                <p style="color: #333; font-size: 16px; margin: 0 0 15px; font-weight: 500;">
                    <i class="fas fa-exclamation-circle" style="color: #ffc107;"></i> 
                    Vous avez <strong>{{ $reclamationsEnAttente ?? 0 }}</strong> réclamation(s) en attente
                </p>
                <p style="color: #666; font-size: 14px; margin: 0; line-height: 1.6;">
                    Vous ne pouvez pas valider ce point tant que vos réclamations n'ont pas été traitées par notre équipe.
                </p>
                <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 12px; margin-top: 20px;">
                    <p style="color: #856404; font-size: 13px; margin: 0;">
                        <i class="fas fa-info-circle"></i> 
                        Veuillez patienter, notre équipe traite vos réclamations dans les plus brefs délais.
                    </p>
                </div>
            </div>
            <div style="padding: 20px 30px 25px; display: flex; gap: 12px; justify-content: center; border-top: 1px solid #eee;">
                <a href="{{ route('reclamations.index') }}" style="padding: 12px 25px; background: #17a2b8; color: #fff; border: none; border-radius: 25px; cursor: pointer; font-size: 14px; font-weight: 500; text-decoration: none;">
                    <i class="fas fa-list"></i> Voir mes réclamations
                </a>
                <button onclick="closeReclamationsEnAttenteModal()" style="padding: 12px 30px; background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: #fff; border: none; border-radius: 25px; cursor: pointer; font-size: 14px; font-weight: 500;">
                    Compris
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation professionnel -->
    <div id="confirmModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: #fff; border-radius: 16px; padding: 0; width: 400px; max-width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; animation: modalSlide 0.3s ease;">
            <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 25px 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                    <svg width="30" height="30" fill="#fff" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                </div>
                <h3 style="color: #fff; margin: 0; font-size: 20px; font-weight: 600;">Confirmation</h3>
            </div>
            <div style="padding: 30px; text-align: center;">
                <p style="color: #333; font-size: 16px; margin: 0 0 10px;">Êtes-vous sûr de vouloir valider ce point ?</p>
                <p style="color: #666; font-size: 13px; margin: 0;">Cette action est irréversible.</p>
            </div>
            <div style="padding: 20px 30px 25px; display: flex; gap: 12px; justify-content: center; border-top: 1px solid #eee;">
                <button onclick="closeModal()" style="padding: 12px 30px; background: #f1f3f4; color: #333; border: none; border-radius: 25px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.2s;">
                    Annuler
                </button>
                <button onclick="confirmerValidation()" style="padding: 12px 30px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #fff; border: none; border-radius: 25px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.2s;">
                    <span style="margin-right: 5px;">✓</span> Confirmer
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes modalSlide {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        #confirmModal button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>

    <!-- Modal Réclamation -->
    <div id="reclamationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: #fff; border-radius: 16px; padding: 0; width: 450px; max-width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; animation: modalSlide 0.3s ease;">
            <div style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); padding: 25px 30px; text-align: center;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                    <svg width="30" height="30" fill="#fff" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                </div>
                <h3 style="color: #fff; margin: 0; font-size: 20px; font-weight: 600;">Signaler une erreur</h3>
            </div>
            <form id="reclamationForm" action="{{ route('commandes.reclamation') }}" method="POST">
                @csrf
                <input type="hidden" name="commande_id" id="reclamation_commande_id">
                <div style="padding: 25px 30px;">
                    <div style="background: #f8f9fa; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px;">
                        <p style="margin: 0; font-size: 13px; color: #666;">
                            <strong>Commune:</strong> <span id="reclamation_commune"></span><br>
                            <strong>Montant actuel:</strong> <span id="reclamation_montant"></span> XOF
                        </p>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333; font-size: 13px;">Type d'erreur</label>
                        <select name="type_erreur" required style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px;">
                            <option value="">-- Sélectionner --</option>
                            <option value="montant_incorrect">Montant incorrect</option>
                            <option value="commune_incorrecte">Commune incorrecte</option>
                            <option value="statut_incorrect">Statut incorrect</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333; font-size: 13px;">Montant correct (si applicable)</label>
                        <input type="number" name="montant_correct" placeholder="Ex: 14500" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 13px;">
                    </div>
                </div>
                <div style="padding: 15px 30px 25px; display: flex; gap: 12px; justify-content: center; border-top: 1px solid #eee;">
                    <button type="button" onclick="closeReclamationModal()" style="padding: 12px 30px; background: #f1f3f4; color: #333; border: none; border-radius: 25px; cursor: pointer; font-size: 14px; font-weight: 500;">
                        Annuler
                    </button>
                    <button type="submit" style="padding: 12px 30px; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: #fff; border: none; border-radius: 25px; cursor: pointer; font-size: 14px; font-weight: 500;">
                        Envoyer la réclamation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validerPoint() {
            document.getElementById('confirmModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }
        function confirmerValidation() {
            window.location.href = '{{ route("commandes.valider", ["date" => $date]) }}';
        }
        function showDejaValideModal() {
            document.getElementById('dejaValideModal').style.display = 'flex';
        }
        function closeDejaValideModal() {
            document.getElementById('dejaValideModal').style.display = 'none';
        }
        function showReclamationsEnAttenteModal() {
            document.getElementById('reclamationsEnAttenteModal').style.display = 'flex';
        }
        function closeReclamationsEnAttenteModal() {
            document.getElementById('reclamationsEnAttenteModal').style.display = 'none';
        }
        function ouvrirReclamation(commandeId, commune, montant) {
            document.getElementById('reclamation_commande_id').value = commandeId;
            document.getElementById('reclamation_commune').textContent = commune;
            document.getElementById('reclamation_montant').textContent = new Intl.NumberFormat('fr-FR').format(montant);
            document.getElementById('reclamationModal').style.display = 'flex';
        }
        function closeReclamationModal() {
            document.getElementById('reclamationModal').style.display = 'none';
        }
        // Fermer les modals en cliquant à l'extérieur
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.getElementById('dejaValideModal').addEventListener('click', function(e) {
            if (e.target === this) closeDejaValideModal();
        });
        document.getElementById('reclamationModal').addEventListener('click', function(e) {
            if (e.target === this) closeReclamationModal();
        });
        document.getElementById('reclamationsEnAttenteModal').addEventListener('click', function(e) {
            if (e.target === this) closeReclamationsEnAttenteModal();
        });
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
                    <th class="text-center no-print">Action</th>
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
                        <td class="text-center no-print">
                            @if(isset($reclamationsParCommande[$cmd->id]))
                                @if($reclamationsParCommande[$cmd->id] == 'en_attente')
                                    <span style="padding: 4px 10px; background: #ffc107; color: #000; border-radius: 15px; font-size: 10px;">
                                        <i class="fas fa-clock"></i> Réclamation envoyée
                                    </span>
                                @else
                                    <span style="padding: 4px 10px; background: #28a745; color: #fff; border-radius: 15px; font-size: 10px;">
                                        <i class="fas fa-check"></i> Réclamation traitée
                                    </span>
                                @endif
                            @else
                                <button onclick="ouvrirReclamation({{ $cmd->id }}, '{{ $cmd->communes }}', {{ $cmd->cout_reel ?? 0 }})" style="padding: 4px 10px; background: #dc3545; color: #fff; border: none; border-radius: 15px; cursor: pointer; font-size: 10px;">
                                    <i class="fas fa-exclamation-triangle"></i> Signaler
                                </button>
                            @endif
                        </td>
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
