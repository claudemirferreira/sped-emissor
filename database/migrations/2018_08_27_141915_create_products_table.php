<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 60)->unique();
            $table->string('descricao', 120);
            $table->string('ean', 14)->nullable();
            $table->string('ean_unidade_tributavel', 14)->nullable();
            $table->string('ex_tipi', 3)->nullable();
            $table->string('genero', 2)->nullable();
            $table->string('ncm', 8)->nullable();
            $table->string('cest', 7)->nullable();
            $table->string('unidade_comercial', 7)->nullable();
            $table->decimal('valor_unitario_comercial', 16, 4)->nullable();
            $table->string('unidade_tributavel', 7)->nullable();
            $table->decimal('quantidade_tributavel', 17, 4)->nullable();
            $table->decimal('valor_unitario_tributavel', 16, 4)->nullable();
            $table->string('ipi_codigo_enquadramento_legal', 3)->nullable();
            $table->string('ipi_cnpj_produtor', 15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
