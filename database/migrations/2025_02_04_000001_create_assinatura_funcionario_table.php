<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assinatura_funcionario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('COD_FUNCIONARIO');
            $table->string('ASSINATURA', 500)->nullable();
            $table->text('ASSINATURA_HEX')->nullable();
            $table->longText('ASSINATURA_BLOB')->nullable();
            $table->boolean('RESPONSAVEL_LIBERACAO')->default(true);

            $table->unique('COD_FUNCIONARIO');
        });
    }

    public function down(): void {
        Schema::dropIfExists('assinatura_funcionario');
    }
};
