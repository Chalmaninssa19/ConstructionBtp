<?php

namespace App\Models\util;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AmountMonth 
{
    public $value;
    public $month;
    public $amount_estimate;
    public $color;
    public $border_color;

///Constructors
public function __construct($value, $month, $amount_estimate)
{
    $this->value = $value;
    $this->month = $month;
    $this->amount_estimate = $amount_estimate;
}

///Fonctions
    public static function listAmountMonth() {
        $amountMonthArray = [
            new AmountMonth(1, 'Jan', 0),
            new AmountMonth(2, 'Fev', 0),
            new AmountMonth(3, 'Mars', 0),
            new AmountMonth(4, 'Avril', 0),
            new AmountMonth(5, 'Mai', 0),
            new AmountMonth(6, 'Juin', 0),
            new AmountMonth(7, 'Juil', 0),
            new AmountMonth(8, 'Aout', 0),
            new AmountMonth(9, 'Sept', 0),
            new AmountMonth(10, 'Oct', 0),
            new AmountMonth(11, 'Nov', 0),
            new AmountMonth(12, 'Dec', 0)
        ];

        return $amountMonthArray;
    }
    public static function getAmountMonth($amountMonthArray) {
        $amountMonthList = AmountMonth::listAmountMonth();

        foreach($amountMonthList as $item) {
            foreach($amountMonthArray as $amountMonth) {
                if($item->value == $amountMonth->month) {
                    $item->amount_estimate = $amountMonth->amount_estimate;
                    if($item->amount_estimate < 50000000) {
                        $item->color = 'rgba(255, 99, 132, 0.2)';
                        $item->border_color = 'rgba(255,99,132,1)';
                    } else {
                        $item->color = 'rgba(54, 162, 235, 0.2)';
                        $item->border_color = 'rgba(54, 162, 235, 1)';
                    }
                }
            }
        }

        return $amountMonthList;
    }
} 

?>