<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            // Relasi ke User (Siapa yang minta)
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Relasi ke Item (Barang apa)
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            $table->integer('qty'); // Jumlah minta

            // Status: 'pending', 'approved', 'rejected'
            $table->string('status')->default('pending');

            // Kode unik transaksi (misal: TRX-20250101-001) agar terlihat profesional
            $table->string('transaction_code')->nullable();

            $table->text('reason')->nullable(); // Keperluan (opsional)
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
        Schema::dropIfExists('transactions');
    }
}
