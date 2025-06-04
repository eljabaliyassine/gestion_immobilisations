<?php

namespace App\Http\Controllers;

use App\Models\PlanAmortissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ExportT16Controller extends Controller
{
    /**
     * Récupère l'ID du dossier courant depuis la session
     */
    private function getCurrentDossierId()
    {
        return session("current_dossier_id") ?? session("dossier_id");
    }

    /**
     * Exporte le Tableau 16 (Dotations aux amortissements) au format CSV.
     * Format optimisé pour Excel avec encodage UTF-8 avec BOM et séparateur point-virgule.
     */
    public function exportTableau16(Request $request)
    {
        $dossierId = $this->getCurrentDossierId();
        $dateDerniereCloture = $request->date_derniere_cloture;
        $dateProchaineCloture = $request->date_prochaine_cloture;
        
        // Vérifier si les dates sont fournies, sinon utiliser celles en session
        if (!$dateDerniereCloture || !$dateProchaineCloture) {
            $dateDerniereCloture = session('date_derniere_cloture');
            $dateProchaineCloture = session('date_prochaine_cloture');
        }
        
        // Si toujours pas de dates, utiliser les dates par défaut
        if (!$dateDerniereCloture || !$dateProchaineCloture) {
            $dateDerniereCloture = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
            $dateProchaineCloture = Carbon::now()->endOfYear()->format('Y-m-d');
        }
        
        // Générer le nom du fichier
        $fileName = 'tableau16_dotations_amortissements_' . Carbon::now()->format('Ymd_His') . '.csv';
        
        // Créer le fichier CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        // Créer le callback pour générer le contenu CSV
        $callback = function() use ($dossierId, $dateDerniereCloture, $dateProchaineCloture) {
            // Ouvrir le fichier en sortie
            $file = fopen('php://output', 'w');
            
            // Ajouter le BOM UTF-8 pour que Excel reconnaisse l'encodage
            fputs($file, "\xEF\xBB\xBF");
            
            // Écrire l'en-tête du tableau
            fputcsv($file, ['Tableau 16 : Dotations aux amortissements'], ';');
            fputcsv($file, ['Date dernière clôture', $dateDerniereCloture], ';');
            fputcsv($file, ['Date clôture', $dateProchaineCloture], ';');
            fputcsv($file, [''], ';'); // Ligne vide pour séparation
            
            // Écrire les en-têtes des colonnes selon le format demandé
            fputcsv($file, [
                'Immobilisations concernées',
                'Date d\'entrée (1)',
                'Prix d\'acquisition (2)',
                'Valeur comptable après réévaluation',
                'Amortissements antérieurs (3)',
                'Taux en %',
                'Durée en années (4)',
                'Amortissements de l\'exercice',
                'Cumul amortissements (col. 4 + col. 7)',
                'Observations (5)'
            ], ';');
            
            // Écrire la ligne des numéros de colonnes
            fputcsv($file, [
                'Nom rubrique',
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9'
            ], ';');
            
            // Récupérer les données regroupées par famille
            $familles = DB::table('plans_amortissement as p')
                ->join('immobilisations as i', 'p.immobilisation_id', '=', 'i.id')
                ->where('p.dossier_id', $dossierId)
                ->where('i.statut', 'actif')
                ->where('p.date_derniere_cloture', $dateDerniereCloture)
                ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                ->select('p.famille', DB::raw('COUNT(*) as count'))
                ->groupBy('p.famille')
                ->get();
            
            // Variables pour les totaux
            $totalPrixAcquisition = 0;
            $totalValeurComptable = 0;
            $totalAmortissementsAnterieurs = 0;
            $totalAmortissementsExercice = 0;
            $totalCumulAmortissements = 0;
            
            // Générer les lignes pour chaque famille
            foreach ($familles as $famille) {
                // Récupérer les données agrégées pour cette famille
                $data = DB::table('plans_amortissement as p')
                    ->join('immobilisations as i', 'p.immobilisation_id', '=', 'i.id')
                    ->where('p.dossier_id', $dossierId)
                    ->where('i.statut', 'actif')
                    ->where('p.famille', $famille->famille)
                    ->where('p.date_derniere_cloture', $dateDerniereCloture)
                    ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                    ->select(
                        DB::raw('MIN(p.immobilisation_date_mise_service) as date_entree'),
                        DB::raw('SUM(p.base_amortissable) as prix_acquisition'),
                        DB::raw('SUM(p.base_amortissable) as valeur_comptable'), // Généralement identique au prix d'acquisition
                        DB::raw('SUM(p.amortissement_cumule_debut) as amortissements_anterieurs'),
                        DB::raw('AVG(p.taux_applique) as taux_moyen'),
                        DB::raw('AVG(p.duree_amortissement_par_defaut) as duree_moyenne'),
                        DB::raw('SUM(p.dotation_periode) as amortissements_exercice'),
                        DB::raw('SUM(p.amortissement_cumule_fin) as cumul_amortissements')
                    )
                    ->first();
                
                if (!$data) continue;
                
                // Formater les valeurs
                $dateEntree = $data->date_entree ? Carbon::parse($data->date_entree)->format('d/m/Y') : '';
                $prixAcquisition = $data->prix_acquisition;
                $valeurComptable = $data->valeur_comptable;
                $amortissementsAnterieurs = $data->amortissements_anterieurs;
                $tauxMoyen = $data->taux_moyen * 100; // Convertir en pourcentage
                $dureeMoyenne = round($data->duree_moyenne); // Arrondir à l'entier le plus proche
                $amortissementsExercice = $data->amortissements_exercice;
                $cumulAmortissements = $data->cumul_amortissements;
                
                // Ajouter aux totaux
                $totalPrixAcquisition += $prixAcquisition;
                $totalValeurComptable += $valeurComptable;
                $totalAmortissementsAnterieurs += $amortissementsAnterieurs;
                $totalAmortissementsExercice += $amortissementsExercice;
                $totalCumulAmortissements += $cumulAmortissements;
                
                // Écrire la ligne pour cette famille
                fputcsv($file, [
                    $famille->famille,
                    $dateEntree,
                    number_format($prixAcquisition, 2, ',', ' '),
                    number_format($valeurComptable, 2, ',', ' '),
                    number_format($amortissementsAnterieurs, 2, ',', ' '),
                    number_format($tauxMoyen, 0, ',', '') . '%',
                    $dureeMoyenne,
                    number_format($amortissementsExercice, 2, ',', ' '),
                    number_format($cumulAmortissements, 2, ',', ' '),
                    '' // Observations vides
                ], ';');
            }
            
            // Écrire la ligne des totaux
            fputcsv($file, [
                'TOTAL',
                '',
                number_format($totalPrixAcquisition, 2, ',', ' '),
                number_format($totalValeurComptable, 2, ',', ' '),
                number_format($totalAmortissementsAnterieurs, 2, ',', ' '),
                '',
                '',
                number_format($totalAmortissementsExercice, 2, ',', ' '),
                number_format($totalCumulAmortissements, 2, ',', ' '),
                ''
            ], ';');
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}
