<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SUPERADMIN_EMAIL');
        $password = env('SUPERADMIN_PASSWORD');

        if (empty($email) || empty($password)) {
            throw new RuntimeException(
                'Set SUPERADMIN_EMAIL and SUPERADMIN_PASSWORD in .env before seeding.'
            );
        }

        DB::statement(
            'INSERT IGNORE INTO companies (name, created_at, updated_at)
             VALUES (?, NOW(), NOW())',
            ['Default Company']
        );

        $companyId = DB::table('companies')
            ->where('name', 'Default Company')
            ->value('id');

        if (!$companyId) {
            throw new RuntimeException('Default Company could not be created.');
        }

        DB::statement(
            'INSERT IGNORE INTO users
             (name, email, password, company_id, role, is_active, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())',
            [
                'Super Admin',
                $email,
                Hash::make($password),
                $companyId,
                'SuperAdmin',
                true,
            ]
        );
    }
}