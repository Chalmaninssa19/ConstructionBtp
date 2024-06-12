<?php

namespace App\Models\authentification;


class Authentified
{
    private $profil;
    private $identifiant;
    private $isAuthentified;

///Constructors
    public function __construct($profil, $identifiant, $isAuthentified)
    {
        $this->profil = $profil;
        $this->identifiant = $identifiant;
        $this->isAuthentified = $isAuthentified;
    }

///Encapsulation
    public function getProfil()
    {
        return $this->profil;
    }
    public function setProfil($value)
    {
        $this->profil = $value;
    }
    public function getIdentifiant()
    {
        return $this->identifiant;
    }
    public function setIdentifiant($value)
    {
        $this->identifiant = $value;
    }
    public function getIsAuthentified()
    {
        return $this->isAuthentified;
    }
    public function setIsAuthentified($value)
    {
        $this->isAuthentified = $value;
    }

///Fonctions
   
}
