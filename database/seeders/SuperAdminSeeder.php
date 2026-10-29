<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement(
            'INSERT IGNORE INTO companies (name, created_at, updated_at)
             VALUES (?, NOW(), NOW())',
            ['Default Company']
        );

        $companyId = DB::table('companies')
            ->where('name', 'Default Company')
            ->value('id');

        $email = env('SUPERADMIN_EMAIL', 'superadmin@example.com');
        $password = Hash::make(
            env('SUPERADMIN_PASSWORD', 'ChangeMe_2026!')
        );

        DB::statement(
            'INSERT IGNORE INTO users
             (name, email, password, company_id, role, is_active, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())',
            [
                'Super Admin',
                $email,
                $password,
                $companyId,
                'SuperAdmin',
                true,
            ]
        );
    }
}