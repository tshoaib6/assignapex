<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostProcessorRejection;

class PostProcessorRejectionSeeder extends Seeder
{
    public function run(): void
    {
        $issues = [
            'Route is not matching',
            'Data Throuput Issue',
            'Voice Scenario Issue',
            'Log Files are corrupted',
            'Missing Log files',
            'Wrong Device Labeling',
            'Scenario issue',
            'Scanner Frequencies are missing',
            'Device Connectivity Issue',
            'Script Issue',
        ];

        foreach ($issues as $issue) {
            PostProcessorRejection::updateOrCreate(['field' => $issue]);
        }
    }
}
