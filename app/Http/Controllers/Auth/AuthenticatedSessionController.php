<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Library\GoogleClient;
use App\Library\Authenticate;
use Socialite;

class AuthenticatedSessionController extends Controller {
    public function create() {
        // TEMPORÁRIO: Login automático SEM BANCO DE DADOS - apenas usando sessão
        // Cria um usuário fake temporário na sessão
        Session::put('auth', true);
        Session::put('user_id', 1);
        Session::put('user_name', 'Usuário Temporário');
        Session::put('user_email', 'usuario@temp.com');
        Session::put('role_id', 1);
        
        // Carrega permissões padrão na sessão (sem depender do banco)
        Session::put('user_permissions', [
            'cadastro' => true,
            'pendente' => true,
            'registro' => true,
            'phmetro' => true,
            'macaddress' => true,
            'horta' => true,
        ]);
        
        return redirect()->route('home');
        
        // Código original comentado temporariamente:
        // $googleClient = new GoogleClient;
        // $googleClient->init();
        // if ($googleClient->authenticated()){
        //     $auth = new Authenticate();
        //     return $auth->authGoogle($googleClient->getData());
        // }
        // return view('auth.login', ['authUrl' => $googleClient->generateLink()]);
    }

    public function store(LoginRequest $request) {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }
    
    public function destroy(Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}