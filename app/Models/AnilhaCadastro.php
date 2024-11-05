<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class AnilhaCadastro extends Model {
    use HasFactory;
    use SoftDeletes;
    use Sortable;

    public function registros() {
        return $this->hasMany(AnilhaRegistro::class, 'anilha_id');
    }

    protected $table = 'anilhas_cadastros';

    public $sortable = ['nome', 'numero_anilha'];
}