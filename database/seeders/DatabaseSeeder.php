<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'financiera@uninavarra.edu.co'],
            ['name' => 'Financiera UNINAVARRA', 'password' => bcrypt('UninavarraCredito2026')]
        );

        $this->call([
            ProgramaSeeder::class,
            ParametroSeeder::class,
        ]);
    }
}
