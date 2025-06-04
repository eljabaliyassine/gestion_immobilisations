<?php

namespace App\Exports;

use App\Models\Immobilisation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class ImmobilisationsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $dossierId;
    protected $filters;

    public function __construct(int $dossierId, array $filters = [])
    {
        $this->dossierId = $dossierId;
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function query()
    {
        $query = Immobilisation::query()
            ->where("dossier_id", $this->dossierId)
            ->with(["famille", "site", "service", "fournisseur"]) // Eager load relationships
            ->orderBy("code_barre"); // Or order as needed

        // Apply filters passed from the controller
        if (!empty($this->filters["filter_code_barre"])) {
            $query->where("code_barre", "like", "%" . $this->filters["filter_code_barre"] . "%");
        }
        if (!empty($this->filters["filter_designation"])) {
            $query->where("designation", "like", "%" . $this->filters["filter_designation"] . "%");
        }
        if (!empty($this->filters["filter_famille_id"])) {
            $query->where("famille_id", $this->filters["filter_famille_id"]);
        }
        if (!empty($this->filters["filter_site_id"])) {
            $query->where("site_id", $this->filters["filter_site_id"]);
        }
        if (!empty($this->filters["filter_service_id"])) {
            $query->where("service_id", $this->filters["filter_service_id"]);
        }
        // Add other filters if needed

        return $query;
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            "Code Barre",
            "Désignation",
            "Description",
            "Famille Code",
            "Famille Libellé",
            "Statut",
            "Date Acquisition",
            "Valeur Acquisition",
            "Fournisseur Code",
            "Fournisseur Nom",
            "Numéro Facture",
            "Site Code",
            "Site Libellé",
            "Service Code",
            "Service Libellé",
            "Emplacement",
            // Add amortissement details later if needed
            "Date Création",
            "Date Modification",
        ];
    }

    /**
    * @param Immobilisation $immobilisation
    * @return array
    */
    public function map($immobilisation): array
    {
        return [
            $immobilisation->code_barre,
            $immobilisation->designation,
            $immobilisation->description,
            $immobilisation->famille->code ?? ",
            $immobilisation->famille->libelle ?? ",
            $immobilisation->statut,
            $immobilisation->date_acquisition ? $immobilisation->date_acquisition->format("Y-m-d") : ",
            $immobilisation->valeur_acquisition, // Format as number?
            $immobilisation->fournisseur->code ?? ",
            $immobilisation->fournisseur->nom ?? ",
            $immobilisation->numero_facture,
            $immobilisation->site->code ?? ",
            $immobilisation->site->libelle ?? ",
            $immobilisation->service->code ?? ",
            $immobilisation->service->libelle ?? ",
            $immobilisation->emplacement,
            $immobilisation->created_at ? $immobilisation->created_at->format("Y-m-d H:i:s") : ",
            $immobilisation->updated_at ? $immobilisation->updated_at->format("Y-m-d H:i:s") : ",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ["font" => ["bold" => true]],
        ];
    }
}

