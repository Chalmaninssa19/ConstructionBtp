<?php

namespace App\Models\util;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AmountYear
{
    public $amount_estimate;
    public $year;
    public $color;
    public $border_color;

///Constructors
    public function __construct($amount_estimate, $year, $color, $border_color)
    {
        $this->amount_estimate = $amount_estimate;
        $this->year = $year;
        $this->color = $color;
        $this->border_color = $border_color;
    }

///Fonctions
    public static function getAmountTotalInYear($amountYearArray) {
        $amountYearList = array();

        foreach($amountYearArray as $item) {
            if($item->amount_estimate < 100000000) {
                $amountYearList[] = new AmountYear($item->amount_estimate, $item->year, 'rgba(255, 99, 132, 0.2)', 'rgba(255,99,132,1)');
            } else {
                $amountYearList[] = new AmountYear($item->amount_estimate, $item->year, 'rgba(54, 162, 235, 0.2)', 'rgba(54, 162, 235, 1)');
            }
        }

        return $amountYearList;
    }
}

?>