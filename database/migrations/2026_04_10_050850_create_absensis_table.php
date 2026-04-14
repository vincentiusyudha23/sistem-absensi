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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('waktu_masuk')->nullable();
            $table->dateTime('waktu_keluar')->nullable();
            $table->decimal('latitude1', 10, 7)->nullable();
            $table->decimal('longitude1', 10, 7)->nullable();
            $table->decimal('latitude2', 10, 7)->nullable();
            $table->decimal('longitude2', 10, 7)->nullable();
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->string('image_masuk')->nullable();
            $table->string('image_keluar')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
