<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReviewerRejection;

class ReviewerRejectionSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            'route mismatched',
            'Scenario Mismatched',
            'Scanner Frequencies mismatch with UE',
            'Accessibility issue',
            'Retainbility issue',
            'Mobility issue',
        ];

        $reports = [
            'missing slide',
            'missing data',
            'table and legends mismatched',
            'Throughput issue',
            'Coverage Issue',
            'Sync Issue',
            'Call Statistics Mismatched',
            'Data Statistics Mismatched',
        ];

        foreach ($fields as $field) {
            ReviewerRejection::updateOrCreate(['category' => $field]);
        }

        foreach ($reports as $report) {
            ReviewerRejection::updateOrCreate(['issue' => $report]);
        }
    }
}
