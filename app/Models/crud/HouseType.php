<?php

namespace App\Models\crud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseType extends Model
{
    use HasFactory;
    protected $table = 'house_type';
    protected $primaryKey = 'id_house_type';

///Fonction
    public function getListDescription() {
        $description = $this->description;
        $parts = explode(", ", $description);

        return $parts;
    }
}
