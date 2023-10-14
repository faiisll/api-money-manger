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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("icon");
            $table->string("variant")->nullable()->default("primary");
            $table->boolean("type")->default(1);
            $table->boolean("isDefault")->default(false);
            $table->unsignedBigInteger("userId")->nullable();
            $table->timestamps();
            $table->foreign('userId')->references('id')->on('users')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
