<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductionDataSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@softwaretech.com',
                'password' => 'superadmin123',
                'role' => 'superadmin',
                'active' => 1,
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@softwaretech.com',
                'password' => 'admin123',
                'role' => 'admin',
                'active' => 1,
            ],
            [
                'name' => 'Cliente',
                'email' => 'cliente@softwaretech.com',
                'password' => 'cliente123',
                'role' => 'cliente',
                'active' => 1,
            ],
            [
                'name' => 'Empleado 1',
                'email' => 'empleado1@softwaretech.com',
                'password' => 'empleado123',
                'role' => 'empleado',
                'active' => 1,
            ],
            [
                'name' => 'Empleado 2',
                'email' => 'empleado2@softwaretech.com',
                'password' => 'empleado123',
                'role' => 'empleado',
                'active' => 1,
            ],
            [
                'name' => 'Empleado 3',
                'email' => 'empleado3@softwaretech.com',
                'password' => 'empleado123',
                'role' => 'empleado',
                'active' => 1,
            ],
            [
                'name' => 'Software Technologies',
                'email' => 'stechnologies@softwaretech.com',
                'password' => 'softwaretechnologies123',
                'role' => 'admin',
                'active' => 1,
            ]
        ];

        $supabaseUrl = env('SUPABASE_URL');
        $serviceKey = env('SUPABASE_SERVICE_KEY');

        foreach ($users as $userData) {
            DB::table('users')->updateOrInsert(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role'],
                    'active' => $userData['active'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            if (!empty($supabaseUrl) && !empty($serviceKey)) {
                try {
                    Http::timeout(5)
                        ->withoutVerifying()
                        ->withHeaders([
                            'apikey' => $serviceKey,
                            'Authorization' => 'Bearer ' . $serviceKey,
                            'Content-Type' => 'application/json',
                        ])->post("{$supabaseUrl}/auth/v1/admin/users", [
                                'email' => $userData['email'],
                                'password' => $userData['password'],
                                'email_confirm' => true,
                                'user_metadata' => [
                                    'name' => $userData['name'],
                                    'role' => $userData['role']
                                ]
                            ]);
                } catch (\Exception $e) {
                    $this->command->info("Aviso: No se pudo sincronizar el usuario {$userData['email']} con Auth (posible red local/offline), pero se guardó correctamente en la BD.");
                }
            }
        }
    }
}
