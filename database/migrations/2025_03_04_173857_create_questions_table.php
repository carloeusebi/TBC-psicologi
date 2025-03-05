<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained('questionnaires');
            $table->string('text')->nullable();
            $table->boolean('is_reversed')->default(false);
            $table->unsignedInteger('order')->default(0);
        });

        $this->seedFromCsv();
    }

    private function seedFromCsv(): void
    {
        $handle = fopen(database_path('csv/questions.csv'), 'r');
        $columns = 5;
        $chunkSize = 1000;
        $chunks = [];

        try {
            $stmt = $this->prepareChunkedStatement($chunkSize, $columns);

            while (($row = fgetcsv($handle)) !== false) {
                $chunks = array_merge($chunks, [
                    $row[0], // id
                    $row[1], // questionnaire_id
                    $row[2], // text
                    $row[3], // is_reversed
                    $row[4], // order
                ]);
            }

            if (count($chunks) === $chunkSize * $columns) {
                $stmt->execute($chunks);
                $chunks = [];
            }

            if (! empty($chunks)) {
                $remainingRows = count($chunks) / $columns;
                $stmt = $this->prepareChunkedStatement($remainingRows, $columns);
                $stmt->execute($chunks);
            }
        } finally {
            fclose($handle);
            if (Illuminate\Support\Facades\DB::getDriverName() === 'pgsql') {
                DB::select('SELECT pg_catalog.setval(\'questions_id_seq\', (SELECT MAX(id) FROM questions))');
            }
        }
    }

    private function prepareChunkedStatement(int $chunkSize, int $columns): PDOStatement
    {
        $rowPlaceholders = '('.implode(',', array_fill(0, $columns, '?')).')';
        $placeholders = implode(',', array_fill(0, $chunkSize, $rowPlaceholders));

        return DB::connection()->getPdo()->prepare("INSERT INTO questions (id, questionnaire_id, text, is_reversed, \"order\") VALUES {$placeholders}");
    }
};
