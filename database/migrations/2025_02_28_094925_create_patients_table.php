<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('address')->nullable();
            $table->string('codice_fiscale')->nullable();
            $table->date('therapy_start_date');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedTinyInteger('weight')->nullable();
            $table->unsignedTinyInteger('height')->nullable();
            $table->string('education')->nullable();
            $table->string('job')->nullable();
            $table->string('cohabitants')->nullable();
            $table->string('drugs')->nullable();
            $table->timestamps();
        });
    }
};
