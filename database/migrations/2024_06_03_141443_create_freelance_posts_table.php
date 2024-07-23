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
        Schema::create('freelance_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_id')->refrences('id')->on('freelancing_owners');
            $table->string('profile_photo');
            $table->string('phone_number',10)->nullable();
            $table->enum('location',['Damascus','Aleppo','Hama','Homs','Latakia','Tartus','Raqqa','Deir Elzor','Idlib','Daraa','Qunetiera','Alhasakah','Damascus Countryside','As Suwayda','Others'])->nullable();
            $table->enum('general_job_title',[
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
            $table->string('specialization') -> default('Not Specified');
            $table->string('earnings') -> default('Determined Later');
            $table->text('job_information') -> default('No More Information About This Job');
            $table->text('requirements') -> default('No Specified Requirements');
            $table->date('application_deadline');
            $table->boolean('is_taken') -> default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelance_posts');
    }
};
