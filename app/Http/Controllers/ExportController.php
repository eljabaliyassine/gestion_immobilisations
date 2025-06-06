<?php

namespace App\Http\Controllers;

use App\Models\Immobilisation;
use App\Models\PlanAmortissement;
use App\Models\ProvisionDepreciation;
use App\Models\MouvementImmobilisation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExportController extends Controller
{
    /**
     * Récupère l'ID du dossier courant depuis la session
     * Méthode ajoutée pour cohérence avec les autres contrôleurs
     */
    private function getCurrentDossierId()
    {
        return session("current_dossier_id") ?? session("dossier_id");
    }

    /**
     * Display the export selection page.
     */
    public function index(): View
    {
        return view("exports.index");
    }

    /**
     * Generate CSV file for Tableau 4 (Immobilisations).
     * Tableau 4 : Immobilisations par famille avec montants bruts début/fin exercice et mouvements
     */
    public function exportTableau4(): StreamedResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $filename = "tableau4_immobilisations_" . date("Ymd_His") . ".csv";

        // Récupérer les dates de clôture depuis la session
        $dateDerniereCloture = session('date_derniere_cloture') ?? Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
        $dateProchaineCloture = session('date_prochaine_cloture') ?? Carbon::now()->endOfYear()->format('Y-m-d');

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($dossierId, $dateDerniereCloture, $dateProchaineCloture) {
            $file = fopen("php://output", "w");
            // Add BOM for UTF-8 compatibility in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // En-tête avec les dates de clôture
            fputcsv($file, ["Tableau 4"], ";");
            fputcsv($file, ["Date dernière clôture", $dateDerniereCloture], ";");
            fputcsv($file, ["Date clôture", $dateProchaineCloture], ";");
            fputcsv($file, ["Immobilisations du dossier courant et statut \"actif\" et période uniquement"], ";");
            fputcsv($file, [""], ";"); // Ligne vide pour séparation

            // En-tête des colonnes
            fputcsv($file, [
                "Famille",
                "Montant brut début exercice",
                "Acquisition",
                "Production par l'Etps pour elle même",
                "Virement",
                "Cession",
                "Retrait",
                "Virement",
                "Montant brut fin exercice"
            ], ";");

            // Récupérer les données des plans d'amortissement regroupées par famille
            $familles = DB::table('plans_amortissement as p')
                ->join('immobilisations as i', 'p.immobilisation_id', '=', 'i.id')
                ->where('p.dossier_id', $dossierId)
                ->where('i.statut', 'actif')
                ->where('p.date_derniere_cloture', $dateDerniereCloture)
                ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                ->select('p.famille', DB::raw('COUNT(*) as count'))
                ->groupBy('p.famille')
                ->get();

            $totalDebutExercice = 0;
            $totalAcquisition = 0;
            $totalProduction = 0;
            $totalVirement1 = 0;
            $totalCession = 0;
            $totalRetrait = 0;
            $totalVirement2 = 0;
            $totalFinExercice = 0;

            // Générer les lignes pour chaque famille
            foreach ($familles as $famille) {
                // Montant brut début exercice (somme des base_amortissable pour les immobilisations acquises avant ou à la date de dernière clôture)
                $montantDebutExercice = DB::table('plans_amortissement as p')
                    ->join('immobilisations as i', 'p.immobilisation_id', '=', 'i.id')
                    ->where('p.dossier_id', $dossierId)
                    ->where('i.statut', 'actif')
                    ->where('p.famille', $famille->famille)
                    ->where('p.date_derniere_cloture', $dateDerniereCloture)
                    ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                    ->where('p.immobilisation_date_acquisition', '<=', $dateDerniereCloture)
                    ->sum('p.base_amortissable');

                // Acquisitions (somme des base_amortissable pour les immobilisations acquises entre les dates de clôture)
                $montantAcquisition = DB::table('plans_amortissement as p')
                    ->join('immobilisations as i', 'p.immobilisation_id', '=', 'i.id')
                    ->where('p.dossier_id', $dossierId)
                    ->where('i.statut', 'actif')
                    ->where('p.famille', $famille->famille)
                    ->where('p.date_derniere_cloture', $dateDerniereCloture)
                    ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                    ->where('p.immobilisation_date_acquisition', '>', $dateDerniereCloture)
                    ->where('p.immobilisation_date_acquisition', '<=', $dateProchaineCloture)
                    ->sum('p.base_amortissable');

                // Les autres colonnes sont à 0 comme spécifié dans les critères
                $montantProduction = 0;
                $montantVirement1 = 0;
                $montantCession = 0;
                $montantRetrait = 0;
                $montantVirement2 = 0;

                // Montant brut fin exercice (somme des base_amortissable pour les immobilisations acquises avant ou à la date de prochaine clôture)
                $montantFinExercice = DB::table('plans_amortissement as p')
                    ->join('immobilisations as i', 'p.immobilisation_id', '=', 'i.id')
                    ->where('p.dossier_id', $dossierId)
                    ->where('i.statut', 'actif')
                    ->where('p.famille', $famille->famille)
                    ->where('p.date_derniere_cloture', $dateDerniereCloture)
                    ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                    ->where('p.immobilisation_date_acquisition', '<=', $dateProchaineCloture)
                    ->sum('p.base_amortissable');

                // Ajouter aux totaux
                $totalDebutExercice += $montantDebutExercice;
                $totalAcquisition += $montantAcquisition;
                $totalProduction += $montantProduction;
                $totalVirement1 += $montantVirement1;
                $totalCession += $montantCession;
                $totalRetrait += $montantRetrait;
                $totalVirement2 += $montantVirement2;
                $totalFinExercice += $montantFinExercice;

                // Écrire la ligne pour cette famille
                fputcsv($file, [
                    $famille->famille,
                    number_format($montantDebutExercice, 2, ',', ' '),
                    number_format($montantAcquisition, 2, ',', ' '),
                    number_format($montantProduction, 2, ',', ' '),
                    number_format($montantVirement1, 2, ',', ' '),
                    number_format($montantCession, 2, ',', ' '),
                    number_format($montantRetrait, 2, ',', ' '),
                    number_format($montantVirement2, 2, ',', ' '),
                    number_format($montantFinExercice, 2, ',', ' ')
                ], ";");
            }

            // Écrire la ligne des totaux
            fputcsv($file, [
                "Total",
                number_format($totalDebutExercice, 2, ',', ' '),
                number_format($totalAcquisition, 2, ',', ' '),
                number_format($totalProduction, 2, ',', ' '),
                number_format($totalVirement1, 2, ',', ' '),
                number_format($totalCession, 2, ',', ' '),
                number_format($totalRetrait, 2, ',', ' '),
                number_format($totalVirement2, 2, ',', ' '),
                number_format($totalFinExercice, 2, ',', ' ')
            ], ";");

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate CSV file for Tableau 8 (Amortissements).
     * Tableau 8 : Amortissements par famille avec cumuls début/fin période et dotations
     */
    public function exportTableau8(): StreamedResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $filename = "tableau8_amortissements_" . date("Ymd_His") . ".csv";

        // Récupérer les dates de clôture depuis la session
        $dateDerniereCloture = session('date_derniere_cloture') ?? Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
        $dateProchaineCloture = session('date_prochaine_cloture') ?? Carbon::now()->endOfYear()->format('Y-m-d');

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($dossierId, $dateDerniereCloture, $dateProchaineCloture) {
            $file = fopen("php://output", "w");
            // Add BOM for UTF-8 compatibility in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // En-tête avec les dates de clôture
            fputcsv($file, ["Tableau 8"], ";");
            fputcsv($file, ["Date dernière clôture", $dateDerniereCloture], ";");
            fputcsv($file, ["Date clôture", $dateProchaineCloture], ";");
            fputcsv($file, ["Immobilisations du dossier courant et statut \"actif\" uniquement"], ";");
            fputcsv($file, [""], ";"); // Ligne vide pour séparation

            // En-tête des colonnes
            fputcsv($file, [
                "Famille",
                "Cumul début période",
                "Dotation",
                "Amortissements sur immobilisations sorties",
                "Cumul d'amortissement fin période"
            ], ";");

            // Récupérer les données des plans d'amortissement regroupées par famille
            $familles = DB::table('plans_amortissement as p')
                ->join('immobilisations as i', 'p.immobilisation_id', '=', 'i.id')
                ->where('p.dossier_id', $dossierId)
                ->where('i.statut', 'actif')
                ->where('p.date_derniere_cloture', $dateDerniereCloture)
                ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                ->select('p.famille', DB::raw('COUNT(*) as count'))
                ->groupBy('p.famille')
                ->get();

            $totalCumulDebut = 0;
            $totalDotation = 0;
            $totalAmortSorties = 0;
            $totalCumulFin = 0;

            // Générer les lignes pour chaque famille
            foreach ($familles as $famille) {
                // Cumul début période (somme des amortissement_cumule_debut pour les immobilisations acquises avant ou à la date de dernière clôture)
                $cumulDebut = DB::table('plans_amortissement as p')
                    ->join('immobilisations as i', 'p.immobilisation_id', '=', 'i.id')
                    ->where('p.dossier_id', $dossierId)
                    ->where('i.statut', 'actif')
                    ->where('p.famille', $famille->famille)
                    ->where('p.date_derniere_cloture', $dateDerniereCloture)
                    ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                    ->where('p.immobilisation_date_acquisition', '<=', $dateDerniereCloture)
                    ->sum('p.amortissement_cumule_debut');

                // Dotation (somme des dotation_periode pour les immobilisations acquises entre les dates de clôture)
                $dotation = DB::table('plans_amortissement as p')
                    ->join('immobilisations as i', 'p.immobilisation_id', '=', 'i.id')
                    ->where('p.dossier_id', $dossierId)
                    ->where('i.statut', 'actif')
                    ->where('p.famille', $famille->famille)
                    ->where('p.date_derniere_cloture', $dateDerniereCloture)
                    ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                    ->where('p.immobilisation_date_acquisition', '<=', $dateProchaineCloture)
                    ->sum('p.dotation_periode');

                // Amortissements sur immobilisations sorties (toujours 0 selon les critères)
                $amortSorties = 0;

                // Cumul fin période (somme des amortissement_cumule_fin pour les immobilisations acquises avant ou à la date de prochaine clôture)
                $cumulFin = DB::table('plans_amortissement as p')
                    ->join('immobilisations as i', 'p.immobilisation_id', '=', 'i.id')
                    ->where('p.dossier_id', $dossierId)
                    ->where('i.statut', 'actif')
                    ->where('p.famille', $famille->famille)
                    ->where('p.date_derniere_cloture', $dateDerniereCloture)
                    ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                    ->where('p.immobilisation_date_acquisition', '<=', $dateProchaineCloture)
                    ->sum('p.amortissement_cumule_fin');

                // Ajouter aux totaux
                $totalCumulDebut += $cumulDebut;
                $totalDotation += $dotation;
                $totalAmortSorties += $amortSorties;
                $totalCumulFin += $cumulFin;

                // Écrire la ligne pour cette famille
                fputcsv($file, [
                    $famille->famille,
                    number_format($cumulDebut, 2, ',', ' '),
                    number_format($dotation, 2, ',', ' '),
                    number_format($amortSorties, 2, ',', ' '),
                    number_format($cumulFin, 2, ',', ' ')
                ], ";");
            }

            // Écrire la ligne des totaux
            fputcsv($file, [
                "Total",
                number_format($totalCumulDebut, 2, ',', ' '),
                number_format($totalDotation, 2, ',', ' '),
                number_format($totalAmortSorties, 2, ',', ' '),
                number_format($totalCumulFin, 2, ',', ' ')
            ], ";");

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate CSV file for accounting entries (Ecritures comptables).
     * Version ULTRA SIMPLIFIÉE utilisant les champs comptecompta de plans_amortissement
     */
    public function exportEcrituresComptables(): StreamedResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $filename = "ecritures_comptables_" . date("Ymd_His") . ".csv";

        // Récupérer les dates de clôture depuis la session
        $dateDerniereCloture = session('date_derniere_cloture') ?? Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
        $dateProchaineCloture = session('date_prochaine_cloture') ?? Carbon::now()->endOfYear()->format('Y-m-d');

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($dossierId, $dateDerniereCloture, $dateProchaineCloture) {
            $file = fopen("php://output", "w");
            // Add BOM for UTF-8 compatibility in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // En-tête des colonnes
            fputcsv($file, [
                "Date",
                "Journal",
                "Compte",
                "Libellé",
                "Débit",
                "Crédit",
                "Référence Pièce"
            ], ";");

            // Calculer le jour suivant la date de dernière clôture
            $jourApresDerniereCloture = Carbon::parse($dateDerniereCloture)->addDay()->format('Y-m-d');

            // Formater les dates pour l'affichage et la référence pièce
            $jourApresDerniereClotureFormatee = Carbon::parse($jourApresDerniereCloture)->format('d/m/Y');
            $dateProchaineClotureFormatee = Carbon::parse($dateProchaineCloture)->format('d/m/Y');

            // Créer la référence pièce avec le jour suivant la date de dernière clôture
            $referencePiece = "DEA du " . $jourApresDerniereClotureFormatee . " au " . $dateProchaineClotureFormatee;

            // ÉCRITURES DE DOTATION (DÉBIT) - Une requête ultra simple
            $comptesDotation = DB::table('plans_amortissement as p')
                ->join('comptescompta as c', 'p.comptecompta_dotation_id', '=', 'c.id')
                ->where('p.dossier_id', $dossierId)
                ->where('p.date_derniere_cloture', $dateDerniereCloture)
                ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                ->select('c.numero', DB::raw('SUM(p.dotation_periode) as montant_total'))
                ->groupBy('c.numero')
                ->get();

            // Générer les écritures comptables pour les comptes de dotation (Débit)
            foreach ($comptesDotation as $compte) {
                fputcsv($file, [
                    $dateProchaineClotureFormatee, // Date
                    "OD",                          // Journal
                    $compte->numero,               // Compte
                    $referencePiece,               // Libellé
                    number_format($compte->montant_total, 2, ',', ' '), // Débit
                    '',                            // Crédit
                    $referencePiece                // Référence Pièce
                ], ";");
            }

            // ÉCRITURES D'AMORTISSEMENT (CRÉDIT) - Une requête ultra simple
            $comptesAmortissement = DB::table('plans_amortissement as p')
                ->join('comptescompta as c', 'p.comptecompta_amortissement_id', '=', 'c.id')
                ->where('p.dossier_id', $dossierId)
                ->where('p.date_derniere_cloture', $dateDerniereCloture)
                ->where('p.date_prochaine_cloture', $dateProchaineCloture)
                ->select('c.numero', DB::raw('SUM(p.dotation_periode) as montant_total'))
                ->groupBy('c.numero')
                ->get();

            // Générer les écritures comptables pour les comptes d'amortissement (Crédit)
            foreach ($comptesAmortissement as $compte) {
                fputcsv($file, [
                    $dateProchaineClotureFormatee, // Date
                    "OD",                          // Journal
                    $compte->numero,               // Compte
                    $referencePiece,               // Libellé
                    '',                            // Débit
                    number_format($compte->montant_total, 2, ',', ' '), // Crédit
                    $referencePiece                // Référence Pièce
                ], ";");
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    /**
     * Generate CSV file for raw Immobilisations table export.
     */
    public function exportImmobilisationsTable(): StreamedResponse
    {
        $dossierId = $this->getCurrentDossierId();
        $filename = "export_table_immobilisations_" . date("Ymd_His") . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($dossierId) {
            $file = fopen("php://output", "w");
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Get columns dynamically from the first record
            $firstImmo = Immobilisation::where('dossier_id', $dossierId)->first();
            if (!$firstImmo) {
                fputcsv($file, ["Aucune immobilisation trouvée pour ce dossier"], ";");
                fclose($file);
                return;
            }

            $columns = array_keys($firstImmo->getAttributes());
            fputcsv($file, $columns, ";");

            // Export all records in chunks
            Immobilisation::where('dossier_id', $dossierId)
                ->chunk(200, function ($immobilisations) use ($file, $columns) {
                    foreach ($immobilisations as $immo) {
                        $row = [];
                        foreach ($columns as $column) {
                            $value = $immo->$column;
                            // Format dates and numbers if needed
                            if ($value instanceof \Carbon\Carbon) {
                                $value = $value->format('d/m/Y');
                            } elseif (is_numeric($value) && strpos($column, 'valeur') !== false) {
                                $value = number_format($value, 2, ',', ' ');
                            }
                            $row[] = $value;
                        }
                        fputcsv($file, $row, ";");
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

