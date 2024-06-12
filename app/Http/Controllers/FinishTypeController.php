<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\crud\FinishType;

class FinishTypeController extends Controller
{
    //Liste type finition
    public function listFinishType()
    {
        //Verfier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        $css = ['asset(\'css/supplier/supplier.css\')'];
        $js = ['assets(\'js/bootstrap.bundle.min.js\')'];
        $contentPage = 'pages/crud/finish_type';
        $title = 'Construction BTP-CRUD type finition';
        $list = null;
      
        try {
            $list = FinishType::all();

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
                'listUnite' => $listUnite,
                'error' => $e->getMessage(),
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);
        }       
    }

    //Enregistrer type finition
    public function save(Request $request)
    {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        try {
            $finishType = new FinishType;
            $finishType->setFinishTypeName($request->input('designation'));
            $finishType->setIncreasePercent($request->input('percent'));
            $finishType->save();

        } catch(Exception $e) {  
            return redirect()->route('finish_type', (['error' => $e->getMessage()]));
        }

        return redirect()->route('finish_type');
    }

    //Supprimer type finition
    public function delete(Request $request)
    {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }

        try {
            $finishType = FinishType::find($request->id_finish_type);
            $finishType->delete();
        } catch(Exception $e) {  
            return redirect()->route('finish_type', (['error' => $e->getMessage()]));
        }

        return redirect()->route('finish_type');
    }

    //Modifier type finition
    public function edit(Request $request)
    {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
          
        $css = ['css/styleTable.css'];
        $js = ['js/script-table-tri.js'];
        $contentPage = 'pages/crud/finish_type';
        $title = 'Construction BTP-CRUD type finition';
        $finishType = null;
        $list = null;

        try {
          
            $finishType = FinishType::find($request->id_finish_type);
            $list = FinishType::all();
 
            if(isset($_GET['error'])) {
                throw new Exception($_GET['error']);
            }
 
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'finishType' => $finishType,
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);
 
        } catch(Exception $e) {  
            return view('pages/template')->with([
                'css' => $css,
                'js' => $js,
                'contentPage' => $contentPage,
                'list' => $list,
                'finishType' => $finishType,
                'error' => $e->getMessage(),
                'title' => $title,
                'profilconnected' => $profilconnected
            ]);
        } 
    }

    //Mettre a jour type finition
    public function update(Request $request)
    {
        //Verifier l'authentification
        $profilconnected = session()->get('authentified');
        if(!isset($profilconnected)) {
            return redirect()->route('pageLogin');
        }
         
        try {
            
            $finishType = FinishType::find($request->id_finish_type);
            $finishType->setFinishTypeName($request->input('designation'));
            $finishType->setIncreasePercent($request->input('percent'));
            $finishType->save();
        } catch(Exception $e) {  
            return redirect()->route('edit_finish_type', (['error' => $e->getMessage(), 'id_finish_type' => $request->id_finish_type]));
        }
 
        return redirect()->route('finish_type');
     }

}
