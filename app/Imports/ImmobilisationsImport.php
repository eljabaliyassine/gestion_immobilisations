<?php

namespace App\Imports;

use App\Models\Immobilisation;
use App\Models\Famille;
use App\Models\Site;
use App\Models\Service;
use App\Models\Fournisseur;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Throwable;

class ImmobilisationsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    private $dossierId;
    private $importedCount = 0;
    private $skippedCount = 0;
    private $skippedRows = [];

    // Cache for related models to reduce DB queries
    private $famillesCache = [];
    private $sitesCache = [];
    private $servicesCache = [];
    private $fournisseursCache = [];

    public function __construct(int $dossierId)
    {
        $this->dossierId = $dossierId;
        // Preload caches if necessary, or load on demand
        $this->famillesCache = Famille::where("dossier_id", $this->dossierId)->pluck("id", "code")->toArray();
        $this->sitesCache = Site::where("dossier_id", $this->dossierId)->pluck("id", "code")->toArray();
        // Services depend on site, load dynamically or handle carefully
        $this->fournisseursCache = Fournisseur::where("dossier_id", $this->dossierId)->pluck("id", "code")->toArray();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. Find related models using codes (case-insensitive lookup?)
        $familleId = $this->famillesCache[strtoupper($row["famille_code"] ?? ".")] ?? null;
        $siteId = $this->sitesCache[strtoupper($row["site_code"] ?? ".")] ?? null;
        $fournisseurId = isset($row["fournisseur_code"]) && !empty($row["fournisseur_code"]) ? ($this->fournisseursCache[strtoupper($row["fournisseur_code"]) ?? "."] ?? null) : null;

        // Find service based on site_id and service_code
        $serviceId = null;
        if ($siteId && isset($row["service_code"])) {
            // Optimize: Cache services per site if importing many rows for the same site
            $service = Service::where("dossier_id", $this->dossierId)
                              ->where("site_id", $siteId)
                              ->whereRaw("UPPER(code) = ?", [strtoupper($row["service_code"])])
                              ->first();
            $serviceId = $service ? $service->id : null;
        }

        // 2. Basic check: Skip row if essential related models not found
        if (!$familleId || !$siteId || !$serviceId) {
            $this->skippedCount++;
            $this->skippedRows[] = ["row" => $row, "reason" => "Famille, Site ou Service introuvable via code."];
            Log::warning("Skipping row during import: Famille, Site or Service not found", ["row" => $row, "dossier" => $this->dossierId]);
            return null; // Skip this row
        }

        // 3. Prepare data for Immobilisation model
        $data = [
            "dossier_id" => $this->dossierId,
            "code_barre" => $row["code_barre"],
            "designation" => $row["designation"],
            "description" => $row["description"] ?? null,
            "famille_id" => $familleId,
            "statut" => $row["statut"] ?? "En service", // Default status
            "date_acquisition" => $this->transformDate($row["date_acquisition"]),
            "valeur_acquisition" => $this->transformNumber($row["valeur_acquisition"]),
            "fournisseur_id" => $fournisseurId,
            "numero_facture" => $row["numero_facture"] ?? null,
            "site_id" => $siteId,
            "service_id" => $serviceId,
            "emplacement" => $row["emplacement"] ?? null,
            // photo_path cannot be imported this way
        ];

        // 4. Create or Update (optional: add logic to update based on code_barre?)
        // For now, we only create, relying on validation rules to prevent duplicates.
        $immo = new Immobilisation($data);
        $this->importedCount++;
        return $immo;
    }

    public function rules(): array
    {
        // Define validation rules for each column
        return [
            "code_barre" => ["required", "string", "max:100", Rule::unique("immobilisations")->where("dossier_id", $this->dossierId)],
            "designation" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "famille_code" => ["required", "string", Rule::exists("familles", "code")->where("dossier_id", $this->dossierId)],
            "statut" => ["required", Rule::in(["En service", "En stock", "En réparation", "Cédé", "Rebut"])],
            "date_acquisition" => ["required" /* Handled by transformDate */],
            "valeur_acquisition" => ["required" /* Handled by transformNumber */],
            "fournisseur_code" => ["nullable", "string", Rule::exists("fournisseurs", "code")->where("dossier_id", $this->dossierId)],
            "numero_facture" => ["nullable", "string", "max:100"],
            "site_code" => ["required", "string", Rule::exists("sites", "code")->where("dossier_id", $this->dossierId)],
            "service_code" => ["required", "string" /* Further validation in model() */],
            "emplacement" => ["nullable", "string", "max:255"],
        ];
    }

    public function customValidationMessages()
    {
        return [
            "code_barre.unique" => "Le code barre existe déjà pour ce dossier.",
            "famille_code.exists" => "Le code famille n'existe pas dans les paramètres.",
            "fournisseur_code.exists" => "Le code fournisseur n'existe pas dans les paramètres.",
            "site_code.exists" => "Le code site n'existe pas dans les paramètres.",
            "statut.in" => "Le statut doit être parmi : En service, En stock, En réparation, Cédé, Rebut.",
        ];
    }

    // SkipsOnError allows continuing import even if some rows have errors
    public function onError(Throwable $e)
    {
        // Log the error
        Log::error("Import Error: " . $e->getMessage(), ["trace" => $e->getTraceAsString()]);
        $this->skippedCount++;
        // Optionally add row info to skippedRows if possible from the exception context
    }

    // SkipsOnFailure allows collecting validation failures without stopping
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->skippedCount++;
            $this->skippedRows[] = [
                "row_number" => $failure->row(),
                "attribute" => $failure->attribute(),
                "errors" => $failure->errors(),
                "values" => $failure->values()
            ];
            Log::warning("Import Validation Failure", [
                "row" => $failure->row(),
                "attribute" => $failure->attribute(),
                "errors" => $failure->errors(),
                "values" => $failure->values(),
                "dossier" => $this->dossierId
            ]);
        }
    }

    // Helper to transform Excel date (serial number or string) to Carbon object
    private function transformDate($value): ?Carbon
    {
        if (empty($value)) return null;
        try {
            // Handle Excel serial date number
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            }
            // Handle various string formats
            return Carbon::parse($value);
        } catch (\Exception $e) {
            Log::error("Date transformation error during import: ".$value, ["error" => $e->getMessage()]);
            // Throw validation error or return null?
            // Returning null might cause validation failure later if date is required.
            // Forcing a validation failure might be better.
             throw new \InvalidArgumentException("Format de date invalide pour la valeur: " . $value);
            // return null;
        }
    }

     // Helper to transform number (potentially with comma as decimal separator)
    private function transformNumber($value): ?float
    {
        if ($value === null || $value === ") return null;
        if (is_numeric($value)) return (float) $value;
        // Try replacing comma with dot for decimal separator
        $value = str_replace(",", ".", $value);
        if (is_numeric($value)) return (float) $value;

        Log::error("Number transformation error during import: ".$value);
        throw new \InvalidArgumentException("Format de nombre invalide pour la valeur: " . $value);
        // return null;
    }

    // Getters for import summary
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

     public function getSkippedRows(): array
    {
        return $this->skippedRows;
    }
}

