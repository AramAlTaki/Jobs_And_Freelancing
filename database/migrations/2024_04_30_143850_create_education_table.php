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
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id') -> references('id') -> on('job_seekers');
            $table->enum('degree',['None','PrimaryShcool','SecondarySchool','HighSchool','Associate','Bachelor','Diploma','Master','Doctorate']);
            $table->enum('at',[
                "None",
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
            $table->string('specialized_in');
            $table->string('from');
            $table->year('in');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
