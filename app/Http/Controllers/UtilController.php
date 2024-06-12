<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\util\Util;

class UtilController extends Controller
{
    //Liste devis client
    public function reinitialize()
    {
        //Reinitialiser la base de donnee
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
      
        try {
            Util::reinitialise_base();

            return redirect()->route('tableau_bord');
        } catch(Exception $e) {
            return view('tableau_bord')->with('error', $e->getMessage());
        }       
    }
}
