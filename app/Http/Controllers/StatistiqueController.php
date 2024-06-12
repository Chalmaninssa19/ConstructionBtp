<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\gestion_devis\Estimate;
use App\Models\util\AmountMonth;
use App\Models\util\AmountYear;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class StatistiqueController extends Controller
{
    //Tableau de bord
    public function tableauBord() {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
 
        $css = [];
        $js = ['vendors/chart.js/Chart.min.js', 'js/statistiques/graphe.js'];
        $contentPage = 'pages/statistiques/tableauBord';
        $title = 'Construction BTP-Tableau de bord';
        $amountTotalEstimate = null;
        $amountTotalPayment = null;
        $years = [2030, 2029, 2028, 2027, 2026, 2025, 2024, 2023, 2022, 2021, 2020, 2019, 2018, 
        2017, 2016, 2015, 2014, 2013, 2012, 2011, 2010, 2008, 2007, 2006, 2005, 2004, 2003, 2002, 2001,
        2000];
        $year = session()->get('year');

        try {
            $amountTotalEstimate = Estimate::getAmountTotalEstimate();
            $amountTotalPayment = Estimate::getAmountTotalPayment();

            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
       
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'title' => $title,
                'amountTotalEstimate' => $amountTotalEstimate,
                'amountTotalPayment' => $amountTotalPayment,
                'years' => $years,
                'year' => $year,
                'profilconnected' => $profilconnected
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'error' => $e->getMessage(),
                'title' => $title,
                'amountTotalEstimate' => $amountTotalEstimate,
                'amountTotalPayment' => $amountTotalPayment,
                'years' => $years,
                'year' => $year,
                'profilconnected' => $profilconnected
            ]);
        }  
    }

    //Graphe de camembert
    public function histogramme() {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
 
        $estimateInMonth = null;
        $year = session()->get('year');
        $yearFilter2 = session()->get('yearFilter2');
        $help = session()->get('help');

        try {
            $data = array();
            $label = array();
            $colors = array();
            $borderColors = array();
            $title = '';
            $h4 = '';
            if(isset($year)) {
                $estimateInMonth = Estimate::getAmountTotalInMonth($year);
                $estimateInMonth = AmountMonth::getAmountMonth($estimateInMonth);
             
                foreach($estimateInMonth as $item) {
                    $data [] = $item->amount_estimate;
                    $label [] = $item->month;
                    $colors [] = $item->color;
                    $borderColors [] = $item->border_color;
                    $title = 'Montant par mois';
                }
                $h4 = 'HISTOGRAMME DE L\'ANNEE '.$year;
                $data [] = Estimate::getMaxAmountTotalInMonth();
                Session::forget('year');
            } else if(isset($help)){
                $estimateInYear = Estimate::getAmountTotalInYear();
                $estimateInYear = AmountYear::getAmountTotalInYear($estimateInYear);
                foreach($estimateInYear as $item) {
                    $data [] = $item->amount_estimate;
                    $label [] = $item->year;
                    $colors [] = $item->color;
                    $borderColors [] = $item->border_color;
                }
                $title = 'Montant par an';
                $h4 = 'HISTOGRAMME PAR AN';
                Session::forget('help');
            } else {
                $estimateInMonth = Estimate::getAmountTotalInMonth();
                $estimateInMonth = AmountMonth::getAmountMonth($estimateInMonth);

                foreach($estimateInMonth as $item) {
                    $data [] = $item->amount_estimate;
                    $label [] = $item->month;
                    $colors [] = $item->color;
                    $borderColors [] = $item->border_color;

                    $title = 'Montant par mois';
                    $h4 = 'HISTOGRAMME DE L\'ANNEE 2024';
                }
                $data [] = Estimate::getMaxAmountTotalInMonth();
            }

            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }            

            return response()->json(['data' => $data, 
                    'label' => $label,
                    'title' => $title,
                    'h4' => $h4,
                    'colors' => $colors,
                    'borderColors' => $borderColors
                ]);
         } catch(Exception $e) {
            throw $e;
         }  
    }

    //Filtrer par mois d'une annee
    public function filterByMonthInYear(Request $request) {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
 
        $year = $request->input('year');
        session()->put('year', $year);
        
        return redirect()->route('tableau_bord');
    }

    //Filtrer par annee
     public function filterByYear(Request $request) {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
 
        $help = $request->input('help');
        session()->put('help', $help);
        
        return redirect()->route('tableau_bord');
    }
}
