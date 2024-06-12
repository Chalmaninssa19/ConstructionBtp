<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\crud\WorkType;
use App\Models\crud\Unit;
use App\Models\crud\VWorkType;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkTypeController extends Controller
{
    //Liste type travaux
    public function listWorkType()
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = ['asset(\'css/supplier/supplier.css\')'];
        $js = ['assets(\'js/bootstrap.bundle.min.js\')'];
        $contentPage = 'pages/crud/work_type_list';
        $title = 'Construction BTP-Liste type travaux en cours';
        $list = null;
        $listTitle = 'LISTE TYPE TRAVAUX EN COURS';
      
        try {
            //$list = WorkType::getWorkTypeList();
            $list = VWorkType::paginate(10);
           
            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
      
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'title' => $title,
                'profilconnected' => $profilconnected,
                'listTitle' => $listTitle
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'error' => $e->getMessage(),
                'title' => $title,
                'profilconnected' => $profilconnected,
                'listTitle' => $listTitle
            ]);
        }       
    }


    //page de creation type travaux
    public function newWorkType()
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = [];
        $js = ['js/add-matiere-premiere-quantite.js'];
        $contentPage = 'pages/crud/new_work_type';
        $title = 'Construction BTP-Ajout type travaux';
        $listUnit = null;
        $success = null;

        try {
            $listUnit = Unit::all();

            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
            if(isset($_GET['success'])) {
                $success = $_GET['success'];
            }

            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'profilconnected' => $profilconnected,
                'title' => $title,
                'listUnit' => $listUnit,
                'success' => $success
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'error' => $e->getMessage(),
                'profilconnected' => $profilconnected,
                'title' => $title,
                'listUnit' => $listUnit,
            ]);
        }
    }

    //page de modification de type de travaux
    public function modifWorkType(Request $request)
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = ['css/annonce/annonce-list.css'];
        $js = ['js/add-matiere-premiere-quantite.js'];
        $contentPage = 'pages/crud/modif_work_type';
        $title = 'Construction BTP-Modification type travaux';
        $listMatierePremiere = null;
        $id_work_type = $request->id_work_type;

        $workType = null;
        $listUnit = null;
        $success = null;

        try {
            $listUnit = Unit::all();
            $workType = WorkType::find($id_work_type);

            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
            if(isset($_GET['success'])) {
                $success = $_GET['success'];
            }

            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'listUnit' => $listUnit,
                'workType' => $workType,
                'profilconnected' => $profilconnected,
                'title' => $title,
                'success' => $success
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'listUnit' => $listUnit,
                'workType' => $workType,
                'error' => $e->getMessage(),
                'profilconnected' => $profilconnected,
                'title' => $title,
            ]);
        }
    }

    //Mettre a jour le type de travaux
    public function updateWorkType(Request $request) {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        try {
            
            $workType = WorkType::find($request->id_work_type);
            $workType->setCode($request->code);
            $workType->setDesignation($request->designation);
            $workType->setUnit($request->unit);
            $workType->setUnitPrice($request->unit_price);
            $workType->setQuantity($request->quantity);

            $workType->save(); 
        } catch(Exception $e) {  
            return redirect()->route('modif_work_type', (['error' => $e->getMessage(), 'id_work_type' => $request->id_work_type]));
        }
 
        return redirect()->route('modif_work_type', (['id_work_type' => $request->id_work_type, 'success' => 'Modification reussi']));
    }

    //Enregistrer type travaux
    public function saveWorkType(Request $request)
    {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
        try {
            $workType = new WorkType;
            $workType->setCode($request->code);
            $workType->setDesignation($request->designation);
            $workType->setUnit($request->unit);
            $workType->setUnitPrice($request->unit_price);
            $workType->setQuantity($request->quantity);

            $workType->save(); 

        } catch(Exception $e) {  
            return redirect()->route('new_work_type', (['error' => $e->getMessage()]));
        }
        return redirect()->route('new_work_type', (['success' => 'Insertion nouveau type travaux reussi']));
    } 

    //Supprimer type travaux
    public function deleteWorkType(Request $request) {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
        $id_work_type = $request->id_work_type;
        try {
            WorkType::find($id_work_type)->delete();
            
        } catch(Exception $e) {  
            return redirect()->route('work_type', (['error' => $e->getMessage()]));
        }
 
        return redirect()->route('work_type');
    }

    //Restaurer le type travaux
    public function restoreWorkType(Request $request) {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
        $id_work_type = $request->id_work_type;
        try {
           $packToRestaured = WorkType::onlyTrashed()->find($id_work_type);
            if($packToRestaured) {
                $packToRestaured->restore();
            } else {
                throw new Exception("Type travaux n'ont trouve");
            }
            WorkType::find($id_work_type)->restore();
            
        } catch(Exception $e) {  
            return redirect()->route('deleted_work_type', (['error' => $e->getMessage()]));
        }
 
        return redirect()->route('deleted_work_type');
    }

    //Forcer la suppression d'un type de travaux
    public function forceDeletedWorkType(Request $request) {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
        $id_work_type = $request->id_work_type;
        try {
           $packToRestaured = WorkType::onlyTrashed()->find($id_work_type);
            if($packToRestaured) {
                $packToRestaured->forceDelete();
            } else {
                throw new Exception("Type travaux n'ont trouve");
            }
            
        } catch(Exception $e) {  
            return redirect()->route('deleted_work_type', (['error' => $e->getMessage()]));
        }
 
        return redirect()->route('deleted_work_type');
    }

    //Liste des types travaux supprimes
    public function workTypeDeleted(Request $request) {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
 
        $css = ['css/annonce/annonce-list.css'];
        $js = ['asset(\'js/bootstrap.bundle.min.js\')'];
        $contentPage = 'pages/crud/work_type_list';
        $title = 'Construction BTP-Liste type travaux supprimes';
        $list = null;
        $listTitle = 'LISTE TYPE TRAVAUX SUPPRIMES';
 
        try {
            $list = VWorkType::onlyTrashed()->paginate(10);
 
            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
 
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'id_helper' => 1,
                'profilconnected' => $profilconnected,
                'title' => $title,
                'listTitle' => $listTitle
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'error' => $e->getMessage(),
                'id_helper' => 1,
                'profilconnected' => $profilconnected,
                'title' => $title,
                'listTitle' => $listTitle
            ]);
        }
    }

    //Rechercher type travaux
    public function searchWorkType(request $request)
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = ['asset(\'css/supplier/supplier.css\')'];
        $js = ['assets(\'js/bootstrap.bundle.min.js\')'];
        $contentPage = 'pages/crud/work_type_list';
        $title = 'Construction BTP-Liste type travaux en cours';
        $list = null;
        $listTitle = 'LISTE TYPE TRAVAUX EN COURS';
        $text = $request->search;
        $id_helper = $request->id_helper;
      
        try {
            $data = array();
            
            if(isset($id_helper)) {
                $data = VWorkType::searchWorkTypeDeleted($text);
            } else {
                $data = VWorkType::searchWorkType($text);
            }

            // Convertir l'array en collection
            $collection = collect($data);

            // Définir le nombre d'éléments par page
            $perPage = 10;

            // Récupérer la page courante depuis les requêtes GET, défaut à 1
            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            // Découper la collection pour la pagination
            $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

            // Créer une instance de LengthAwarePaginator
            $list = new LengthAwarePaginator($currentPageItems, $collection->count(), $perPage);

            // Définir l'URL pour chaque lien de pagination
            $list->setPath($request->url());

           
            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
      
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'title' => $title,
                'profilconnected' => $profilconnected,
                'listTitle' => $listTitle,
                'id_helper' => $id_helper,
                'text' => $text
            ]);
        } catch(Exception $e) {
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'error' => $e->getMessage(),
                'title' => $title,
                'profilconnected' => $profilconnected,
                'listTitle' => $listTitle,
                'id_helper' => $id_helper,
                'text' => $text
            ]);
        }       
    }
}
