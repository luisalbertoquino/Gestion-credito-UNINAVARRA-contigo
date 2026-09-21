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
            ['name' => 'Financiera UNINAVARRA', 'password' => bcrypt('UninavarraCredito2026'), 'role' => 'financiera']
        );

        User::query()->firstOrCreate(
            ['email' => 'administrador@uninavarra.edu.co'],
            ['name' => 'Administrador UNINAVARRA', 'password' => bcrypt('UninavarraAdmin2026'), 'role' => 'admin']
        );

        $this->call([
            ProgramaSeeder::class,
            ParametroSeeder::class,
            DocumentoRequeridoSeeder::class,
        ]);
    }
}
