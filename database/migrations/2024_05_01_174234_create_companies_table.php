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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->refrences('id')->on('users');
            $table->string('company_name');
            $table->string('company_email')->unique();
            $table->string('company_website')->unique()->nullable();
            $table->string('company_logo')->nullable();
            $table->enum('company_location',['Damascus','Aleppo','Hama','Homs','Latakia','Tartus','Raqqa','Deir Elzor','Idlib','Daraa','Qunetiera','Alhasakah','Damascus Countryside','As Suwayda']);
            $table->enum('company_industry',[
                "Accounting",
                "Administrative",
                "Arts and Design",
                "Business Development",
                "Community and Social Services",
                "Consulting",
                "Education",
                "Engineering",
                "Entrepreneurship",
                "Finance",
                "Healthcare Services",
                "Human Resources",
                "Information Technology",
                "Legal",
                "Marketing",
                "Media and Communication",
                "Military and Protective Services",
                "Operations",
                "Product Management",
                "Program and Project Management",
                "Purchasing",
                "Quality Assurance",
                "Real Estate",
                "Research",
                "Sales",
                "Support",
                "Tourism",
                "Others"
            ]);
            $table->string('company_size',7)->nullable();
            $table->text('description')->nullable();
            $table->year('founding_year');
            $table->json('social_media_links');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
