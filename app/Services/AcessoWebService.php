<?php

namespace App\Services;

use App\Models\Acesso;
use App\Models\AcessoUsuario;

class AcessoWebService
{
    /**
     * Verifica se o usuário tem a permissão no módulo Web (COD_TIPO_ACESSO = 230).
     * É necessário ter ACESSO = 'S' e a coluna correspondente (EDITAR, EXCLUIR ou INSERIR) = 'S'.
     */
    public function hasAcesso(int $codFuncionario, string $permission): bool
    {
        $row = AcessoUsuario::where('COD_FUNCIONARIO', $codFuncionario)
            ->where('COD_TIPO_ACESSO', Acesso::COD_TIPO_ACESSO_WEB)
            ->where('ACESSO', AcessoUsuario::SIM)
            ->first();

        if (!$row) {
            return false;
        }

        return match (strtoupper(trim($permission))) {
            'EDITAR_WEB' => strtoupper((string) $row->EDITAR) === AcessoUsuario::SIM,
            'EXCLUIR_WEB' => strtoupper((string) $row->EXCLUIR) === AcessoUsuario::SIM,
            'INSERIR_WEB' => strtoupper((string) $row->INSERIR) === AcessoUsuario::SIM,
            default => false,
        };
    }

    /**
     * Retorna as permissões Web do usuário (módulo 230).
     * Requer ACESSO = 'S'; EDITAR, EXCLUIR e INSERIR definem se pode editar, excluir ou inserir.
     */
    public function getPermissoesWeb(int $codFuncionario): array
    {
        $row = AcessoUsuario::where('COD_FUNCIONARIO', $codFuncionario)
            ->where('COD_TIPO_ACESSO', Acesso::COD_TIPO_ACESSO_WEB)
            ->where('ACESSO', AcessoUsuario::SIM)
            ->first();

        if (!$row) {
            return [
                'podeEditar' => false,
                'podeExcluir' => false,
                'podeInserir' => false,
                'nomes' => [],
            ];
        }

        $podeEditar = strtoupper((string) $row->EDITAR) === AcessoUsuario::SIM;
        $podeExcluir = strtoupper((string) $row->EXCLUIR) === AcessoUsuario::SIM;
        $podeInserir = strtoupper((string) $row->INSERIR) === AcessoUsuario::SIM;

        $nomes = [];
        if ($podeEditar) $nomes[] = 'EDITAR_WEB';
        if ($podeExcluir) $nomes[] = 'EXCLUIR_WEB';
        if ($podeInserir) $nomes[] = 'INSERIR_WEB';

        return [
            'podeEditar' => $podeEditar,
            'podeExcluir' => $podeExcluir,
            'podeInserir' => $podeInserir,
            'nomes' => $nomes,
        ];
    }

    /**
     * Retorna a lista de nomes de acesso do usuário (EDITAR_WEB, EXCLUIR_WEB, INSERIR_WEB).
     * Mantido para compatibilidade com o frontend (userAcessosWeb).
     */
    public function getAcessosNomes(int $codFuncionario): array
    {
        $permissoes = $this->getPermissoesWeb($codFuncionario);
        return $permissoes['nomes'];
    }
}
