<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans');
            $table->string('stripe_id');
            $table->string('interval');
            $table->float('amount');
            $table->string('label');
        });

        DB::table('prices')->insert($this->testPrices());
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function testPrices(): array
    {
        return [
            [
                'plan_id' => 1,
                'stripe_id' => 'price_rand1',
                'interval' => 'year',
                'amount' => 0,
                'label' => '0,00 €/anno',
            ],
            [
                'plan_id' => 1,
                'stripe_id' => 'price_rand2',
                'interval' => 'month',
                'amount' => 0,
                'label' => '0,00 €/mese',
            ],
            [
                'plan_id' => 2,
                'stripe_id' => 'price_rand3',
                'interval' => 'year',
                'amount' => 49.99,
                'label' => '49,99 €/anno',
            ],
            [
                'plan_id' => 2,
                'stripe_id' => 'price_rand4',
                'interval' => 'month',
                'amount' => 4.99,
                'label' => '4,99 €/mese',
            ],
            [
                'plan_id' => 3,
                'stripe_id' => 'price_rand5',
                'interval' => 'month',
                'amount' => 9.99,
                'label' => '9,99 €/mese',
            ],
            [
                'plan_id' => 3,
                'stripe_id' => 'price_rand6',
                'interval' => 'year',
                'amount' => 99.99,
                'label' => '99,99 €/anno',
            ],
        ];
    }
};
