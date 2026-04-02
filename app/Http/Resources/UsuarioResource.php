<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'NOME' => $this->NOME,
            'FOTO' => $this->FOTO,
            'EMAIL' => $this->EMAIL,
        ];
    }
}
