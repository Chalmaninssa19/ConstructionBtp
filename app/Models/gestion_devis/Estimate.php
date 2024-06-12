<?php

namespace App\Models\gestion_devis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;
use NumberFormatter;

class Estimate extends Model
{
    use HasFactory;
    protected $table = 'estimate';
    protected $primaryKey = 'id_estimate';

///validation
    //Valider le type finition
    public function valideFinishType($value) {
        if (!isset($value) || !is_numeric($value)) {
            throw new Exception('Veuillez entrer un entier dans le champ type finition');
        }
    }

    //Valider la date debut
    public function valideDateStart($date) {
        if(!isset($date) || !strtotime($date)) {
            throw new Exception('Le champ date doit etre une date valide au format annee-mois-jour');
        } else {
            $dateParts = explode('-', $date);
            if(count($dateParts) != 3 || !checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
                throw new Exception('Le champ date doit etre au format annee-mois-jour');
            }
        }
    }

    public function valideLieu($value) {
        if (!isset($value) || empty($value) || !is_string($value)) {
            throw new Exception('Veuillez entrer une chaine de caractere dans le champ lieu');
        }
    }

///Fonctions 
    //Inserer les details du devis
    public function insertDetailEstimate() {
        try {
            $sql = "INSERT INTO estimate_detail (estimate_id, work_type_id, code, work_detail_designation, 
            unit_id, unit_price, quantity, amount)
            SELECT %d, w.work_type_id, d.code, d.designation, d.unit_id, d.unit_price, d.quantity, 
            d.unit_price*d.quantity AS amount 
            FROM v_house_and_work_type w
            JOIN work_type d
            ON w.work_type_id = d.id_work_type
            WHERE w.id_house_type=%d";

            $sql = sprintf($sql,$this->id_estimate, $this->id_house_type);
            DB::statement($sql);    
        } catch(Exception $e) {
            throw $e;
        }
    } 

    //Avoir la date fin du devis
    public function getDateEnd() {
        $dateEnd = Carbon::parse($this->start_date);
        $dateEnd->addDays($this->duration);

        return $dateEnd->toDateString();
    }

    //Avoir le montant total d'un devis
    public function getAmountTotal() {
        $amountTotal = $this->sum_amount_work + (($this->percent_increase*$this->sum_amount_work)/100);

        $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

        $amountFormated = $formatter->format($amountTotal);

        return $amountFormated;
    }

    //Avoir le montant total d'un devis sans formattage
    public function getAmountTotalWithoutFormatter() {
        $amountTotal = $this->sum_amount_work + (($this->percent_increase*$this->sum_amount_work)/100);

        return $amountTotal;
    }

    //Avoir le montant total des travaux a faire 
    public function getSumAmountWork() {
        $amountTotal = $this->sum_amount_work;

        $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

        $amountFormated = $formatter->format($amountTotal);

        return $amountFormated;
    }

    //Avoir la representation en millier des nombres 
    public function getNumberformatter($number) {
        $amount = $number;

        $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

        $amountFormated = $formatter->format($amount);

        return $amountFormated;
    }

    //Avoir le montant total d'un devis
    public static function getAmountTotalEstimate() {
        try {
            $req = "SELECT SUM(sum_amount_work + ((sum_amount_work*percent_increase)/100))
            AS amount_estimate_total 
            from estimate";
            $req = sprintf($req);
            $results = DB::select($req);
            $i = 0;
            if($results) {
                foreach($results as $row) {
                    $amountTotal = $row->amount_estimate_total;

                    $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
                    $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

                    $amountFormated = $formatter->format($amountTotal);

                    return $amountFormated;
                }
            }

            return null;
        } catch(Exception $e) {
            throw $e;
        }
    } 

     //Avoir le montant total des payments effectues
     public static function getAmountTotalPayment() {
        try {
            $req = "SELECT SUM(amount) AS amount_payment_total FROM payment";
            $req = sprintf($req);
            $results = DB::select($req);
            $i = 0;
            if($results) {
                foreach($results as $row) {
                    $amountTotal = $row->amount_payment_total;

                    $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
                    $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

                    $amountFormated = $formatter->format($amountTotal);

                    return $amountFormated;
                }
            }

            return null;
        } catch(Exception $e) {
            throw $e;
        }
    } 

    //Avoir le montant total des devis par mois
     public static function getAmountTotalInMonth($year = 2024) {
        try {
            $req = "SELECT month, SUM(amount_estimate)::NUMERIC AS amount_estimate FROM v_estimate_year_month
            WHERE year=%d
            GROUP BY month";
            $req = sprintf($req, $year);
            $results = DB::select($req);
            $i = 0;
            $list = array();
            if($results) {
                foreach($results as $row) {
                   $list [] = $row;
                }
            }

            return $list;
        } catch(Exception $e) {
            throw $e;
        }
    } 

    //Avoir le montant total minimum des devis par mois
    public static function getMaxAmountTotalInMonth() {
        try {
            $req = "SELECT MAX(amount_estimate) as max_amount_total_in_month FROM v_amount_total_in_month";
            $req = sprintf($req);
            $results = DB::select($req);
            if($results) {
                foreach($results as $row) {
                   return $row->max_amount_total_in_month;
                }
            }

            return null;
        } catch(Exception $e) {
            throw $e;
        }
    } 

    //Avoir le montant total des devis par an
    public static function getAmountTotalInYear() {
        try {
            $req = "SELECT year, SUM(amount_estimate)::NUMERIC AS amount_estimate FROM v_estimate_year_month
            GROUP BY year ORDER BY year";
            $req = sprintf($req);
            $results = DB::select($req);
            $i = 0;
            $list = array();
            if($results) {
                foreach($results as $row) {
                   $list [] = $row;
                }
            }

            return $list;
        } catch(Exception $e) {
            throw $e;
        }
    } 
}
