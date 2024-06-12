<?php

namespace App\Models\gestion_devis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NumberFormatter;
use Carbon\Carbon;

class VEstimateProgress extends Model
{
    use HasFactory;
    protected $table = 'v_estimate_progress';

///Fonctions
    //Avoir la date fin du devis
    public function getDateEnd() {
        $dateEnd = Carbon::parse($this->start_date);
        $dateEnd->addDays($this->duration);

        return $dateEnd->toDateString();
    }

    //Avoir le montant total d'un devis
    public function getAmountTotal() {
        $amountTotal = $this->amount_total;

        $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

        $amountFormated = $formatter->format($amountTotal);

        return $amountFormated;
    }

    //Avoir le montant total d'un devis
    public function getAmounFormat($amount) {
        $amountTotal = $amount;

        $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

        $amountFormated = $formatter->format($amountTotal);

        return $amountFormated;
    }

     //Avoir le paiement effectue total d'un devis
     public function getPaymentTotal() {
        $amountTotal = $this->amount_payed;

        $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

        $amountFormated = $formatter->format($amountTotal);

        return $amountFormated;
    }
}
