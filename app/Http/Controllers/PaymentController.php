<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\paiement\Payment;
use Exception;

class PaymentController extends Controller
{
    //page de paiement
    public function paymentPage(Request $request)
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = [];
        $js = ['js/payment.js'];
        $contentPage = 'pages/paiement/insertion_payment';
        $title = 'Construction BTP - paiement';
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
            return redirect()->route('estimate_list', (['error' => $e->getMessage()]));
        }
    }

    //Enregistrer un paiement
    public function savePayment(Request $request)
    {
       //Verifier l'authentification
       $profilconnected = session()->get('authentified');
       $estimate = session()->get('estimate');

       if(!isset($profilconnected)) {
           return redirect()->route('pageLogin');
       }
      
       try {
            $date_payment = $request->input('date_payment');
            $amount = $request->input('amount');
            $id_estimate = $request->input('id_estimate');

            $payment = new Payment;
            $payment->setDatePayment($date_payment);
            $payment->setAmount($amount, $id_estimate);
            $payment->id_estimate = $id_estimate;
            $payment->client_phone_number = $profilconnected->getIdentifiant();
            $payment->save();
            $payment->ref_payment = 'PA00'.$payment->id_payment;
            $payment->save();

            return response()->json();
       } catch(Exception $e) {  
            throw $e;
           //return redirect()->route('payment_page', (['error' => $e->getMessage()]));
       }
       //return redirect()->route('payment_page', (['success' => 'Paiement reussi']));
    } 
}
