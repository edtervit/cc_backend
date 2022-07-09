<?php

namespace Database\Seeders;

use App\Models\ColourSchemes;
use Illuminate\Database\Seeder;

class ColourSchemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ColourSchemes::factory()->count(10)->create();
    }
}
