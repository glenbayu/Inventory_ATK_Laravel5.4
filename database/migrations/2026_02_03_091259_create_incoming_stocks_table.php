<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomingStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_stocks', function (Blueprint $table) {
            $table->increments('id');
            // Kita catat ID barang yang direstock
            $table->integer('item_id')->unsigned();
            // Kita catat ID Admin yang melakukan restock (biar ketahuan pelakunya)
            $table->integer('user_id')->unsigned()->nullable();
            // Jumlah yang dimasukkan
            $table->integer('qty');
            // Tanggal otomatis (created_at)
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
        Schema::dropIfExists('incoming_stocks');
    }
}
