<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\crud\HouseType;
use App\Models\crud\VHouseType;
use App\Models\gestion_devis\Estimate;
use App\Models\gestion_devis\VDetailsEstimate;
use App\Models\gestion_devis\VEstimateProgress;
use App\Models\gestion_devis\WorkProgress;
use App\Models\crud\FinishType;
use App\Models\paiement\Payment;
use Illuminate\Support\Facades\Log;
use Exception;
use Dompdf\Dompdf;

class EstimateController extends Controller
{
    //Liste devis client
    public function list()
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = [];
        $js = [];
        $contentPage = 'pages/gestion_devis/liste_devis_client';
        $title = 'Construction BTP - liste devis';
        $list = null;
      
        try {
            if($profilconnected->getProfil() == 2) {
                $list = Estimate::where('client_phone_number', $profilconnected->getIdentifiant())->get();

                if(isset($_GET['error'])) {
                    throw new Exception($_GET['error']);
                }
          
                return view('pages/template')->with([
                    'css' => $css,
                    'js' => $js,
                    'contentPage' => $contentPage,
                    'list' => $list,
                    'title' => $title,
                    'profilconnected' => $profilconnected
                ]);
            } else {
                $contentPage = 'pages/auth/page404';
                $title = 'Construction BTP - Erreur 404';
                return view('pages/template')->with([
                    'css' => $css,
                    'js' => $js,
                    'contentPage' => $contentPage,
                    'title' => $title,
                    'profilconnected' => $profilconnected
                ]);
            }
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'error' => $e->getMessage(),
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);
        }       
    }


    //page de creation d'un devis
    public function newEstimate()
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = ['css/devis/devis.css'];
        $js = [];
        $contentPage = 'pages/gestion_devis/create_estimate_client';
        $title = 'Construction BTP - nouveau devis';
        $list = null;
        try {
            if($profilconnected->getProfil() == 2) {
                $list = VHouseType::all();

                if(isset($_GET['error'])) {
                    throw new Exception($_GET['error']);
                }
    
                return view('pages/template')->with([
                    'css' => $css,
                    'js' => $js,
                    'contentPage' => $contentPage,
                    'title' => $title,
                    'list' => $list,
                    'profilconnected' => $profilconnected
                ]);
            } else {
                $contentPage = 'pages/auth/page404';
                $title = 'Construction BTP - Erreur 404';
                return view('pages/template')->with([
                    'css' => $css,
                    'js' => $js,
                    'contentPage' => $contentPage,
                    'title' => $title,
                    'profilconnected' => $profilconnected
                ]);
            }

           
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'error' => $e->getMessage(),
                'title' => $title,
                'list' => $list,
                'profilconnected' => $profilconnected
            ]);
        }
    }

    //page de creation d'un devis page 2
    public function newEstimate2()
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = ['css/devis/devis.css'];
        $js = [];
        $contentPage = 'pages/gestion_devis/create_estimate_client2';
        $title = 'Construction BTP - nouveau devis';
        $listFinishType = null;
        $success = null;
        try {
            $listFinishType = FinishType::all();

            if(isset($_GET['success'])) {
                $success = $_GET['success'];
            }
            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }

            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'title' => $title,
                'listFinishType' => $listFinishType,
                'message' => $success,
                'profilconnected' => $profilconnected
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'error' => $e->getMessage(),
                'title' => $title,
                'listFinishType' => $listFinishType,
                'profilconnected' => $profilconnected
            ]);
        }
    }

    //Enregistrer la maison choisie
    public function saveHouseType(Request $request)
    {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        try {
            $houseType = VHouseType::where('id_house_type',$request->input('house_type'))->first();
            $estimate = new Estimate;
        
            $estimate->house_type_designation = $houseType->designation;
            $estimate->duration = $houseType->duration;
            $estimate->house_description = $houseType->description;
            $estimate->sum_amount_work = $houseType->total_amount;
            $estimate->id_house_type = $houseType->id_house_type;
            $estimate->surface = $houseType->surface;

            session()->put('estimate', $estimate);
        } catch(Exception $e) {  
            return redirect()->route('new_estimate', (['error' => $e->getMessage()]));
        }

        return redirect()->route('new_estimate2');
    }

     //Enregistrer un devis
     public function saveEstimate(Request $request)
     {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        $estimate = session()->get('estimate');
 
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
        if(!isset($estimate)) {
            throw new Exception('Impossible d\'effectuer cette operation : vous devrez choisir 
            en premier le type de maison');
        }
        try {
            $finish_type = $request->input('id_finish_type');
            $dateStart = $request->input('date_start');
            $dateEstimate = $request->input('date_estimate');
            $lieu = $request->input('lieu');
            $estimate->valideFinishType($finish_type);
            $estimate->valideDateStart($dateEstimate);
            $estimate->valideDateStart($dateStart);
            $estimate->valideLieu($lieu);

            $finish_type = FinishType::find($finish_type);
            $estimate->finish_type_designation = $finish_type->finish_type_name;
            $estimate->percent_increase = $finish_type->increase_percent;
            $estimate->start_date = $dateStart;
            $estimate->date_estimate = $dateEstimate;
            $estimate->client_phone_number = $profilconnected->getIdentifiant();
            $estimate->state = 1;
            $estimate->lieu = $lieu;
            $estimate->save();
            $estimate->ref_estimate = 'D00'.$estimate->id_estimate;
            $estimate->save();
            $estimate->insertDetailEstimate();

        } catch(Exception $e) {  
            return redirect()->route('new_estimate2', (['error' => $e->getMessage()]));
        }
        return redirect()->route('estimate_list', (['success' => 'Creation devise reussi']));
     } 

    //Exporter le devis en pdf
    public function exportEstimatePdf(Request $request) {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = [];
        $js = ['assets(\'js/bootstrap.bundle.min.js\')'];
        $contentPage = 'pages/gestion_billet/detail_livraison';
        $id_estimate =$request->id_estimate;
        $listdetailEstimate = array();
        $estimate = null;

        try {

            $estimate = Estimate::where('id_estimate', $id_estimate)->first();
            $listdetailEstimate = VDetailsEstimate::where('estimate_id',$id_estimate)->get();
            $listPayment = Payment::where('id_estimate', $id_estimate)->get();
            $payment = VEstimateProgress::where('id_estimate', $id_estimate)->first();

            $data = [
                'listdetailEstimate' => $listdetailEstimate,
                'estimate' => $estimate,
                'listPayment' => $listPayment,
                'payment' => $payment
            ];

            $pdf = new DomPdf();
            $pdf->loadHtml(view('pages/gestion_devis/fiche_devis_client', $data));
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();

            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
          
            return $pdf->stream('devis_'.$estimate->house_type_designation.'.pdf');
        } catch(Exception $e) {
            return redirect()->route('estimate_list', (['error' => $e->getMessage()]));
        } 
    }

    //Devis en cours
    public function estimateInProgress() {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
 
        $css = [];
        $js = [];
        $contentPage = 'pages/gestion_devis/liste_devis_btp';
        $title = 'Construction BTP - liste devis en cours';
        $list = null;
       
        try {
            $list = VEstimateProgress::all();
 
            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
       
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'error' => $e->getMessage(),
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);
        }   
    }

    //Details devis
    public function detailsEstimate(Request $request) {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = [];
        $js = ['assets(\'js/bootstrap.bundle.min.js\')'];
        $contentPage = 'pages/gestion_devis/fiche_devis_btp';
        $id_estimate =$request->id_estimate;
        $listdetailEstimate = array();
        $estimate = null;
        $title = 'Construction BTP - details devis';

        try {

            $estimate = Estimate::where('id_estimate', $id_estimate)->first();
            $estimateProgress = VEstimateProgress::where('id_estimate', $id_estimate)->first();
            $listdetailEstimate = VDetailsEstimate::where('estimate_id',$id_estimate)->get();

          
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'estimate' => $estimate,
                'estimateProgress' => $estimateProgress,
                'listdetailEstimate' => $listdetailEstimate,
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);        
        } catch(Exception $e) {
            return redirect()->route('estimate_in_progress', (['error' => $e->getMessage()]));
        } 
    }

    //page d'insertion avancement
    public function insertWorkProgressPage(Request $request)
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = [];
        $js = ['js/payment.js'];
        $contentPage = 'pages/gestion_devis/insert_work_progress';
        $title = 'Construction BTP - avancement travaux';
        $success = null;
        $id_estimate = $request->id_estimate;

        try {

            if(isset($_GET['success'])) {
                $success = $_GET['success'];
            }

            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }

            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'title' => $title,
                'message' => $success,
                'id_estimate' => $id_estimate,
                'profilconnected' => $profilconnected
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'title' => $title,
                'error' => $e->getMessage(),
                'id_estimate' => $id_estimate,
                'profilconnected' => $profilconnected
            ]);        
        }
    }

    //Enregistrer l'avancement type travaux
    public function saveWorkProgress(Request $request)
    {
       //Verifier l'authentification
       $profilconnected = session()->get('authentified');

       if(!isset($profilconnected)) {
           return redirect()->route('pageLogin');
       }
      
       try {
            $date_start = $request->input('date_start');
            $date_end = $request->input('date_end');
            $id_estimate = $request->input('id_estimate');

            $workProgress = new WorkProgress;

            $workProgress->setDateStart($date_start);
            $workProgress->setDateEnd($date_end);
            $weekendCount = $workProgress->countWeekendsBetweenDates($date_start, $date_end);
            $workProgress->id_estimate = $id_estimate;
            $workProgress->n_week_end = $weekendCount;
            $workProgress->save();

            return redirect()->route('estimate_in_progress', (['success' => 'Insertion avancement travaux reussi', 'id_estimate' => $request->input('id_estimate')]));
       } catch(Exception $e) {  
           return redirect()->route('work_progress', (['error' => $e->getMessage(), 'id_estimate' => $request->input('id_estimate')]));
       }
    } 
}
