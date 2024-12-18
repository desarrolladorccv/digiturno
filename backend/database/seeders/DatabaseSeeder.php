<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            AttentionProfileSeeder::class,
            ClientTypeSeeder::class,
            RolePermissionSeeder::class,
            RoomSeeder::class,
            ModuleTypeSeeder::class,
            ModuleSeeder::class,
            ShiftSeeder::class,
            AbsenceReasonSeeder::class,
        ]);
    }
}
