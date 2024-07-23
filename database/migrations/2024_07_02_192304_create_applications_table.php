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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId("job_seeker_id")->references("id")->on("job_seekers");
            $table->string("user_full_name");
            $table->morphs("model");
            $table->string("job_title");
            $table->enum("status",["Pending","Accepted","Rejected"])->default("Pending");
            $table->string("cv_url")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
