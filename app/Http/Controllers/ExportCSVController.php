<?php

namespace App\Http\Controllers;

use App\Models\PlanAmortissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ExportCSVController extends Controller
{
    /**
     * Récupère l'ID du dossier courant depuis la session
     */
    private function getCurrentDossierId()
    {
        return session("current_dossier_id") ?? session("dossier_id");
    }

    /**
     * Exporte tous les champs de la table plans_amortissement au format CSV.
     * Optimisé pour Excel avec encodage UTF-8 avec BOM et séparateur point-virgule.
     */
    public function exportPlanAmortissementCSV(Request $request)
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

        // Récupérer tous les plans d'amortissement du dossier courant
        $plans = DB::table('plans_amortissement')
            ->where('dossier_id', $dossierId)
            ->where('date_derniere_cloture', $dateDerniereCloture)
            ->where('date_prochaine_cloture', $dateProchaineCloture)
            ->orderBy('immobilisations_libelle')
            ->orderBy('annee_exercice')
            ->get();

        // Générer le nom du fichier
        $fileName = 'plan_amortissement_complet_' . Carbon::now()->format('Ymd_His') . '.csv';

        // Créer le fichier CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Créer le callback pour générer le contenu CSV
        $callback = function() use ($plans) {
            // Ouvrir le fichier en sortie
            $file = fopen('php://output', 'w');

            // Ajouter le BOM UTF-8 pour que Excel reconnaisse l'encodage
            fputs($file, "\xEF\xBB\xBF");

            // Si aucun plan trouvé, retourner un en-tête vide
            if ($plans->isEmpty()) {
                fputcsv($file, ['Aucun plan d\'amortissement trouvé pour le dossier courant et les dates sélectionnées.'], ';');
                fclose($file);
                return;
            }

            // Récupérer les noms des colonnes à partir du premier enregistrement
            $columns = array_keys((array)$plans->first());

            // Écrire l'en-tête CSV avec point-virgule comme séparateur
            fputcsv($file, $columns, ';');

            // Écrire les données
            foreach ($plans as $plan) {
                $row = (array)$plan;

                // Formater les dates pour Excel (format dd/mm/yyyy)
                foreach (['immobilisation_date_acquisition', 'immobilisation_date_mise_service', 'date_derniere_cloture', 'date_prochaine_cloture', 'created_at', 'updated_at'] as $dateField) {
                    if (isset($row[$dateField]) && !empty($row[$dateField])) {
                        try {
                            $date = Carbon::parse($row[$dateField]);
                            if (in_array($dateField, ['created_at', 'updated_at'])) {
                                $row[$dateField] = $date->format('d/m/Y H:i:s');
                            } else {
                                $row[$dateField] = $date->format('d/m/Y');
                            }
                        } catch (\Exception $e) {
                            // Garder la valeur originale si le parsing échoue
                        }
                    }
                }

                // Formater les nombres pour Excel (remplacer le point par la virgule)
                foreach (['base_amortissable', 'taux_applique', 'dotation_annuelle', 'amortissement_cumule_debut', 'amortissement_cumule_fin', 'vna_fin_exercice', 'dotation_periode'] as $numField) {
                    if (isset($row[$numField]) && is_numeric($row[$numField])) {
                        // Formater avec 2 décimales et remplacer le point par la virgule
                        $row[$numField] = str_replace('.', ',', number_format((float)$row[$numField], 2, '.', ''));
                    }
                }

                // Écrire la ligne avec point-virgule comme séparateur
                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}