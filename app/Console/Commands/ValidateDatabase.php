<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ValidateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate database connection and configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Validating Database Configuration...');
        $this->newLine();

        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        // Check Database Configuration
        $this->line('1. Checking Database Configuration...');
        $this->line('   Connection: ' . $connection);
        $this->line('   Host: ' . ($config['host'] ?? 'not set'));
        $this->line('   Port: ' . ($config['port'] ?? 'not set'));
        $this->line('   Database: ' . ($config['database'] ?? 'not set'));
        $this->line('   Username: ' . ($config['username'] ?? 'not set'));
        $this->line('   Password: ' . (!empty($config['password']) ? '***' : 'not set'));
        $this->newLine();

        // Check if required values are set
        $this->line('2. Validating Required Settings...');
        $errors = [];

        if (empty($config['host'])) {
            $this->error('   ❌ DB_HOST is not set in .env file');
            $errors[] = 'DB_HOST';
        } else {
            $this->info('   ✓ DB_HOST is set');
        }

        if (empty($config['port'])) {
            $this->error('   ❌ DB_PORT is not set in .env file');
            $errors[] = 'DB_PORT';
        } else {
            $this->info('   ✓ DB_PORT is set');
        }

        if (empty($config['database'])) {
            $this->error('   ❌ DB_DATABASE is not set in .env file');
            $errors[] = 'DB_DATABASE';
        } else {
            $this->info('   ✓ DB_DATABASE is set');
        }

        if (empty($config['username'])) {
            $this->error('   ❌ DB_USERNAME is not set in .env file');
            $errors[] = 'DB_USERNAME';
        } else {
            $this->info('   ✓ DB_USERNAME is set');
        }

        $this->newLine();

        if (!empty($errors)) {
            $this->error('❌ Configuration is incomplete. Please set the following in .env:');
            foreach ($errors as $error) {
                $this->line('   - ' . $error);
            }
            return 1;
        }

        // Test Database Connection
        $this->line('3. Testing Database Connection...');
        try {
            DB::connection()->getPdo();
            $this->info('   ✓ Database connection successful!');
            
            // Try to query the database
            $version = DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown';
            $this->line('   MySQL Version: ' . $version);
            
            // Check if users table exists
            try {
                $tableExists = DB::select("SHOW TABLES LIKE 'users'");
                if (!empty($tableExists)) {
                    $userCount = DB::table('users')->count();
                    $this->info('   ✓ Users table exists (' . $userCount . ' users)');
                } else {
                    $this->warn('   ⚠ Users table does not exist. Run: php artisan migrate');
                }
            } catch (\Exception $e) {
                $this->warn('   ⚠ Could not check users table: ' . $e->getMessage());
            }
            
        } catch (\PDOException $e) {
            $this->error('   ❌ Database connection failed!');
            $this->newLine();
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            
            // Provide troubleshooting tips
            $this->line('=== Troubleshooting ===');
            $this->newLine();
            
            if (str_contains($e->getMessage(), 'No connection could be made') || 
                str_contains($e->getMessage(), 'Connection refused')) {
                $this->line('1. Check if MySQL service is running:');
                $this->line('   - Windows: Open Services and start "MySQL" service');
                $this->line('   - Windows (XAMPP/WAMP): Start MySQL from control panel');
                $this->line('   - Linux: sudo systemctl start mysql');
                $this->line('   - Mac: brew services start mysql');
                $this->newLine();
            }
            
            if (str_contains($e->getMessage(), 'Access denied')) {
                $this->line('2. Check database credentials:');
                $this->line('   - Verify DB_USERNAME and DB_PASSWORD in .env');
                $this->line('   - Make sure the user has access to the database');
                $this->newLine();
            }
            
            if (str_contains($e->getMessage(), "Unknown database")) {
                $this->line('3. Create the database:');
                $this->line('   mysql -u ' . $config['username'] . ' -p');
                $this->line('   CREATE DATABASE ' . $config['database'] . ';');
                $this->newLine();
            }
            
            $this->line('4. Verify .env configuration:');
            $this->line('   DB_CONNECTION=' . $connection);
            $this->line('   DB_HOST=' . $config['host']);
            $this->line('   DB_PORT=' . $config['port']);
            $this->line('   DB_DATABASE=' . $config['database']);
            $this->line('   DB_USERNAME=' . $config['username']);
            $this->line('   DB_PASSWORD=***');
            $this->newLine();
            
            $this->line('5. Clear configuration cache:');
            $this->line('   php artisan config:clear');
            $this->newLine();
            
            return 1;
        } catch (\Exception $e) {
            $this->error('   ❌ Unexpected error: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info('=== Summary ===');
        $this->info('✓ Database configuration is valid and connection is working!');
        $this->newLine();
        
        return 0;
    }
}

