<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventaireDetail extends Model
{
    use HasFactory;

    protected $table = "inventaire_details";

    protected $fillable = [
        "inventaire_id",
        "immobilisation_id",
        "code_barre_scan",
        "date_scan",
        "statut_constate",
        "site_scan_id",
        "service_scan_id",
        "commentaire",
        "user_id",
    ];

    protected $casts = [
        "date_scan" => "datetime",
    ];

    /**
     * Get the inventaire session this detail belongs to.
     */
    public function inventaire(): BelongsTo
    {
        return $this->belongsTo(Inventaire::class);
    }

    /**
     * Get the immobilisation identified (if found in the database).
     */
    public function immobilisation(): BelongsTo
    {
        return $this->belongsTo(Immobilisation::class);
    }

    /**
     * Get the site where the item was scanned.
     */
    public function siteScanne(): BelongsTo
    {
        return $this->belongsTo(Site::class, "site_scan_id");
    }

    /**
     * Get the service where the item was scanned.
     */
    public function serviceScanne(): BelongsTo
    {
        return $this->belongsTo(Service::class, "service_scan_id");
    }

    /**
     * Get the user who performed the scan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor to get the dossier indirectly
     */
    public function getDossierAttribute()
    {
        return $this->inventaire->dossier;
    }

    /**
     * Alias for statut_constate to maintain compatibility with controller
     */
    public function getStatutComptageAttribute()
    {
        return $this->statut_constate;
    }

    /**
     * Mutator for statut_comptage to maintain compatibility with controller
     */
    public function setStatutComptageAttribute($value)
    {
        $this->attributes['statut_constate'] = $value;
    }

    /**
     * Alias for code_barre_scan to maintain compatibility with controller
     */
    public function getCodeBarreScanneAttribute()
    {
        return $this->code_barre_scan;
    }

    /**
     * Mutator for code_barre_scanne to maintain compatibility with controller
     */
    public function setCodeBarreScanneAttribute($value)
    {
        $this->attributes['code_barre_scan'] = $value;
    }
}
