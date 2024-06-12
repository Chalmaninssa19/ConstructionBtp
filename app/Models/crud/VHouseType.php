<?php

namespace App\Models\crud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NumberFormatter;

class VHouseType extends Model
{
    use HasFactory;
    protected $table = 'v_house_type';

///Fonction
    public function getListDescription() {
        $description = $this->description;
        $parts = explode(", ", $description);
    
        return $parts;
    }

    public function getAmountNumber() {
        $formatter = new NumberFormatter('fr_FR', NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::GROUPING_USED, true);

        $amountFormated = $formatter->format($this->total_amount);

        return $amountFormated;
    }
}
