<?php

namespace App\Models\importation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;
use App\Models\util\Util;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportPaiement extends Model
{
    use HasFactory;

    protected $table = 'paiement_temp';
    private $errors = array();

///Getters et setters
    public function setRefDevis($ref_devis, $row) {
        if (!isset($ref_devis) || empty($ref_devis) || !is_string($ref_devis)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer une chaine de caractere dans le champ ref_devis'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ ref_devis');
        }
        $this->ref_devis = $ref_devis;
    }

    public function setRefPaiement($ref_paiement, $row) {
        if (!isset($ref_paiement) || empty($ref_paiement) || !is_string($ref_paiement)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer une chaine de caractere dans le champ ref_paiement'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ ref_paiement');
        }
        $this->ref_paiement = $ref_paiement;
    }

    public function setDatePaiement($date_paiement, $row) {
        $dateValider = Carbon::createFromFormat('d/m/Y', $date_paiement);

        if($dateValider == false) {
            $this->errors[] = 'Erreur ligne '.$row. ' : le format de date_paiement donne ne peur etre formatter'; 
            //throw new Exception('Le format de date_paiement donne ne peur etre formatter');
        }
        $date_paiement = Carbon::createFromFormat('d/m/Y', $date_paiement)->format('Y-m-d');
        if(!isset($date_paiement) || !strtotime($date_paiement)) {
            $this->errors[] = 'Erreur ligne '.$row.' : le champ date_paiement doit etre une date_paiement valide au format annee-mois-jour'; 
            //throw new Exception('Le champ date_paiement doit etre une date_paiement valide au format annee-mois-jour');
        } else {
            $dateParts = explode('-', $date_paiement);
            if(count($dateParts) != 3 || !checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
                $this->errors[] = 'Erreur ligne '.$row.' : le champ date_paiement doit etre au format annee-mois-jour'; 
                //throw new Exception('Le champ date_paiement doit etre au format annee-mois-jour');
            }
        }

        $this->date_paiement = $date_paiement;
    }


    public function setMontant($montant, $row) {
        $montant = str_replace(',', '.', $montant);
        if (!isset($montant) || !is_numeric($montant)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer un nombre dans le champ montant'; 
            //throw new Exception('Veuillez entrer un nombre dans le champ montant');
        }
        if($montant < 0) {
            $this->errors[] = 'Erreur ligne '.$row.' : valeur de montant doit etre positive'; 
            //throw new Exception('Valeur de montant doit etre positive');
        }
        $this->montant = $montant;
    }

///Valider importation
    public static function makeErrorMessage($importPaiement) {
        $errorMessage = 'Erreurs rencontres lors de la lecture du fichier csv : ';
        foreach($importPaiement->errors as $error) {
            $errorMessage = $errorMessage.' -'.$error.';';
        }

        return $errorMessage;
    }

    public static function valideData($datas)
    {
        $row = 1;
        try {
            $importPaiement = new ImportPaiement();

            foreach($datas as $column){
                $ref_devis = $column[0];
                $ref_paiement = $column[1];
                $date_paiement = $column[2];
                $montant = $column[3];
                
                $importPaiement->setRefDevis($ref_devis, $row);
                $importPaiement->setRefPaiement($ref_paiement, $row);
                $importPaiement->setDatePaiement($date_paiement, $row);
                $importPaiement->setMontant($montant, $row);
    
                $row++;
            }

            if(count($importPaiement->errors) > 0) {
                $errorMessage = ImportDevis::makeErrorMessage($importPaiement);
                throw new Exception($errorMessage);
            } else {
                $row = 1;
                $errors = array();
                foreach($datas as $column){
                    $ref_devis = $column[0];
                    $ref_paiement = $column[1];
                    $date_paiement = $column[2];
                    $montant = $column[3];

                    $importPaiement = new ImportPaiement();
                    $importPaiement->setRefDevis($ref_devis, $row);
                    $importPaiement->setRefPaiement($ref_paiement, $row);
                    $importPaiement->setDatePaiement($date_paiement, $row);
                    $importPaiement->setMontant($montant, $row);
            
                    $importPaiement->save();

                    $row++;
                }
                Util::insert_payment();
            }
        } catch(Exception $e) {
            //Log::debug('erreur = '.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
