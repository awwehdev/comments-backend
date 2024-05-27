<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Sanctum\PersonalAccessToken;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('123'),
        ]);

        PersonalAccessToken::query()->updateOrCreate([
            'token' => hash('sha256', 'PKn5wcfBmhGpbKFHyDQLxSW0sOzfGVjDKzi3ru7l8952ffde'),
        ], [
            'name' => 'access-token',
            'expires_at' => null,
            'abilities' => ['*'],
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
        ]);
    }
}
