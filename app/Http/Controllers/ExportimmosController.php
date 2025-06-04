<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ExportimmosController extends Controller
{
    /**
     * Récupère l'ID du dossier courant depuis la session
     */
    private function getCurrentDossierId()
    {
        return session("current_dossier_id") ?? session("dossier_id");
    }

    /**
     * Exporte tous les champs de la table immobilisations du dossier courant au format CSV.
     * Tous les statuts d'immobilisations sont inclus, sans restriction de période.
     * Format optimisé pour Excel avec encodage UTF-8 avec BOM et séparateur point-virgule.
     */
    public function exportImmobilisationsCompletes(Request $request)
    {
        $dossierId = $this->getCurrentDossierId();
        
        // Générer le nom du fichier
        $fileName = 'immobilisations_completes_' . Carbon::now()->format('Ymd_His') . '.csv';
        
        // Créer le fichier CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        // Créer le callback pour générer le contenu CSV
        $callback = function() use ($dossierId) {
            // Ouvrir le fichier en sortie
            $file = fopen('php://output', 'w');
            
            // Ajouter le BOM UTF-8 pour que Excel reconnaisse l'encodage
            fputs($file, "\xEF\xBB\xBF");
            
            // Récupérer toutes les immobilisations du dossier courant (tous statuts confondus)
            $immobilisations = DB::table('immobilisations as i')
                ->leftJoin('familles as f', 'i.famille_id', '=', 'f.id')
                ->leftJoin('sites as s', 'i.site_id', '=', 's.id')
                ->leftJoin('services as srv', 'i.service_id', '=', 'srv.id')
                ->leftJoin('fournisseurs as four', 'i.fournisseur_id', '=', 'four.id')
                ->leftJoin('comptescompta as cc_immo', 'i.comptecompta_immobilisation_id', '=', 'cc_immo.id')
                ->leftJoin('comptescompta as cc_amort', 'i.comptecompta_amortissement_id', '=', 'cc_amort.id')
                ->leftJoin('comptescompta as cc_dot', 'i.comptecompta_dotation_id', '=', 'cc_dot.id')
                ->leftJoin('comptescompta as cc_tva', 'i.comptecompta_tva_id', '=', 'cc_tva.id')
                ->where('i.dossier_id', $dossierId)
                ->select(
                    'i.id',
                    'i.dossier_id',
                    'i.numero',
                    'i.code',
                    'i.libelle',
                    'i.designation',
                    'i.description',
                    'i.famille_id',
                    'f.libelle as famille_libelle',
                    'i.site_id',
                    's.libelle as site_libelle',
                    'i.service_id',
                    'srv.libelle as service_libelle',
                    'i.date_acquisition',
                    'i.date_mise_service',
                    'i.valeur_acquisition',
                    'i.tva_deductible',
                    'i.comptecompta_immobilisation_id',
                    'cc_immo.numero as compte_immobilisation_numero',
                    'cc_immo.libelle as compte_immobilisation_libelle',
                    'i.comptecompta_amortissement_id',
                    'cc_amort.numero as compte_amortissement_numero',
                    'cc_amort.libelle as compte_amortissement_libelle',
                    'i.comptecompta_dotation_id',
                    'cc_dot.numero as compte_dotation_numero',
                    'cc_dot.libelle as compte_dotation_libelle',
                    'i.comptecompta_tva_id',
                    'cc_tva.numero as compte_tva_numero',
                    'cc_tva.libelle as compte_tva_libelle',
                    'i.duree_amortissement',
                    'i.methode_amortissement',
                    'i.coefficient_degressif',
                    'i.base_amortissement',
                    'i.valeur_residuelle',
                    'i.amortissements_cumules',
                    'i.statut',
                    'i.date_sortie',
                    'i.prix_vente',
                    'i.fournisseur_id',
                    'four.nom as fournisseur_nom',
                    'i.numero_facture',
                    'i.numero_serie',
                    'i.code_barre',
                    'i.photo_path',
                    'i.document_path',
                    'i.created_at',
                    'i.updated_at'
                )
                ->orderBy('i.numero')
                ->get();
            
            // Si aucune immobilisation trouvée, retourner un en-tête vide
            if ($immobilisations->isEmpty()) {
                fputcsv($file, ['Aucune immobilisation trouvée pour le dossier courant.'], ';');
                fclose($file);
                return;
            }
            
            // Récupérer les noms des colonnes à partir du premier enregistrement
            $columns = array_keys((array)$immobilisations->first());
            
            // Écrire l'en-tête CSV avec point-virgule comme séparateur
            fputcsv($file, $columns, ';');
            
            // Écrire les données
            foreach ($immobilisations as $immobilisation) {
                $row = (array)$immobilisation;
                
                // Formater les dates pour Excel (format dd/mm/yyyy)
                foreach (['date_acquisition', 'date_mise_service', 'date_sortie', 'created_at', 'updated_at'] as $dateField) {
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
                foreach (['valeur_acquisition', 'tva_deductible', 'coefficient_degressif', 'base_amortissement', 'valeur_residuelle', 'amortissements_cumules', 'prix_vente'] as $numField) {
                    if (isset($row[$numField]) && is_numeric($row[$numField])) {
                        // Formater avec 2 décimales et remplacer le point par la virgule
                        $row[$numField] = str_replace('.', ',', number_format((float)$row[$numField], 2, '.', ' '));
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
