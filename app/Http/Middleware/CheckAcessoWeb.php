<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AcessoWebService;
use Symfony\Component\HttpFoundation\Response;

class CheckAcessoWeb
{
    public function __construct(
        protected AcessoWebService $acessoWebService
    ) {}

    /**
     * Exige que o usuário tenha pelo menos uma das permissões informadas.
     * Uso na rota: ->middleware('acesso.web:EDITAR_WEB') ou ->middleware('acesso.web:EXCLUIR_WEB,EDITAR_WEB')
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        $codFuncionario = (int) $request->user()->COD_FUNCIONARIO;
        $list = array_map('trim', explode(',', $permissions));
        $hasAny = false;
        foreach ($list as $permission) {
            if ($this->acessoWebService->hasAcesso($codFuncionario, $permission)) {
                $hasAny = true;
                break;
            }
        }
        if (!$hasAny) {
            return response()->json([
                'message' => 'Você não tem acesso para esse tipo de ação.',
            ], 403);
        }

        return $next($request);
    }
}
