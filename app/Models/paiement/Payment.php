<?php

namespace App\Models\paiement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\gestion_devis\VEstimateProgress;
use Exception;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payment';
    protected $primaryKey = 'id_payment';

///Validation
    //Valider le montant
    public function setAmount($amount, $id_estimate) {
        if (!isset($amount) || !is_numeric($amount)) {
            throw new Exception('Veuillez entrer un nombre dans le champ type montant');
        }
        $estimateProgress = VEstimateProgress::where('id_estimate', $id_estimate)->first();
        $restePaye = $estimateProgress->amount_total - $estimateProgress->amount_payed;
        if($restePaye == 0) {
            $error = 'Vous ne pouvez pas effectuer cette operation, votre paiement total est deja acheve';
            throw new Exception($error);
        }
        if(($restePaye - $amount) < 0) {
            $error = 'Votre paiement est superieur au montant total du devis que vous devrez payer = '.$estimateProgress->getAmountTotal().
            ' Il vous reste seulement a payer la somme de '.$estimateProgress->getAmounFormat($restePaye);
            throw new Exception($error);
        }
        $this->amount = $amount;
    }

//Valider la date debut
    public function setDatePayment($date) {
        if(!isset($date) || !strtotime($date)) {
            throw new Exception('Le champ date doit etre une date valide au format annee-mois-jour');
        } else {
            $dateParts = explode('-', $date);
            if(count($dateParts) != 3 || !checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
                throw new Exception('Le champ date doit etre au format annee-mois-jour');
            }
        }
        $this->date_payment = $date;
    }
}
