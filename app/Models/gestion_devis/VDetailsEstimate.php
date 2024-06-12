<?php

namespace App\Models\gestion_devis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use NumberFormatter;

class VDetailsEstimate extends Model
{
    use HasFactory;
    protected $table = 'v_details_estimate';

///Fonctions
    //Avoir le formattage en nombre d'un decimal
    public static function getFormatNumber($decimalNumber) {
        $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

        $amountFormated = $formatter->format($decimalNumber);

        return $amountFormated;
    }
}
