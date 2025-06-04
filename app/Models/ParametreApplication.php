<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParametreApplication extends Model
{
    use HasFactory;

    protected $table = "parametres_application";

    protected $fillable = [
        "dossier_id",
        "cle",
        "valeur",
        "description",
        "type_valeur",
    ];

    protected $casts = [
        // Cast value based on type_valeur if needed, e.g., using an accessor
    ];

    /**
     * Get the dossier associated with this parameter (if dossier-specific).
     */
    public function dossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class);
    }

    // Add an accessor to automatically cast the value based on type_valeur
    public function getValeurAttribute($value)
    {
        switch ($this->type_valeur) {
            case 'integer':
                return (int) $value;
            case 'decimal':
                return (float) $value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    // Add a mutator to handle setting the value correctly
    public function setValeurAttribute($newValue)
    {
        if ($this->type_valeur === 'json' && is_array($newValue)) {
            $this->attributes['valeur'] = json_encode($newValue);
        } elseif ($this->type_valeur === 'boolean') {
            $this->attributes['valeur'] = $newValue ? 'true' : 'false';
        } else {
            $this->attributes['valeur'] = $newValue;
        }
    }

}
