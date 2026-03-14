<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('client')) {
            return redirect()->route('login');
        }

        $client = Session::get('client');
        $clientId = $client['id'] ?? null;

        $query = Commande::where('utilisateur_id', $clientId);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('communes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_reception', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_reception', '<=', $request->date_fin);
        }

        $commandes = $query->orderByDesc('id')->paginate(20);

        $stats = [
            'total' => Commande::where('utilisateur_id', $clientId)->count(),
            'livres' => Commande::where('utilisateur_id', $clientId)->where('statut', 'Livré')->count(),
            'en_cours' => Commande::where('utilisateur_id', $clientId)->whereIn('statut', ['En attente', 'En cours', 'Programmé'])->count(),
            'non_livres' => Commande::where('utilisateur_id', $clientId)->where('statut', 'Non livré')->count(),
            'retours' => Commande::where('utilisateur_id', $clientId)->where('statut', 'Retour')->count(),
        ];

        return view('commandes.index', compact('commandes', 'stats'));
    }

    public function show($id)
    {
        if (!Session::has('client')) {
            return redirect()->route('login');
        }

        $client = Session::get('client');
        $clientId = $client['id'] ?? null;

        $commande = Commande::where('utilisateur_id', $clientId)
            ->where('id', $id)
            ->firstOrFail();

        return view('commandes.show', compact('commande'));
    }

    public function store(Request $request)
    {
        if (!Session::has('client')) {
            return redirect()->route('login');
        }

        $request->validate([
            'communes' => 'required|string|max:255',
            'cout_global' => 'required|integer|min:0',
            'cout_livraison' => 'required|integer|min:0',
        ]);

        $client = Session::get('client');

        Commande::create([
            'utilisateur_id' => $client['id'],
            'communes' => $request->communes,
            'cout_global' => $request->cout_global,
            'cout_livraison' => $request->cout_livraison,
            'cout_reel' => $request->cout_global - $request->cout_livraison,
            'statut' => 'Non Livré',
            'date_reception' => now()->toDateString(),
        ]);

        return redirect()->route('commandes.index')->with('success', 'Commande enregistrée avec succès!');
    }

    public function print(Request $request)
    {
        if (!Session::has('client')) {
            return redirect()->route('login');
        }

        $client = Session::get('client');
        $clientId = $client['id'] ?? null;
        $date = $request->input('date', date('Y-m-d'));

        $commandes = Commande::where('utilisateur_id', $clientId)
            ->whereDate('date_livraison', $date)
            ->orderByDesc('id')
            ->get();

        $boutique = Session::get('boutique');

        // Vérifier si le point a déjà été validé pour cette date
        $pointDejaValide = Commande::where('utilisateur_id', $clientId)
            ->whereDate('date_livraison', $date)
            ->where('point_valide', true)
            ->exists();

        // Récupérer les réclamations avec leur statut
        $reclamationsParCommande = Reclamation::whereIn('commande_id', $commandes->pluck('id'))
            ->pluck('statut', 'commande_id')
            ->toArray();

        // Vérifier s'il y a des réclamations en attente
        $reclamationsEnAttente = Reclamation::whereIn('commande_id', $commandes->pluck('id'))
            ->where('statut', 'en_attente')
            ->count();

        return view('commandes.print', compact('commandes', 'client', 'boutique', 'date', 'pointDejaValide', 'reclamationsParCommande', 'reclamationsEnAttente'));
    }

    public function valider(Request $request)
    {
        if (!Session::has('client')) {
            return redirect()->route('login');
        }

        $client = Session::get('client');
        $clientId = $client['id'] ?? null;
        $date = $request->input('date', date('Y-m-d'));

        // Marquer les commandes comme validées
        Commande::where('utilisateur_id', $clientId)
            ->whereDate('date_livraison', $date)
            ->update([
                'point_valide' => true,
                'date_validation_point' => now(),
            ]);

        return redirect()->route('commandes.index')->with('success', 'Point du ' . \Carbon\Carbon::parse($date)->format('d/m/Y') . ' validé avec succès!');
    }

    public function pointsValides()
    {
        if (!Session::has('client')) {
            return redirect()->route('login');
        }

        $client = Session::get('client');
        $clientId = $client['id'] ?? null;
        $boutique = Session::get('boutique');

        // Grouper les commandes validées par date de livraison
        $pointsValides = Commande::where('utilisateur_id', $clientId)
            ->where('point_valide', true)
            ->whereNotNull('date_validation_point')
            ->select('date_livraison', 'date_validation_point')
            ->selectRaw('SUM(cout_reel) as montant_total')
            ->selectRaw('COUNT(*) as nombre_colis')
            ->selectRaw('MAX(paiement_effectue) as paiement_effectue')
            ->selectRaw('MAX(operateur_paiement) as operateur_paiement')
            ->selectRaw('MAX(date_paiement) as date_paiement')
            ->groupBy('date_livraison', 'date_validation_point')
            ->orderByDesc('date_validation_point')
            ->paginate(20);

        return view('commandes.points-valides', compact('pointsValides', 'boutique'));
    }

    public function reclamation(Request $request)
    {
        if (!Session::has('client')) {
            return redirect()->route('login');
        }

        $request->validate([
            'commande_id' => 'required|integer',
            'type_erreur' => 'required|string',
        ]);

        $client = Session::get('client');
        $clientId = $client['id'] ?? null;

        // Vérifier que la commande appartient bien au client
        $commande = Commande::where('id', $request->commande_id)
            ->where('utilisateur_id', $clientId)
            ->first();

        if (!$commande) {
            return back()->with('error', 'Commande non trouvée.');
        }

        // Vérifier si une réclamation existe déjà pour cette commande
        $existingReclamation = Reclamation::where('commande_id', $commande->id)
            ->where('statut', 'en_attente')
            ->first();

        if ($existingReclamation) {
            return back()->with('error', 'Une réclamation est déjà en cours pour cette commande.');
        }

        // Créer la réclamation dans la table reclamations
        Reclamation::create([
            'commande_id' => $commande->id,
            'utilisateur_id' => $clientId,
            'type_reclamation' => $request->type_erreur,
            'montant_actuel' => $commande->cout_reel,
            'montant_reclame' => $request->montant_correct,
            'statut' => 'en_attente',
        ]);

        return back()->with('success', 'Votre réclamation a été enregistrée. Notre équipe va la traiter rapidement.');
    }

    public function mesReclamations()
    {
        if (!Session::has('client')) {
            return redirect()->route('login');
        }

        $client = Session::get('client');
        $clientId = $client['id'] ?? null;
        $boutique = Session::get('boutique');

        $reclamations = Reclamation::with('commande')
            ->where('utilisateur_id', $clientId)
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('reclamations.index', compact('reclamations', 'boutique'));
    }
}
