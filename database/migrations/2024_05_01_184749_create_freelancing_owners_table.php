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
        Schema::create('freelancing_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->refrences('id')->on('users');
            $table->string('profile_photo');
            $table->enum('gender',['FEMALE','MALE']);
            $table->string('phone_number',10)->nullable();
            $table->text('bio')->nullable();
            $table->text('languages')->nullable();
            $table->enum('location',['Damascus','Aleppo','Hama','Homs','Latakia','Tartus','Raqqa','Deir Elzor','Idlib','Daraa','Qunetiera','Alhasakah','Damascus Countryside','As Suwayda','Others'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancing_owners');
    }
};
