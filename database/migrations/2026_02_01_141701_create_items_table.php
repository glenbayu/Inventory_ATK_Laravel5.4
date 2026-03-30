<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique(); // Barcode/Kode Barang
            $table->string('name');
            $table->string('category'); // Stationery, Cleaning, dll
            $table->string('unit'); // Pcs, Pack, Rim

            // Logika Inventory
            $table->integer('stock')->default(0);
            $table->integer('safety_stock')->default(5); // Batas minimal untuk alert merah
            $table->integer('max_stock')->default(100);  // Acuan tombol "Reset Stock"

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
        Schema::dropIfExists('items');
    }
}
