<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id')->unique();
            $table->string('name');
            $table->text('description');
            $table->json('features')->nullable();
            $table->json('abilities')->nullable();
        });
    }
};
