<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\importation\ImportDevis;
use App\Models\importation\ImportMaisonTravaux;
use App\Models\importation\ImportPaiement;
use Exception;

class ImportController extends Controller
{
    //Page d'importation
    public function importPage()
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = ['asset(\'css/supplier/supplier.css\')'];
        $js = ['assets(\'js/bootstrap.bundle.min.js\')'];
        $contentPage = 'pages/importation/import_page';
        $title = 'Construction BTP-Importation';
      
        try {

            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
      
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'error' => $e->getMessage(),
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);
        }       
    }


    //Importation csv de maison, travaux et devis
    public function importHouseWorkEstimate(Request $request)
    {
            //Verifier l'authentification
            $profilconnected = session()->get('authentified');
            if(!isset($profilconnected)) {
                return redirect()->route('pageLogin');
            }
        try {
            $request->validate([
                'upload_house_work' => 'required',
                'upload_estimate' => 'required',
            ], [
                'required' => 'Le champ est vide',
                'mimes:csv' => 'Doit etre au format csv'
            ]);
            ///Lire le fichier csv maison et travaux
            $file = $request->file('upload_house_work');
            $handle = fopen($file->path(), 'r');

            fgetcsv($handle);
    
            $chunksize = 25;
            while(!feof($handle))
            {
                $chunkdata = [];
    
                for($i = 0; $i<$chunksize; $i++)
                {
                    $data = fgetcsv($handle);
                    if($data === false)
                    {
                        break;
                    }
                    $chunkdata[] = $data; 
                }
    
                ImportMaisonTravaux::valideData($chunkdata);
            }
            fclose($handle);

            $file2 = $request->file('upload_estimate');
            $handle = fopen($file2->path(), 'r');
    
            fgetcsv($handle);

            $chunksize = 25;
            while(!feof($handle))
            {
                $chunkdata = [];
    
                for($i = 0; $i<$chunksize; $i++)
                {
                    $data = fgetcsv($handle);
                    if($data === false)
                    {
                        break;
                    }
                    $chunkdata[] = $data; 
                }
    
                ImportDevis::valideData($chunkdata);
            }
    
            fclose($handle);

            return redirect()->route('import_page', (['success' => 'Les donnees ont ete bien enregistres']));
        } catch(Exception $e) {
            return redirect()->route('import_page', (['error' => $e->getMessage()]));
        }
    }

    //Importation csv de paiement
    public function importPayment(Request $request)
    {
            //Verifier l'authentification
            $profilconnected = session()->get('authentified');
            if(!isset($profilconnected)) {
                return redirect()->route('pageLogin');
            }
        try {
            $request->validate([
                'upload_payment' => 'required',
            ], [
                'required' => 'Le champ est vide',
                'mimes:csv' => 'Doit etre au format csv'
            ]);
            ///Lire le fichier csv maison et travaux
            $file = $request->file('upload_payment');
            $handle = fopen($file->path(), 'r');

            fgetcsv($handle);
    
            $chunksize = 25;
            while(!feof($handle))
            {
                $chunkdata = [];
    
                for($i = 0; $i<$chunksize; $i++)
                {
                    $data = fgetcsv($handle);
                    if($data === false)
                    {
                        break;
                    }
                    $chunkdata[] = $data; 
                }
    
                ImportPaiement::valideData($chunkdata);
            }
            fclose($handle);

            return redirect()->route('import_page', (['success' => 'Les donnees ont ete bien enregistres']));
        } catch(Exception $e) {
            return redirect()->route('import_page', (['error' => $e->getMessage()]));
        }
    }
}
