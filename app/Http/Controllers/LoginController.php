<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\authentification\Users;
use App\Models\authentification\Authentified;
use App\Models\authentification\Client;
use Exception;

class LoginController extends Controller
{
    public function index()
    {
        
        return view('pages/auth/loginClient');
    }

    public function pageLoginAdmin() {
        return view('pages/auth/login');
    }

    //Authentification d'un client
    public function authenticateClient(Request $request)
    {
        try {
                $numero_telephone = $request->input('numero_telephone');

                $client = new Client;
                $client->setNumeroTelephone($numero_telephone);
                $authentified = new Authentified(2, $numero_telephone, true);
                session()->put('authentified', $authentified);

                return redirect()->route('estimate_list');
        } catch(Exception $e) {  
            return view('pages/auth/loginClient')->with('error', $e->getMessage());
        }
    }

    //Authentification d'un utilisateur
    public function authenticateAdmin(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|string',
                'mdp' => 'required|string',
            ], [
                'required' => 'Le champ :attribute est obligatoire.',
                'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            ]);
            

            // Créer un nouvel utilisateur avec les données validées
            $user = Users::authenticateAdmin($validatedData['username'], $validatedData['mdp']);
            if($user->profile_id!=1) {
                throw new Exception('Vous devrez etre un admin pour se connecter');
            }
            $authentified = new Authentified($user->profile_id, $user->username, true);
            session()->put('authentified', $authentified);

            return redirect()->route('tableau_bord');

        } catch(Exception $e) {  
            return view('pages/auth/login')->with('error', $e->getMessage());
        }
    }

    //Se deconnecter
    public function deconnect(Request $request) {
        $request->session()->flush();
        
        return redirect()->route('pageLogin');
    }
}
