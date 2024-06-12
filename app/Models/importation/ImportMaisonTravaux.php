<?php

namespace App\Models\importation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\util\Util;
use Exception;

class ImportMaisonTravaux extends Model
{
    use HasFactory;

    protected $table = 'maison_travaux_temp';
    private $errors = array();

///Getters et setters
    public function setTypeMaison($typeMaison, $row) {
        if (!isset($typeMaison) || empty($typeMaison) || !is_string($typeMaison)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer une chaine de caractere dans le champ typeMaison'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ typeMaison');
        }
        $this->type_maison = $typeMaison;
    }

    public function setDescription($description, $row) {
        if (!isset($description) || empty($description) || !is_string($description)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer une chaine de caractere dans le champ description'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ description');
        }
        $this->description = $description;
    }

    public function setSurface($surface, $row) {
        if (!isset($surface) || !is_numeric($surface)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer un nombre dans le champ surface'; 
            //throw new Exception('Veuillez entrer un nombre dans le champ surface');
        }
        if($surface < 0) {
            $this->errors[] = 'Erreur ligne '.$row.' : valeur de surface doit etre positive'; 
            //throw new Exception('Valeur de surface doit etre positive');
        }
        $this->surface = $surface;
    }

    public function setCodeTravaux($code_travaux, $row) {
        if (!isset($code_travaux) || empty($code_travaux) || !is_string($code_travaux)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer une chaine de caractere dans le champ code_travaux'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ code_travaux');
        }
        $this->code_travaux = $code_travaux;
    }

    public function setTypeTravaux($typeTravaux, $row) {
        if (!isset($typeTravaux) || empty($typeTravaux) || !is_string($typeTravaux)) {
            $this->errors[] = 'Erreur ligne '.$row. ' : veuillez entrer une chaine de caractere dans le champ typeTravaux'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ typeTravaux');
        }
        $this->type_travaux = $typeTravaux;
    }

    public function setUnite($unite, $row) {
        if (!isset($unite) || empty($unite) || !is_string($unite)) {
            $this->errors[] = 'Erreur ligne '.$row. ' : veuillez entrer une chaine de caractere dans le champ unite'; 
            //throw new Exception('Veuillez entrer une chaine de caractere dans le champ unite');
        }
        $this->unite = $unite;
    }

    public function setPrixUnitaire($prixUnitaire, $row) {
        $prixUnitaire = str_replace(',', '.', $prixUnitaire);
        if (!isset($prixUnitaire) || !is_numeric($prixUnitaire)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer un nombre dans le champ prixUnitaire'; 
            //throw new Exception('Veuillez entrer un nombre dans le champ prixUnitaire');
        }
        if($prixUnitaire < 0) {
            $this->errors[] = 'Erreur ligne '.$row.' : valeur de prixUnitaire doit etre positive'; 
            //throw new Exception('Valeur de prixUnitaire doit etre positive');
        }
        $this->prix_unitaire = $prixUnitaire;
    }

    public function setQuantite($quantite, $row) {
        $quantite = str_replace(',', '.', $quantite);
        if (!isset($quantite) || !is_numeric($quantite)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer un nombre dans le champ quantite'; 
            //throw new Exception('Veuillez entrer un nombre dans le champ quantite');
        }
        if($quantite < 0) {
            $this->errors[] = 'Erreur ligne '.$row.' : valeur de quantite doit etre positive'; 
            //throw new Exception('Valeur de quantite doit etre positive');
        }
        $this->quantite = $quantite;
    }

    public function setDureeTravaux($dureeTravaux, $row) {
        $dureeTravaux = str_replace(',', '.', $dureeTravaux);
        if (!isset($dureeTravaux) || !is_numeric($dureeTravaux)) {
            $this->errors[] = 'Erreur ligne '.$row.' : veuillez entrer un nombre dans le champ dureeTravaux'; 
            //throw new Exception('Veuillez entrer un nombre dans le champ dureeTravaux');
        }
        if($dureeTravaux < 0) {
            $this->errors[] = 'Erreur ligne '.$row.' : valeur de dureeTravaux doit etre positive'; 
            //throw new Exception('Valeur de dureeTravaux doit etre positive');
        }
        $this->duree_travaux = $dureeTravaux;
    }

///Valider importation
    public static function makeErrorMessage($importMaisonTravaux) {
        $errorMessage = 'Erreurs rencontres lors de la lecture du fichier csv : ';
        foreach($importMaisonTravaux->errors as $error) {
            $errorMessage = $errorMessage.' -'.$error.';';
        }

        return $errorMessage;
    }

    public static function valideData($datas)
    {
        $row = 1;
        try {
            $importMaisonTravaux = new ImportMaisonTravaux();

            foreach($datas as $column){
                $type_maison = $column[0];
                $description = $column[1];
                $surface = $column[2];
                $code_travaux = $column[3];
                $type_travaux = $column[4];
                $unite = $column[5];
                $prix_unitaire = $column[6];
                $quantite = $column[7];
                $duree_travaux = $column[8];

                
                $importMaisonTravaux->setTypeMaison($type_maison, $row);
                $importMaisonTravaux->setDescription($description, $row);
                $importMaisonTravaux->setSurface($surface, $row);
                $importMaisonTravaux->setCodeTravaux($code_travaux, $row);
                $importMaisonTravaux->setTypeTravaux($type_travaux, $row);
                $importMaisonTravaux->setUnite($unite, $row);
                $importMaisonTravaux->setPrixUnitaire($prix_unitaire, $row);
                $importMaisonTravaux->setQuantite($quantite, $row);
                $importMaisonTravaux->setDureeTravaux($duree_travaux, $row);
    
                $row++;
            }

            if(count($importMaisonTravaux->errors) > 0) {
                $errorMessage = ImportMaisonTravaux::makeErrorMessage($importMaisonTravaux);
                throw new Exception($errorMessage);
            } else {
                $row = 1;
                foreach($datas as $column){
                    $type_maison = $column[0];
                    $description = $column[1];
                    $surface = $column[2];
                    $code_travaux = $column[3];
                    $type_travaux = $column[4];
                    $unite = $column[5];
                    $prix_unitaire = $column[6];
                    $quantite = $column[7];
                    $duree_travaux = $column[8];

                    $importMaisonTravaux = new ImportMaisonTravaux();
                    $importMaisonTravaux->setTypeMaison($type_maison, $row);
                    $importMaisonTravaux->setDescription($description, $row);
                    $importMaisonTravaux->setSurface($surface, $row);
                    $importMaisonTravaux->setCodeTravaux($code_travaux, $row);
                    $importMaisonTravaux->setTypeTravaux($type_travaux, $row);
                    $importMaisonTravaux->setUnite($unite, $row);
                    $importMaisonTravaux->setPrixUnitaire($prix_unitaire, $row);
                    $importMaisonTravaux->setQuantite($quantite, $row);
                    $importMaisonTravaux->setDureeTravaux($duree_travaux, $row);
        
                    $importMaisonTravaux->save();
                    $row++;
                }
                Util::insert_house_work();
            }
        } catch(Exception $e) {
            //Log::debug('erreur = '.$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
