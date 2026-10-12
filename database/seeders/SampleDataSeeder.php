<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Agriculture & Forestry', 'description' => 'Crops, livestock, and forestry research'],
            ['name' => 'Aquatic & Marine', 'description' => 'Fisheries and aquatic resources'],
            ['name' => 'Natural Resources & Environment', 'description' => 'Ecosystems, climate, and conservation'],
            ['name' => 'Food & Nutrition', 'description' => 'Food processing, safety, and nutrition studies'],
        ])->mapWithKeys(fn (array $data) => [
            $data['name'] => Category::firstOrCreate(
                ['name' => $data['name']],
                ['description' => $data['description']],
            ),
        ]);

        $proponent = User::where('email', 'proponent@example.com')->firstOrFail();
        $reviewer = User::where('email', 'reviewer@example.com')->firstOrFail();
        $leadOne = User::where('email', 'maria@example.com')->firstOrFail();
        $leadTwo = User::where('email', 'jose@example.com')->firstOrFail();

        $samples = [
            [
                'project_title' => 'Climate-resilient Rice Varieties',
                'category' => 'Agriculture & Forestry',
                'status' => 'new',
                'description' => 'Developing rice varieties that tolerate drought and flooding.',
                'objective' => 'Increase yields for smallholder farms under variable rainfall.',
                'est_budget' => 1500000,
                'project_lead' => $leadOne->id,
                'timelines' => [
                    ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
                ],
            ],
            [
                'project_title' => 'Mangrove Rehabilitation Monitoring',
                'category' => 'Natural Resources & Environment',
                'status' => 'approved',
                'description' => 'Tracking mangrove recovery across coastal sites.',
                'objective' => 'Restore degraded mangrove areas and measure carbon capture.',
                'est_budget' => 875000,
                'project_lead' => $leadTwo->id,
                'timelines' => [
                    ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
                    ['activity' => 'Proposal reviewed', 'updated_to' => 'approved', 'updated_by' => $reviewer->id, 'remarks' => 'Objectives and budget are aligned with the agency priorities.'],
                ],
            ],
            [
                'project_title' => 'Seaweed Farming Productivity',
                'category' => 'Aquatic & Marine',
                'status' => 'disapproved',
                'description' => 'Assessing seaweed yields for coastal livelihood programs.',
                'objective' => 'Identify low-cost practices that raise seaweed output.',
                'est_budget' => 620000,
                'project_lead' => $leadOne->id,
                'timelines' => [
                    ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
                    ['activity' => 'Proposal reviewed', 'updated_to' => 'disapproved', 'updated_by' => $reviewer->id, 'remarks' => 'Duplicate of an ongoing coastal program; please consolidate.'],
                ],
            ],
            [
                'project_title' => 'Smart Aquaponics Water Quality',
                'category' => 'Aquatic & Marine',
                'status' => 'new',
                'description' => 'Sensor-based monitoring of water quality in aquaponics systems.',
                'objective' => 'Reduce fish mortality through automated water quality alerts.',
                'est_budget' => 1100000,
                'project_lead' => $leadTwo->id,
                'timelines' => [
                    ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
                ],
            ],
            [
                'project_title' => 'Native Bee Pollination Program',
                'category' => 'Agriculture & Forestry',
                'status' => 'approved',
                'description' => 'Deploying native bee colonies to boost crop pollination.',
                'objective' => 'Improve fruit set and yields through managed pollination.',
                'est_budget' => 430000,
                'project_lead' => $leadOne->id,
                'timelines' => [
                    ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
                    ['activity' => 'Proposal reviewed', 'updated_to' => 'approved', 'updated_by' => $reviewer->id, 'remarks' => 'Approved for pilot implementation.'],
                ],
            ],
            [
                'project_title' => 'Ready-to-Eat Vegetable Puree',
                'category' => 'Food & Nutrition',
                'status' => 'new',
                'description' => 'Developing shelf-stable vegetable puree for child nutrition programs.',
                'objective' => 'Provide affordable and nutritious food products for feeding programs.',
                'est_budget' => 980000,
                'project_lead' => $leadTwo->id,
                'timelines' => [
                    ['activity' => 'Proposal submitted', 'updated_to' => 'new', 'updated_by' => $proponent->id, 'remarks' => null],
                ],
            ],
        ];

        foreach ($samples as $sample) {
            $proposal = Proposal::firstOrCreate(
                ['project_title' => $sample['project_title']],
                [
                    'category_id' => $categories[$sample['category']]->id,
                    'status' => $sample['status'],
                    'description' => $sample['description'],
                    'objective' => $sample['objective'],
                    'attachment' => ['attachments/sample.pdf'],
                    'start_date' => now()->addMonth()->toDateString(),
                    'end_date' => now()->addMonths(6)->toDateString(),
                    'est_budget' => $sample['est_budget'],
                    'created_by' => $proponent->id,
                    'project_lead' => $sample['project_lead'],
                ],
            );

            foreach ($sample['timelines'] as $timeline) {
                $proposal->timelines()->firstOrCreate(
                    ['activity' => $timeline['activity'], 'updated_to' => $timeline['updated_to']],
                    ['updated_by' => $timeline['updated_by'], 'remarks' => $timeline['remarks']],
                );
            }
        }
    }
}
