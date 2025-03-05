<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->primary('id');
        });

        Schema::create('evaluations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id')->constrained('patients');
            $table->string('title');
            $table->boolean('has_introduction');
            $table->text('introduction')->nullable();
            $table->boolean('hap_patient_form');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluation_questionnaire', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('evaluation_id')->constrained('evaluations');
            $table->foreignId('questionnaire_id')->constrained('questionnaires');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }
};
