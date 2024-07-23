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
        Schema::create('job_seekers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id') -> references('id') -> on('users');
            $table->string('profile_photo') -> nullable();
            $table->enum('gender',['MALE','FEMALE']);
            $table->date('date_of_birth');
            $table->enum('city',['Damascus','Aleppo','Hama','Homs','Latakia','Tartus','Raqqa','Deir Elzor','Idlib','Daraa','Qunetiera','Alhasakah','Damascus Countryside','As Suwayda']);
            $table->text('languages') -> nullable();
            $table->json('search_for');
            $table->text('additional_information')->nullable();
            $table->string('cv')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_seeker');
    }
};
