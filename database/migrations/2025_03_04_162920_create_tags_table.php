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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 7);
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained('tags');
            $table->morphs('taggable');
        });

        $this->seedFromCsv();
    }

    private function seedFromCsv(): void
    {
        $handle = fopen(database_path('csv/tags.csv'), 'r');
        $columns = 3;
        $chunkSize = 11; // based on the total number of tags in the csv
        $chunks = [];

        try {
            $stmt = $this->prepareChunkedStatement($chunkSize, $columns);

            while (($row = fgetcsv($handle)) !== false) {
                $chunks = array_merge($chunks, [$row[0], $row[1], $row[2]]);
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
            if (DB::getDriverName() === 'pgsql') {
                DB::select('SELECT pg_catalog.setval(\'tags_id_seq\', (SELECT MAX(id) FROM tags))');
            }
        }
    }

    private function prepareChunkedStatement(int $chunkSize, int $columns): PDOStatement
    {
        $rowPlaceholders = '('.implode(',', array_fill(0, $columns, '?')).')';
        $placeholders = implode(',', array_fill(0, $chunkSize, $rowPlaceholders));

        return DB::connection()->getPdo()->prepare("INSERT INTO tags (id, name, color) VALUES $placeholders");
    }
};
