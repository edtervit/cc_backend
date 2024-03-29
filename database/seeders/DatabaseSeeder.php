<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MessagesSeeder::class);
        $this->call(ColourSchemesSeeder::class);
        $this->call(SubjectsSeeder::class);
        $this->call(ImageTopicsSeeder::class);
        $this->call(ImageSeeder::class);
    }
}
