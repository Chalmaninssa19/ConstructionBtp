<?php

namespace App\Models\crud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishType extends Model
{
    use HasFactory;
    protected $table = 'finish_type';
    protected $primaryKey = 'id_finish_type';

///Validation
    public function setFinishTypeName($designation) {
        if (!isset($designation) || empty($designation) || !is_string($designation)) {
            throw new Exception('Veuillez entrer une chaine de caractere dans le champ type finistion');
        }
        $this->finish_type_name = $designation;
    }

    public function setIncreasePercent($percent) {
        if (!isset($percent) || !is_numeric($percent)) {
            throw new Exception('Veuillez entrer un nombre dans le champ taux d\'augmentation');
        }
        if($percent < 0) {
            throw new Exception("Veuillez entrer un nombre positif dans le champ taux d\'augmentation");
        }
        $this->increase_percent = $percent;
    }
}
