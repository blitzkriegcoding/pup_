<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LotesCreditos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotes_creditos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';

            $table->increments('id_lote');
            $table->integer('nro_cuota')->unsigned();
            $table->date('fecha_vencimiento');
            $table->decimal('interes', 11, 2);
            $table->decimal('amortizacion', 11, 2);
            $table->decimal('valor_cuota', 11, 2);
            $table->decimal('saldo_insoluto', 11, 2);
            $table->string('estado_cuota', 255);
            $table->string('tipo_cuota', 255);
            $table->date('fecha_pago')->nullable();
            $table->string('rut_cliente', 255);
            $table->integer('nro_credito')->unsigned();
            $table->string('nombres_cliente', 255);
            $table->string('apellidos_cliente', 255);
            $table->integer('id_carga')->unsigned();
            $table->dateTime('fecha_hora_carga');

            $table->foreign('id_carga')->references('id_carga')->on('logs_cargas_crediticias')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
