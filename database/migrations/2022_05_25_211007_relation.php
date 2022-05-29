<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Relation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::table('thumbniles', function (Blueprint $table) {
            $table->foreign('id_admin')->references("id")->on("users")->cascadeOnUpdate()->nullOnDelete();
        });
        Schema::table('mereks', function (Blueprint $table) {
            $table->foreign('id_admin')->references("id")->on("users")->cascadeOnUpdate()->nullOnDelete();
        });
        Schema::table('barangs', function (Blueprint $table) {
            $table->foreign('id_merek')->references("id")->on("mereks")->cascadeOnUpdate()->cascadeOnDelete();
        });
        Schema::table('detail_barangs', function (Blueprint $table) {
            $table->foreign('id_barang')->references("id")->on("barangs")->cascadeOnUpdate()->cascadeOnDelete();
        });
        Schema::table('promos', function (Blueprint $table) {
            $table->foreign('id_barang')->references("id")->on("barangs")->cascadeOnUpdate()->cascadeOnDelete();
        });
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->foreign('id_detail_barang')->references("id")->on("detail_barangs")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_pelanggan')->references("id")->on("pelanggans")->cascadeOnUpdate()->cascadeOnDelete();
        });
        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreign('id_pelanggan')->references("id")->on("pelanggans")->cascadeOnUpdate()->nullOnDelete();
        });
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->foreign('id_detail_barang')->references("id")->on("detail_barangs")->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('payment_id')->references("payment_id")->on("transaksis")->cascadeOnUpdate()->nullOnDelete();
        });
        Schema::table('komentars', function (Blueprint $table) {
            $table->foreign('id_detail_transaksi')->references("id")->on("detail_transaksis")->cascadeOnUpdate()->cascadeOnDelete();
        });
        Schema::table('balasans', function (Blueprint $table) {
            $table->foreign('id_komentar')->references("id")->on("komentars")->cascadeOnUpdate()->cascadeOnDelete();
        });
        Schema::table('status_transaksis', function (Blueprint $table) {
            $table->foreign('id_transaksi')->references("id")->on("transaksis")->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_kategori_transaksi')->references("id")->on("kategori_transaksis")->cascadeOnUpdate()->cascadeOnDelete();
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
