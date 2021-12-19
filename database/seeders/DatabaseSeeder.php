<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
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
         User::factory()->count(1)->create([
             'login' => 'admin',
             'access_level' => 3
         ]);
        User::factory()->count(50)->create();

        $this->call([
            CompanySeeder::class,
        ]);
    }
}
