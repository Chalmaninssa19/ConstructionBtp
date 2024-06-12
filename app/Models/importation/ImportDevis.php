<?php

namespace App\Models\importation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;
use App\Models\util\Util;

class ImportDevis extends Model
{
    use HasFactory;

    protected $table = 'devis_temp';
    private $errors = array();

///Getters et setters
    public function setClient($client, $row) {
        if (!isset($client) || empty($client) || !is_string($client)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer une chaine de caractere dans le champ client'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ client');
        }
        $this->client = $client;
    }

    public function setRefDevis($ref_devis, $row) {
        if (!isset($ref_devis) || empty($ref_devis) || !is_string($ref_devis)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer une chaine de caractere dans le champ ref_devis'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ ref_devis');
        }
        $this->ref_devis = $ref_devis;
    }

    public function setTypeMaison($type_maison, $row) {
        if (!isset($type_maison) || empty($type_maison) || !is_string($type_maison)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer une chaine de caractere dans le champ type_maison'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ type_maison');
        }
        $this->type_maison = $type_maison;
    }

    public function setFinition($finition, $row) {
        if (!isset($finition) || empty($finition) || !is_string($finition)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer une chaine de caractere dans le champ finition'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ finition');
        }
        $this->finition = $finition;
    }

    public function setTauxFinition($taux_finition, $row) {
        if(strpos($taux_finition, '%') !== false) {
            $taux_finition = str_replace('%', '', $taux_finition);
        }
        $taux_finition = str_replace(',', '.', $taux_finition);
        if (!isset($taux_finition) || !is_numeric($taux_finition)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer un nombre dans le champ taux_finition'; 
            //throw new Exception('Veuillez entrer un nombre dans le champ taux_finition');
        }
        if($taux_finition < 0) {
            $this->errors[] = 'Erreur ligne '.$row.' : valeur de taux_finition doit etre positive'; 
            //throw new Exception('Valeur de taux_finition doit etre positive');
        }
        $this->taux_finition = $taux_finition;
    }

    public function setDateDevis($date_devis, $row) {
        $dateValider = Carbon::createFromFormat('d/m/Y', $date_devis);

        if($dateValider == false) {
            $this->errors[] = 'Erreur ligne '.$row. ' : le format de date_devis donne ne peur etre formatter'; 
            //throw new Exception('Le format de date_devis donne ne peur etre formatter');
        }
        $date_devis = Carbon::createFromFormat('d/m/Y', $date_devis)->format('Y-m-d');
        if(!isset($date_devis) || !strtotime($date_devis)) {
            $this->errors[] = 'Erreur ligne '.$row.' : le champ date_devis doit etre une date_devis valide au format annee-mois-jour'; 
            //throw new Exception('Le champ date_devis doit etre une date_devis valide au format annee-mois-jour');
        } else {
            $dateParts = explode('-', $date_devis);
            if(count($dateParts) != 3 || !checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
                $this->errors[] = 'Erreur ligne '.$row.' : le champ date_devis doit etre au format annee-mois-jour'; 
                //throw new Exception('Le champ date_devis doit etre au format annee-mois-jour');
            }
        }

        $this->date_devis = $date_devis;
    }

    public function setDateDebut($date_debut, $row) {
        $dateValider = Carbon::createFromFormat('d/m/Y', $date_debut);

        if($dateValider == false) {
            $this->errors[] = 'Erreur ligne '.$row. ' : le format de date_debut donne ne peur etre formatter'; 
            //throw new Exception('Le format de date_debut donne ne peur etre formatter');
        }
        $date_debut = Carbon::createFromFormat('d/m/Y', $date_debut)->format('Y-m-d');
        if(!isset($date_debut) || !strtotime($date_debut)) {
            $this->errors[] = 'Erreur ligne '.$row.' : le champ date_debut doit etre une date_debut valide au format annee-mois-jour'; 
            //throw new Exception('Le champ date_debut doit etre une date_debut valide au format annee-mois-jour');
        } else {
            $dateParts = explode('-', $date_debut);
            if(count($dateParts) != 3 || !checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
                $this->errors[] = 'Erreur ligne '.$row.' : le champ date_debut doit etre au format annee-mois-jour'; 
                //throw new Exception('Le champ date_debut doit etre au format annee-mois-jour');
            }
        }

        $this->date_debut = $date_debut;
    }

    public function setLieu($lieu, $row) {
        if (!isset($lieu) || empty($lieu) || !is_string($lieu)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer une chaine de caractere dans le champ lieu'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ lieu');
        }
        $this->lieu = $lieu;
    }

///Valider importation
    public static function makeErrorMessage($importDevis) {
        $errorMessage = 'Erreurs rencontres lors de la lecture du fichier csv : ';
        foreach($importDevis->errors as $error) {
            $errorMessage = $errorMessage.' -'.$error.';';
        }

        return $errorMessage;
    }

    public static function valideData($datas)
    {
        $row = 1;
        try {
            $importDevis = new ImportDevis();

            foreach($datas as $column){
                $client = $column[0];
                $ref_devis = $column[1];
                $type_maison = $column[2];
                $finition = $column[3];
                $taux_finition = $column[4];
                $date_devis = $column[5];
                $date_debut = $column[6];
                $lieu = $column[7];

                
                $importDevis->setClient($client, $row);
                $importDevis->setRefDevis($ref_devis, $row);
                $importDevis->setTypeMaison($type_maison, $row);
                $importDevis->setFinition($finition, $row);
                $importDevis->setTauxFinition($taux_finition, $row);
                $importDevis->setDateDevis($date_devis, $row);
                $importDevis->setDateDebut($date_debut, $row);
                $importDevis->setLieu($lieu, $row);
    
                $row++;
            }

            if(count($importDevis->errors) > 0) {
                $errorMessage = ImportDevis::makeErrorMessage($importDevis);
                throw new Exception($errorMessage);
            } else {
                $row = 1;
                foreach($datas as $column){
                    $client = $column[0];
                    $ref_devis = $column[1];
                    $type_maison = $column[2];
                    $finition = $column[3];
                    $taux_finition = $column[4];
                    $date_devis = $column[5];
                    $date_debut = $column[6];
                    $lieu = $column[7];

                    $importDevis = new ImportDevis();
                    $importDevis->setClient($client, $row);
                    $importDevis->setRefDevis($ref_devis, $row);
                    $importDevis->setTypeMaison($type_maison, $row);
                    $importDevis->setFinition($finition, $row);
                    $importDevis->setTauxFinition($taux_finition, $row);
                    $importDevis->setDateDevis($date_devis, $row);
                    $importDevis->setDateDebut($date_debut, $row);
                    $importDevis->setLieu($lieu, $row);
                    
                    $importDevis->save();
                    $row++;
                }
                Util::insert_estimate();
                Util::insert_detail_estimate();
            }
        } catch(Exception $e) {
            //Log::debug('erreur = '.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
