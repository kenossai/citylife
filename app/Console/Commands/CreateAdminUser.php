<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user for Filament';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating admin user...');

        // Delete existing admin if exists
        User::where('email', 'admin@citylife.com')->delete();

        // Create admin user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@citylife.com',
            'password' => Hash::make('CityLife2025!'),
            'email_verified_at' => now(),
        ]);

        $this->info('✅ Admin user created successfully!');
        $this->line('Email: admin@citylife.com');
        $this->line('Password: CityLife2025!');
        $this->line('Login at: /admin');

        return Command::SUCCESS;
    }
}
