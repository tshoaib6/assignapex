<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scenario;

class ScenariosSeeder extends Seeder
{
    public function run(): void
    {
        $scenarios = [
            [
                'scenario_type' => 'Benchmarking',
                'scenario' => 'Voice Long Call',
                'description' => 'Mobile to Mobile',
                'network' => '2G/3G/4G (VoLTE Enable)',
                'duration' => '300s',
                'pause' => '30s',
                'number_of_devices' => 6
            ],
            [
                'scenario_type' => 'Benchmarking',
                'scenario' => 'Voice Short Call',
                'description' => 'Mobile to Mobile',
                'network' => '2G/3G/4G (VoLTE Enable)',
                'duration' => '10s',
                'pause' => '30s',
                'number_of_devices' => 6
            ],
            [
                'scenario_type' => 'Complain',
                'scenario' => 'Voice long call',
                'description' => 'Mobile to Mobile',
                'network' => '2G/3G/4G (VoLTE Enable)',
                'duration' => '300s',
                'pause' => '30s',
                'number_of_devices' => 6
            ],
            [
                'scenario_type' => 'Obligation',
                'scenario' => 'Voice Call',
                'description' => 'IVR',
                'network' => '2G/3G/4G (VoLTE Enable)',
                'duration' => '90s',
                'pause' => '30s',
                'number_of_devices' => 3
            ]
            // ✅ You can keep adding more rows here
        ];

        foreach ($scenarios as $s) {
            Scenario::updateOrCreate([
                'scenario_type' => $s['scenario_type'],
                'scenario' => $s['scenario']
            ], $s);
        }
    }
}
