<?php

declare(strict_types=1);

use App\Models\Questionnaire;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->text('description');
            $table->boolean('is_visible');
            $table->timestamps();
        });

        $this->seedFromCsv();
        $this->seedTaggablesFromCsv();
    }

    private function seedFromCsv(): void
    {
        $handle = fopen(database_path('csv/questionnaires.csv'), 'r');
        $columns = 7;
        $chunkSize = 20;
        $chunks = [];

        try {
            $stmt = $this->prepareChunkedStatement($chunkSize, $columns);

            while (($row = fgetcsv($handle)) !== false) {
                $chunks = array_merge($chunks, [
                    $row[0], // id
                    $row[2], // name
                    Str::slug($row[2]), // slug
                    $row[3], // description
                    true, // is_visible
                    now(), // created_at
                    now(), // updated_at
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
            DB::select('SELECT pg_catalog.setval(\'questionnaires_id_seq\', (SELECT MAX(id) FROM questionnaires))');
        }
    }

    private function prepareChunkedStatement(int $chunkSize, int $columns): PDOStatement
    {
        $rowPlaceholders = '('.implode(',', array_fill(0, $columns, '?')).')';
        $placeholders = implode(',', array_fill(0, $chunkSize, $rowPlaceholders));

        return DB::connection()->getPdo()->prepare("INSERT INTO questionnaires (id, name, slug, description, is_visible, created_at, updated_at) VALUES {$placeholders}");
    }

    private function seedTaggablesFromCsv(): void
    {
        $handle = fopen(database_path('csv/questionnaire_tag.csv'), 'r');
        $columns = 3;
        $chunkSize = 24; // based on the total number of tags in the csv
        $chunks = [];

        try {
            $stmt = $this->prepareTaggableChunkedStatement($chunkSize, $columns);

            while (($row = fgetcsv($handle)) !== false) {
                $chunks = array_merge($chunks, [
                    $row[2], // tag_id
                    $row[1], // taggable_id
                    Questionnaire::class, // taggable_type
                ]);
            }

            if (count($chunks) === $chunkSize * $columns) {
                $stmt->execute($chunks);
                $chunks = [];
            }

            if (! empty($chunks)) {
                $remainingRows = count($chunks) / $columns;
                $stmt = $this->prepareTaggableChunkedStatement($remainingRows, $columns);
                $stmt->execute($chunks);
            }
        } finally {
            fclose($handle);
            DB::select('SELECT pg_catalog.setval(\'taggables_id_seq\', (SELECT MAX(id) FROM taggables))');
        }
    }

    private function prepareTaggableChunkedStatement(int $chunkSize, int $columns): PDOStatement
    {
        $rowPlaceholders = '('.implode(',', array_fill(0, $columns, '?')).')';
        $placeholders = implode(',', array_fill(0, $chunkSize, $rowPlaceholders));

        return DB::connection()->getPdo()->prepare("INSERT INTO taggables (tag_id, taggable_id, taggable_type) VALUES $placeholders");
    }
};
