<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApexHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApexHistorySeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('data/apex_history.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("apex_history.csv not found at {$csvPath}");
            return;
        }

        DB::table('apex_history')->truncate();

        $handle = fopen($csvPath, 'r');
        $header = fgetcsv($handle); // skip header row
        $count  = 0;
        $batch  = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 8) continue;

            $batch[] = [
                'process_id'          => $row[0],
                'process_step_status' => $row[1] ?: null,
                'step_name'           => $row[2] ?: null,
                'step_user'           => $row[3] ?: null,
                'step_start'          => $this->parseDate($row[4]),
                'step_end'            => $this->parseDate($row[5]),
                'step_duration_min'   => is_numeric($row[6]) ? (int) $row[6] : null,
                'process_id_num'      => is_numeric($row[7]) ? (int) $row[7] : null,
                'created_at'          => now(),
                'updated_at'          => now(),
            ];

            if (count($batch) >= 100) {
                ApexHistory::insert($batch);
                $count += count($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            ApexHistory::insert($batch);
            $count += count($batch);
        }

        fclose($handle);
        $this->command->info("Imported {$count} Apex history records.");
    }

    private function parseDate(?string $value): ?string
    {
        if (empty($value)) return null;
        try {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }
}
