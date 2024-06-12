<?php

namespace App\Models\authentification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\DB;


class Users extends Model
{    
    use HasFactory;
    protected $primaryKey = 'id_users';

///Fonction
    ///S'Authentifier
    public static function authenticateAdmin($username, $mdp) {
        try {
            $req = "SELECT * FROM users WHERE mdp = '%s' AND username = '%s'";
            $req = sprintf($req,$mdp,$username);
            $results = DB::select($req);
            $i = 0;
            if($results) {
                foreach ($results as $row) {
                    return $row;
                }
            }
            throw new Exception("Verifier votre username et mot de passe");

        } catch(Exception $e) {
            throw $e;
        }
    }
}