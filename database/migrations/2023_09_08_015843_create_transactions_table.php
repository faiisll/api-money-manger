<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("desc");
            $table->bigInteger("amount")->default(0);
            $table->unsignedBigInteger("walletId")->nullable();
            $table->unsignedBigInteger("categoryId")->nullable();
            $table->bigInteger("userId");
            $table->date("date");
            $table->timestamps();
            $table->foreign('walletId')->references('id')->on('wallets')->onDelete("cascade");
            $table->foreign('categoryId')->references('id')->on('categories')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
