<?php

namespace App\Models\util;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class Util 
{
    //Reinitialiser la base
    public static function reinitialise_base() {
        try {
            $sql = "SELECT reinitialisation()";

            $sql = sprintf($sql);
            DB::statement($sql);    
        } catch(Exception $e) {
            throw $e;
        }
    } 

    //Inserer maison et travaux
    public static function insert_house_work() {
        try {
            $sql = "SELECT insert_house_work_from_csv()";

            $sql = sprintf($sql);
            DB::statement($sql);    
        } catch(Exception $e) {
            throw $e;
        }
    } 

    //Inserer devis
    public static function insert_estimate() {
        try {
            $sql = "SELECT insert_estimate_from_csv()";

            $sql = sprintf($sql);
            DB::statement($sql);    
        } catch(Exception $e) {
            throw $e;
        }
    } 

    //Inserer paiement
    public static function insert_detail_estimate() {
        try {
            $sql = "SELECT insert_into_detail_Estimate()";

            $sql = sprintf($sql);
            DB::statement($sql);    
        } catch(Exception $e) {
            throw $e;
        }
    } 

    //Inserer paiement
    public static function insert_payment() {
        try {
            $sql = "SELECT insert_payment_from_csv()";

            $sql = sprintf($sql);
            DB::statement($sql);    
        } catch(Exception $e) {
            throw $e;
        }
    } 
}
