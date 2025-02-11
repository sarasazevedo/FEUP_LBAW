<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $path = base_path('sql/db.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
        $this->command->info('Database seeded!');

        $populatePath = base_path('sql/populate.sql');
        $populateSql = file_get_contents($populatePath);
        DB::unprepared($populateSql);
        $this->command->info('Database populated with additional data!');
    }
}
