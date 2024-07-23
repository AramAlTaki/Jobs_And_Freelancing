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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->refrences('id')->on('companies');
            $table->string('company_name');
            $table->string('company_logo');
            $table->enum('company_location',['Damascus','Aleppo','Hama','Homs','Latakia','Tartus','Raqqa','Deir Elzor','Idlib','Daraa','Qunetiera','Alhasakah','Damascus Countryside','As Suwayda'])->nullable();
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
            $table->enum('enrollment_status',["Full Time","Part Time","Three Quarters","Remotely","Hourly"]);
            $table->enum('prefered_gender',["Male","Female","Any"]);
            $table->text('prefered_experience') -> default('No Specified Experience');
            $table->text('detailed_location');
            $table->text('requirements') -> default('No Specified Requirements');
            $table->text('promises') -> default('No Specified Promises');
            $table->text('job_information') -> default('No More Information About This Job');
            $table->date('application_deadline');
            $table->string('expected_salary') -> default('Decide After Interview');
            $table->boolean('is_taken') -> default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
