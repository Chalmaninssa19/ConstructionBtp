<?php

namespace App\Models\authentification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Client extends Model
{
   private $numero_telephone;

///Encapsulation
    public function getNumeroTelephone() {
        return $this->numero_telephone;
    }
    public function setNumeroTelephone($value) {
        // Vérifier si la valeur est numérique
        if (!is_numeric($value)) {
            throw new Exception('Le numero de telephone doit etre un nombre');
        }

        // Vérifier si la valeur a exactement 10 chiffres
        if (strlen($value) !== 10) {
            throw new Exception('Le numero de telephone ne doit comporter que 10 chiffres');
        }

        // Vérifier si le prefixe est l'un d'entre eux
        $prefixes = ['033', '032', '034', '038'];

        $startPrefixe = false;
        foreach ($prefixes as $prefix) {
            if (substr($value, 0, strlen($prefix)) === $prefix) {
                $startPrefixe = true;
                break;
            }
        }
        if(!$startPrefixe) {
            throw new Exception('Le numero de telephone doit commencer par 032, 033, 034 ou 038');
        }

        $this->numero_telephone = $value;
    }

}
