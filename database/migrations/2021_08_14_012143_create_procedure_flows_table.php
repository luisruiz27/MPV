<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcedureFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedure_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procedure_id')->comment('Hoja de Ruta');
            $table->foreign('procedure_id')->references('id')->on('procedures')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('area_id')->comment('Área de Trabajo');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('user_id')->comment('Usuario');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('procedure_flows');
    }
}
