<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        Client::factory(10)
            ->recycle($this->adminUser())
            ->create()
            ->each(function (Client $client) {
                Project::factory()
                    ->recycle($client)
                    ->recycle($this->adminUser())
                    ->has(Task::factory(rand(2, 3)), 'tasks')
                    ->create();
            });
    }

    protected function adminUser(): ?User
    {
        return User::where('email', 'admin@example.com')->first();
    }
}
