<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarefaMarcadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarefa_marcadores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("tarefa_id")->unsigned();
            $table->bigInteger("marcador_id")->unsigned();
            $table->foreign("tarefa_id")->references("id")->on("tarefas")->onDelete('cascade');;
            $table->foreign("marcador_id")->references("id")->on("marcadores");
            $table->unique(["tarefa_id", "marcador_id"], "tarefa_marcador_unique");
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
        Schema::dropIfExists('tarefa_marcadores');
    }
}
