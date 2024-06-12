<?php

namespace App\Models\crud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Unit extends Model
{
    use HasFactory;
    protected $table = 'unit';
    protected $primaryKey = 'id_unit';

//Verfier si le nom existe deja
    public static function isExist($nom) {
        try {
            $req = "SELECT * FROM unit WHERE unit_name = '%s' LIMIT 1";
            $req = sprintf($req,$nom);
            $results = DB::select($req);
            $i = 0;
            if($results) {
                throw new Exception("Ce nom existe deja, veuillez inserer un autre");
            }

            return null;
        } catch(Exception $e) {
            throw $e;
        }
    } 
}
