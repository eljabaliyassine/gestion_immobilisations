<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\PlanAmortissement;
use Illuminate\Support\Collection;

class PlanAmortissementExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $dossierId;
    protected $dateDerniereCloture;
    protected $dateProchaineCloture;

    public function __construct($dossierId, $dateDerniereCloture, $dateProchaineCloture)
    {
        $this->dossierId = $dossierId;
        $this->dateDerniereCloture = $dateDerniereCloture;
        $this->dateProchaineCloture = $dateProchaineCloture;
    }

    public function collection()
    {
        return PlanAmortissement::where('dossier_id', $this->dossierId)
            ->where('date_derniere_cloture', $this->dateDerniereCloture)
            ->where('date_prochaine_cloture', $this->dateProchaineCloture)
            ->orderBy('immobilisations_libelle')
            ->orderBy('annee_exercice')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Immobilisation',
            'Description',
            'Famille',
            'Date acquisition',
            'Date mise en service',
            'Base amortissable',
            'Taux',
            'Durée période (jours)',
            'Dotation période',
            'Amortissement cumulé début',
            'Amortissement cumulé fin',
            'VNA fin exercice'
        ];
    }

    public function map($planAmortissement): array
    {
        return [
            $planAmortissement->immobilisations_libelle,
            $planAmortissement->immobilisations_description,
            $planAmortissement->famille,
            $planAmortissement->immobilisation_date_acquisition,
            $planAmortissement->immobilisation_date_mise_service,
            $planAmortissement->base_amortissable,
            $planAmortissement->taux_applique,
            $planAmortissement->duree_periode,
            $planAmortissement->dotation_periode,
            $planAmortissement->amortissement_cumule_debut,
            $planAmortissement->amortissement_cumule_fin,
            $planAmortissement->vna_fin_exercice
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style pour l'en-tête
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            // Style pour les données numériques
            'F:L' => [
                'numberFormat' => [
                    'formatCode' => '#,##0.00_-DH'
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ]
            ],
            // Style pour le taux
            'G' => [
                'numberFormat' => [
                    'formatCode' => '0.00%'
                ]
            ],
            // Style pour les dates
            'D:E' => [
                'numberFormat' => [
                    'formatCode' => 'dd/mm/yyyy'
                ]
            ]
        ];
    }

    public function title(): string
    {
        return 'Plan d\'amortissement';
    }
}
