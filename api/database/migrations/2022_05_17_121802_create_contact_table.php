<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('cpf', 11)->unique('cpf');
            $table->string('nome', 100);
            $table->string('telefone', 15);
            $table->string('cep', 10);
            $table->string('logradouro');
            $table->string('numero', 20);
            $table->string('complemento', 50)->nullable();
            $table->string('bairro', 150);
            $table->string('localidade', 150);
            $table->char('uf', 2);
            $table->string('latitude', 30);
            $table->string('longitude', 30);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact');
    }
}
