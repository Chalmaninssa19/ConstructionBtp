<?php

namespace App\Models\crud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Exception;
use NumberFormatter;

class VWorkType extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'v_work_type';

///Fonctions
    //Rechercher un type travaux par code ou designation
    public static function searchWorkType($text) {
        try {
            $req = "SELECT * FROM v_work_type WHERE code LIKE ? OR designation LIKE ?";
            $results = DB::select($req, ['%' . $text . '%', '%' . $text . '%']);
            $list = [];

            if ($results) {
                foreach ($results as $row) {
                    $unitPrice = $row->unit_price;
                    $amount = $row->amount;

                    $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
                    $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

                    $unitPriceFormatted = $formatter->format($unitPrice);
                    $amountFormatted = $formatter->format($amount);
                    $row->unit_price = $unitPriceFormatted;
                    $row->amount = $amountFormatted;

                    $list[] = $row;
                }
            }

            return $list;
        } catch (Exception $e) {
            throw $e;
        }
    }

    //Rechercher un type travaux supprimes par code ou designation
    public static function searchWorkTypeDeleted($text) {
        try {
            $req = "SELECT * FROM v_work_type WHERE deleted_at != NULL AND (code LIKE ? OR designation LIKE ?)";
            $results = DB::select($req, ['%' . $text . '%', '%' . $text . '%']);
            $list = [];

            if ($results) {
                foreach ($results as $row) {
                    $unitPrice = $row->unit_price;
                    $amount = $row->amount;

                    $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
                    $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

                    $unitPriceFormatted = $formatter->format($unitPrice);
                    $amountFormatted = $formatter->format($amount);
                    $row->unit_price = $unitPriceFormatted;
                    $row->amount = $amountFormatted;

                    $list[] = $row;
                }
            }

            return $list;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
