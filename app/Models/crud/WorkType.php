<?php

namespace App\Models\crud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;
use NumberFormatter;

class WorkType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'work_type';
    protected $primaryKey = 'id_work_type';

///Validation
    public function setCode($code) {
        if (!isset($code) || empty($code) || !is_string($code)) {
            throw new Exception('Veuillez entrer une chaine de caractere dans le champ code');
        }
        $this->code = $code;
    }

    public function setdesignation($designation) {
        if (!isset($designation) || empty($designation) || !is_string($designation)) {
            throw new Exception('Veuillez entrer une chaine de caractere dans le champ designation');
        }
        $this->designation = $designation;
    }

    public function setUnit($unit) {
        if (!isset($unit) || !is_numeric($unit)) {
            throw new Exception('Veuillez entrer un entier dans le champ unite');
        }
        if($unit < 0) {
            throw new Exception("Veuillez entrer un entier positif");
        }
        $this->unit_id = $unit;
    }

    public function setUnitPrice($unitPrice) {
        if (!isset($unitPrice) || !is_numeric($unitPrice)) {
            throw new Exception('Veuillez entrer un nombre dans le champ prix unitaire');
        }
        if($unitPrice <= 0) {
            throw new Exception("Veuillez entrer un nombre superieur a 0 dans prix unitaire");
        }
        $this->unit_price = $unitPrice;
    }

    public function setQuantity($quantity) {
        if (!isset($quantity) || !is_numeric($quantity)) {
            throw new Exception('Veuillez entrer un nombre dans le champ quantite');
        }
        if($quantity <= 0) {
            throw new Exception("Veuillez entrer un entier superieur a 0 dans quantite");
        }
        $this->quantity = $quantity;
    }

///Fonctions
    //Avoir les types de travaux
    public static function getWorkTypeList() {
        try {
            $req = "SELECT * FROM v_work_type";
            $req = sprintf($req);
            $results = DB::select($req);
            $i = 0;
            $list = array();
            if($results) {
                foreach($results as $row) {
                    $unitPrice = $row->unit_price;
                    $amount = $row->amount;

                    $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
                    $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

                    $unitPriceFormatted = $formatter->format($unitPrice);
                    $amountFormatted = $formatter->format($amount);
                    $row->unit_price = $unitPriceFormatted;
                    $row->amount = $amountFormatted;

                    $list [] = $row;
                }
            }

            return $list;
        } catch(Exception $e) {
            throw $e;
        }
    } 
}
