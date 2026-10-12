<?php

namespace Database\Seeders;

use App\Models\Proposal;
use App\Models\Timeline;
use App\Models\User;
use Illuminate\Database\Seeder;

class TimelineSeeder extends Seeder
{
    public function run(): void
    {
        $proponent = User::where('email', 'proponent@example.com')->firstOrFail();
        $reviewer = User::where('email', 'reviewer@example.com')->firstOrFail();

        $timelines = [
            'Climate-resilient Rice Varieties' => [
                ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
            ],
            'Mangrove Rehabilitation Monitoring' => [
                ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
                ['activity' => 'Proposal reviewed', 'updated_to' => 'approved', 'updated_by' => $reviewer->id, 'remarks' => 'Objectives and budget are aligned with the agency priorities.'],
            ],
            'Seaweed Farming Productivity' => [
                ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
                ['activity' => 'Proposal reviewed', 'updated_to' => 'disapproved', 'updated_by' => $reviewer->id, 'remarks' => 'Duplicate of an ongoing coastal program; please consolidate.'],
            ],
            'Smart Aquaponics Water Quality' => [
                ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
            ],
            'Native Bee Pollination Program' => [
                ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
                ['activity' => 'Proposal reviewed', 'updated_to' => 'approved', 'updated_by' => $reviewer->id, 'remarks' => 'Approved for pilot implementation.'],
            ],
            'Ready-to-Eat Vegetable Puree' => [
                ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
            ],
        ];

        foreach ($timelines as $projectTitle => $entries) {
            $proposal = Proposal::where('project_title', $projectTitle)->first();

            if (! $proposal) {
                continue;
            }

            foreach ($entries as $entry) {
                Timeline::firstOrCreate(
                    [
                        'project_id' => $proposal->id,
                        'activity' => $entry['activity'],
                        'updated_to' => $entry['updated_to'],
                    ],
                    [
                        'updated_by' => $entry['updated_by'],
                        'remarks' => $entry['remarks'],
                    ],
                );
            }
        }
    }
}
