<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existe el admin
        $adminExists = DB::table('users')->where('email', 'admin@hakipadel.com')->exists();

        if (!$adminExists) {
            DB::table('users')->insert([
                'name' => 'Administrador Hakipadel',
                'email' => 'admin@hakipadel.com',
                'role' => 'admin',
                'password' => Hash::make('Hakipadel2025!'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "✅ Usuario administrador creado exitosamente\n";
            echo "📧 Email: admin@hakipadel.com\n";
            echo "🔑 Password: Hakipadel2025!\n";
        } else {
            echo "⚠️  El usuario administrador ya existe\n";
        }
    }
}
