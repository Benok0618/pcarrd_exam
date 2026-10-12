<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AccountSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProposalSeeder::class,
            TimelineSeeder::class,
        ]);
    }
}
