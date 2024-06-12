<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\crud\Unit;
use Exception;

class UniteController extends Controller
{
    //Liste unite
    public function listUnite()
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = ['asset(\'css/supplier/supplier.css\')'];
        $js = ['assets(\'js/bootstrap.bundle.min.js\')'];
        $contentPage = 'pages/crud/unite';
        $title = 'Construction BTP-CRUD unite';
        $listUnite = null;
      
        try {
            $listUnite = Unit::all();

            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
      
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'listUnite' => $listUnite,
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'listUnite' => $listUnite,
                'error' => $e->getMessage(),
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);
        }       
    }

    //Enregistrer unite
    public function save(Request $request)
    {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        try {
            $validatedData = $request->validate([
                'nom' => 'required|string',
            ], [
                'required' => 'Le champ :attribute est obligatoire.',
                'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            ]);

            $idUnite = Unit::isExist($validatedData['nom']);
            if($idUnite == null) {
                $unite = new Unit;
                $unite->unit_name = $validatedData['nom'];
                $unite->save();
            }
        } catch(Exception $e) {  
            return redirect()->route('unite', (['error' => $e->getMessage()]));
        }

        return redirect()->route('unite');
    }

    //Supprimer unite
    public function delete(Request $request)
    {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        try {
            $unite = Unit::find($request->id_unite);
            $unite->delete();
        } catch(Exception $e) {  
            return redirect()->route('unite', (['error' => $e->getMessage()]));
        }

        return redirect()->route('unite');
    }
}
