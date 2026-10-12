<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProposalSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');

        $proponent = User::where('email', 'proponent@example.com')->firstOrFail();
        $leadOne = User::where('email', 'maria@example.com')->firstOrFail();
        $leadTwo = User::where('email', 'jose@example.com')->firstOrFail();

        $proposals = [
            [
                'project_title' => 'Climate-resilient Rice Varieties',
                'category' => 'Agriculture & Forestry',
                'status' => 'new',
                'description' => 'Developing rice varieties that tolerate drought and flooding.',
                'objective' => 'Increase yields for smallholder farms under variable rainfall.',
                'est_budget' => 1500000,
                'project_lead' => $leadOne->id,
            ],
            [
                'project_title' => 'Mangrove Rehabilitation Monitoring',
                'category' => 'Natural Resources & Environment',
                'status' => 'approved',
                'description' => 'Tracking mangrove recovery across coastal sites.',
                'objective' => 'Restore degraded mangrove areas and measure carbon capture.',
                'est_budget' => 875000,
                'project_lead' => $leadTwo->id,
            ],
            [
                'project_title' => 'Seaweed Farming Productivity',
                'category' => 'Aquatic & Marine',
                'status' => 'disapproved',
                'description' => 'Assessing seaweed yields for coastal livelihood programs.',
                'objective' => 'Identify low-cost practices that raise seaweed output.',
                'est_budget' => 620000,
                'project_lead' => $leadOne->id,
            ],
            [
                'project_title' => 'Smart Aquaponics Water Quality',
                'category' => 'Aquatic & Marine',
                'status' => 'new',
                'description' => 'Sensor-based monitoring of water quality in aquaponics systems.',
                'objective' => 'Reduce fish mortality through automated water quality alerts.',
                'est_budget' => 1100000,
                'project_lead' => $leadTwo->id,
            ],
            [
                'project_title' => 'Native Bee Pollination Program',
                'category' => 'Agriculture & Forestry',
                'status' => 'approved',
                'description' => 'Deploying native bee colonies to boost crop pollination.',
                'objective' => 'Improve fruit set and yields through managed pollination.',
                'est_budget' => 430000,
                'project_lead' => $leadOne->id,
            ],
            [
                'project_title' => 'Ready-to-Eat Vegetable Puree',
                'category' => 'Food & Nutrition',
                'status' => 'new',
                'description' => 'Developing shelf-stable vegetable puree for child nutrition programs.',
                'objective' => 'Provide affordable and nutritious food products for feeding programs.',
                'est_budget' => 980000,
                'project_lead' => $leadTwo->id,
            ],
        ];

        foreach ($proposals as $proposal) {
            Proposal::firstOrCreate(
                ['project_title' => $proposal['project_title']],
                [
                    'category_id' => $categories[$proposal['category']],
                    'status' => $proposal['status'],
                    'description' => $proposal['description'],
                    'objective' => $proposal['objective'],
                    'attachment' => ['attachments/sample.pdf'],
                    'start_date' => now()->addMonth()->toDateString(),
                    'end_date' => now()->addMonths(6)->toDateString(),
                    'est_budget' => $proposal['est_budget'],
                    'created_by' => $proponent->id,
                    'project_lead' => $proposal['project_lead'],
                ],
            );
        }
    }
}
