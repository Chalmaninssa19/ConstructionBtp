<?php

namespace App\Models\gestion_devis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class WorkProgress extends Model
{
    use HasFactory;
    protected $table = 'work_progress';
    protected $primaryKey = 'id_work_progress';

///Validation
    //validation date debut
    public function setDateStart($date) {
        if(!isset($date) || !strtotime($date)) {
            throw new Exception('Le champ date doit etre une date valide au format annee-mois-jour');
        } else {
            $dateParts = explode('-', $date);
            if(count($dateParts) != 3 || !checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
                throw new Exception('Le champ date doit etre au format annee-mois-jour');
            }
        }
        $this->date_start = $date;
    }

    //validation date fin
    public function setDateEnd($date) {
        if(!isset($date) || !strtotime($date)) {
            throw new Exception('Le champ date doit etre une date valide au format annee-mois-jour');
        } else {
            $dateParts = explode('-', $date);
            if(count($dateParts) != 3 || !checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
                throw new Exception('Le champ date doit etre au format annee-mois-jour');
            }
        }
        $this->date_end = $date;
    }

///Fonctions

    public function countWeekendsBetweenDates($dateStart, $dateEnd)
    {
        // Convertir les dates en objets Carbon
        $start = Carbon::parse($dateStart);
        $end = Carbon::parse($dateEnd);

        // Générer une période entre les deux dates
        $period = CarbonPeriod::create($start, $end);

        // Compter les week-ends (samedi et dimanche)
        $weekendDays = 0;

        foreach ($period as $date) {
            if ($date->isSaturday() || $date->isSunday()) {
                $weekendDays++;
            }
        }

        return $weekendDays;
    }
}
