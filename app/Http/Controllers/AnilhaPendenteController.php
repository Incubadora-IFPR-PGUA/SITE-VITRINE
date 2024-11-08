<?php

namespace App\Http\Controllers;

use App\Models\AnilhaPendente;
use App\Models\AnilhaCadastro;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnilhaPendenteController extends Controller {
    protected $apiService;

    public function __construct(ApiService $apiService) {
        $this->apiService = $apiService;
    }

    public function index() {
        // $this->authorize('hasFullPermission', AnilhaPendente::class);
        $data = $this->apiService->listarAnilhasPendentes();  
        return view('pendente.index', compact('data'));
    }

    public function reload() {
        // $this->authorize('hasFullPermission', AnilhaPendente::class);
        $data = $this->apiService->listarAnilhasPendentes();
        return response()->json($data); 
    }

    public function aceitarPendente($id) {
        // $this->authorize('hasFullPermission', AnilhaRegistro::class);
        $name = request()->input('name');
        $data = $this->apiService->aceitarPendente($id, $name);
        if (isset($data['message'])) {
            return redirect()->back()->with('status', $data['message']);
        }
        return redirect()->back()->with('error', 'Erro ao processar a solicitação.');
    }

    public function update(Request $request, $id) {
        $this->authorize('hasFullPermission', AnilhaPendente::class);
        $data = $this->apiService->obterAnilhaPendentePorId($id);
        if(isset($data)) {
            $dadosAtualizacao = [
                'name' => $request->input('name')
            ];
            $data = $this->apiService->atualizarAnilhaPendente($id, $dadosAtualizacao);
            return redirect()->route('pendente.index');
        }
        return "<h1>ERRO: CADASTRO NÃO ENCONTRADO!</h1>";
    }

    public function destroy($id) {
        $this->authorize('hasFullPermission', AnilhaPendente::class);
        $data = $this->apiService->obterAnilhaPendentePorId($id);
        if(isset($data)) {
            $data = $this->apiService->deletarAnilhaPendente($id);
            return redirect()->route('pendente.index');
        }
        return "<h1>ERRO: ANILHA NÃO ENCONTRADA!</h1>";
    }
}