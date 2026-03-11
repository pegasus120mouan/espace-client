<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Session::has('client')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = hash('sha256', $request->input('password'));

        $utilisateur = Utilisateur::where('login', $login)
            ->where('password', $password)
            ->where('role', 'clients')
            ->first();

        if ($utilisateur) {
            if ($utilisateur->statut_compte == 0) {
                return back()->withErrors(['login' => 'Votre compte est désactivé. Contactez l\'administrateur.'])->withInput();
            }

            Session::put('client', [
                'id' => $utilisateur->id,
                'nom' => $utilisateur->nom,
                'prenoms' => $utilisateur->prenoms,
                'login' => $utilisateur->login,
                'contact' => $utilisateur->contact,
                'avatar' => $utilisateur->avatar,
                'boutique_id' => $utilisateur->boutique_id,
            ]);

            if ($utilisateur->boutique) {
                Session::put('boutique', [
                    'id' => $utilisateur->boutique->id,
                    'nom' => $utilisateur->boutique->nom,
                    'logo' => $utilisateur->boutique->logo,
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Connexion réussie!');
        }

        return back()->withErrors(['login' => 'Login ou mot de passe incorrect.'])->withInput();
    }

    public function logout()
    {
        Session::forget('client');
        Session::forget('boutique');
        Session::flush();
        return redirect()->route('login')->with('success', 'Déconnexion réussie!');
    }

    public function dashboard()
    {
        if (!Session::has('client')) {
            return redirect()->route('login');
        }

        $client = Session::get('client');
        $boutique = Session::get('boutique');
        $clientId = $client['id'] ?? null;

        // Dates du mois en cours
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = \Carbon\Carbon::now()->endOfMonth()->toDateString();

        // Commandes du mois en cours
        $commandesMois = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->whereBetween('date_reception', [$startOfMonth, $endOfMonth])
            ->get();

        // Stats du mois
        $nbColisRecusMois = $commandesMois->count();
        $nbColisLivresMois = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Livré')
            ->whereBetween('date_livraison', [$startOfMonth, $endOfMonth])
            ->count();
        $nbColisNonLivresMois = $commandesMois->where('statut', 'Non Livré')->count();
        $nbColisRetoursMois = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Retour')
            ->whereBetween('date_retour', [$startOfMonth, $endOfMonth])
            ->count();

        // Montants du mois
        $montantLivresMois = (int) \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Livré')
            ->whereBetween('date_livraison', [$startOfMonth, $endOfMonth])
            ->sum('cout_reel');
        $fraisLivraisonMois = (int) \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Livré')
            ->whereBetween('date_livraison', [$startOfMonth, $endOfMonth])
            ->sum('cout_livraison');

        // Points validés du mois
        $nbPointsValidesMois = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('point_valide', true)
            ->whereBetween('date_validation_point', [$startOfMonth, $endOfMonth])
            ->count();
        $montantPointsValidesMois = (int) \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('point_valide', true)
            ->whereBetween('date_validation_point', [$startOfMonth, $endOfMonth])
            ->sum('cout_reel');

        // Paiements du mois
        $nbPaiementsEffectuesMois = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('paiement_effectue', true)
            ->whereBetween('date_paiement', [$startOfMonth, $endOfMonth])
            ->count();
        $montantPaiementsEffectuesMois = (int) \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('paiement_effectue', true)
            ->whereBetween('date_paiement', [$startOfMonth, $endOfMonth])
            ->sum('cout_reel');

        // Labels et data pour le graphique
        $statutsLabels = ['Livrés', 'Non Livrés', 'Retours'];
        $statutsData = [$nbColisLivresMois, $nbColisNonLivresMois, $nbColisRetoursMois];

        // Top 5 des communes les plus livrées du mois
        $topCommunesMois = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Livré')
            ->whereBetween('date_livraison', [$startOfMonth, $endOfMonth])
            ->selectRaw('communes, COUNT(*) as total')
            ->groupBy('communes')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Top 5 des communes pour les expéditions (colis reçus) du mois
        $topExpeditionsMois = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->whereBetween('date_reception', [$startOfMonth, $endOfMonth])
            ->selectRaw('communes, COUNT(*) as total')
            ->groupBy('communes')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Évolution mensuelle des livraisons sur l'année
        $evolutionMensuelle = [];
        $moisLabels = [];
        for ($i = 1; $i <= 12; $i++) {
            $debutMois = \Carbon\Carbon::create(null, $i, 1)->startOfMonth()->toDateString();
            $finMois = \Carbon\Carbon::create(null, $i, 1)->endOfMonth()->toDateString();
            $moisLabels[] = \Carbon\Carbon::create(null, $i, 1)->translatedFormat('M');
            $evolutionMensuelle[] = \App\Models\Commande::where('utilisateur_id', $clientId)
                ->where('statut', 'Livré')
                ->whereBetween('date_livraison', [$debutMois, $finMois])
                ->count();
        }

        // Comparaison avec le mois précédent
        $moisPrecedentDebut = \Carbon\Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $moisPrecedentFin = \Carbon\Carbon::now()->subMonth()->endOfMonth()->toDateString();

        $nbColisRecusMoisPrec = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->whereBetween('date_reception', [$moisPrecedentDebut, $moisPrecedentFin])
            ->count();
        $nbColisLivresMoisPrec = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Livré')
            ->whereBetween('date_livraison', [$moisPrecedentDebut, $moisPrecedentFin])
            ->count();
        $montantLivresMoisPrec = (int) \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Livré')
            ->whereBetween('date_livraison', [$moisPrecedentDebut, $moisPrecedentFin])
            ->sum('cout_reel');

        // Calcul des variations
        $variationRecus = $nbColisRecusMoisPrec > 0 ? round((($nbColisRecusMois - $nbColisRecusMoisPrec) / $nbColisRecusMoisPrec) * 100, 1) : 0;
        $variationLivres = $nbColisLivresMoisPrec > 0 ? round((($nbColisLivresMois - $nbColisLivresMoisPrec) / $nbColisLivresMoisPrec) * 100, 1) : 0;
        $variationMontant = $montantLivresMoisPrec > 0 ? round((($montantLivresMois - $montantLivresMoisPrec) / $montantLivresMoisPrec) * 100, 1) : 0;

        // Dernières commandes
        $dernieresCommandes = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'client',
            'boutique',
            'nbColisRecusMois',
            'nbColisLivresMois',
            'nbColisNonLivresMois',
            'nbColisRetoursMois',
            'montantLivresMois',
            'fraisLivraisonMois',
            'nbPointsValidesMois',
            'montantPointsValidesMois',
            'nbPaiementsEffectuesMois',
            'montantPaiementsEffectuesMois',
            'statutsLabels',
            'statutsData',
            'topCommunesMois',
            'topExpeditionsMois',
            'evolutionMensuelle',
            'moisLabels',
            'variationRecus',
            'variationLivres',
            'variationMontant',
            'dernieresCommandes'
        ));
    }

    public function dashboardAnnee()
    {
        if (!Session::has('client')) {
            return redirect()->route('login');
        }

        $client = Session::get('client');
        $boutique = Session::get('boutique');
        $clientId = $client['id'] ?? null;

        // Dates de l'année en cours
        $startOfYear = \Carbon\Carbon::now()->startOfYear()->toDateString();
        $endOfYear = \Carbon\Carbon::now()->endOfYear()->toDateString();

        // Commandes de l'année en cours
        $commandesAnnee = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->whereBetween('date_reception', [$startOfYear, $endOfYear])
            ->get();

        // Stats de l'année
        $nbColisRecusAnnee = $commandesAnnee->count();
        $nbColisLivresAnnee = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Livré')
            ->whereBetween('date_livraison', [$startOfYear, $endOfYear])
            ->count();
        $nbColisNonLivresAnnee = $commandesAnnee->where('statut', 'Non Livré')->count();
        $nbColisRetoursAnnee = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Retour')
            ->whereBetween('date_retour', [$startOfYear, $endOfYear])
            ->count();

        // Montants de l'année
        $montantLivresAnnee = (int) \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Livré')
            ->whereBetween('date_livraison', [$startOfYear, $endOfYear])
            ->sum('cout_reel');
        $fraisLivraisonAnnee = (int) \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Livré')
            ->whereBetween('date_livraison', [$startOfYear, $endOfYear])
            ->sum('cout_livraison');

        // Points validés de l'année
        $nbPointsValidesAnnee = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('point_valide', true)
            ->whereBetween('date_validation_point', [$startOfYear, $endOfYear])
            ->count();
        $montantPointsValidesAnnee = (int) \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('point_valide', true)
            ->whereBetween('date_validation_point', [$startOfYear, $endOfYear])
            ->sum('cout_reel');

        // Paiements de l'année
        $nbPaiementsEffectuesAnnee = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('paiement_effectue', true)
            ->whereBetween('date_paiement', [$startOfYear, $endOfYear])
            ->count();
        $montantPaiementsEffectuesAnnee = (int) \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('paiement_effectue', true)
            ->whereBetween('date_paiement', [$startOfYear, $endOfYear])
            ->sum('cout_reel');

        // Labels et data pour le graphique
        $statutsLabels = ['Livrés', 'Non Livrés', 'Retours'];
        $statutsData = [$nbColisLivresAnnee, $nbColisNonLivresAnnee, $nbColisRetoursAnnee];

        // Top 5 des communes les plus livrées de l'année
        $topCommunesAnnee = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->where('statut', 'Livré')
            ->whereBetween('date_livraison', [$startOfYear, $endOfYear])
            ->selectRaw('communes, COUNT(*) as total')
            ->groupBy('communes')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Top 5 des communes pour les expéditions (colis reçus) de l'année
        $topExpeditionsAnnee = \App\Models\Commande::where('utilisateur_id', $clientId)
            ->whereBetween('date_reception', [$startOfYear, $endOfYear])
            ->selectRaw('communes, COUNT(*) as total')
            ->groupBy('communes')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Évolution mensuelle des livraisons sur l'année
        $evolutionMensuelle = [];
        $moisLabels = [];
        for ($i = 1; $i <= 12; $i++) {
            $debutMois = \Carbon\Carbon::create(null, $i, 1)->startOfMonth()->toDateString();
            $finMois = \Carbon\Carbon::create(null, $i, 1)->endOfMonth()->toDateString();
            $moisLabels[] = \Carbon\Carbon::create(null, $i, 1)->translatedFormat('M');
            $evolutionMensuelle[] = \App\Models\Commande::where('utilisateur_id', $clientId)
                ->where('statut', 'Livré')
                ->whereBetween('date_livraison', [$debutMois, $finMois])
                ->count();
        }

        return view('dashboard-annee', compact(
            'client',
            'boutique',
            'nbColisRecusAnnee',
            'nbColisLivresAnnee',
            'nbColisNonLivresAnnee',
            'nbColisRetoursAnnee',
            'montantLivresAnnee',
            'fraisLivraisonAnnee',
            'nbPointsValidesAnnee',
            'montantPointsValidesAnnee',
            'nbPaiementsEffectuesAnnee',
            'montantPaiementsEffectuesAnnee',
            'statutsLabels',
            'statutsData',
            'topCommunesAnnee',
            'topExpeditionsAnnee',
            'evolutionMensuelle',
            'moisLabels'
        ));
    }
}
