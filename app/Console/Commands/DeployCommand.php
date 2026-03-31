<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeployCommand extends Command
{
    protected $signature = 'deploy:production 
                            {--migrate : Run database migrations}
                            {--seed : Run database seeders}
                            {--optimize : Optimize the application}
                            {--cache : Cache config, routes, and views}
                            {--force : Force the deployment without confirmation}';

    protected $description = 'Deploy application to production';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('Are you sure you want to deploy to production?')) {
            $this->warn('Deployment cancelled.');
            return Command::FAILURE;
        }

        $this->info('🚀 Starting production deployment...');
        $this->newLine();

        // Step 1: Maintenance mode
        $this->info('Step 1: Enabling maintenance mode...');
        $this->call('down');
        $this->info('✓ Maintenance mode enabled');
        $this->newLine();

        // Step 2: Clear all caches
        $this->info('Step 2: Clearing caches...');
        $this->call('optimize:clear');
        $this->info('✓ Caches cleared');
        $this->newLine();

        // Step 3: Database migrations
        if ($this->option('migrate')) {
            $this->info('Step 3: Running database migrations...');
            $this->call('migrate', ['--force' => true]);
            $this->info('✓ Migrations completed');
            $this->newLine();
        }

        // Step 4: Database seeders
        if ($this->option('seed')) {
            $this->info('Step 4: Running database seeders...');
            $this->call('db:seed', ['--force' => true]);
            $this->info('✓ Seeders completed');
            $this->newLine();
        }

        // Step 5: Cache optimization
        if ($this->option('cache')) {
            $this->info('Step 5: Caching configuration...');
            $this->call('config:cache');
            $this->info('✓ Config cached');
            $this->newLine();

            $this->info('Step 6: Caching routes...');
            $this->call('route:cache');
            $this->info('✓ Routes cached');
            $this->newLine();

            $this->info('Step 7: Caching views...');
            $this->call('view:cache');
            $this->info('✓ Views cached');
            $this->newLine();

            $this->info('Step 8: Caching events...');
            $this->call('event:cache');
            $this->info('✓ Events cached');
            $this->newLine();
        }

        // Step 6: General optimization
        if ($this->option('optimize')) {
            $this->info('Step 9: Running optimization...');
            $this->call('optimize');
            $this->info('✓ Optimization completed');
            $this->newLine();

            $this->info('Step 10: Warming up cache...');
            $this->call('cache:optimize');
            $this->info('✓ Cache warmed up');
            $this->newLine();
        }

        // Step 7: Storage link
        $this->info('Step 11: Creating storage link...');
        $this->call('storage:link');
        $this->info('✓ Storage link created');
        $this->newLine();

        // Step 8: Disable maintenance mode
        $this->info('Step 12: Disabling maintenance mode...');
        $this->call('up');
        $this->info('✓ Maintenance mode disabled');
        $this->newLine();

        $this->info('✅ Deployment completed successfully!');
        $this->newLine();
        $this->info('Next steps:');
        $this->line('  - Check application health: php artisan about');
        $this->line('  - Monitor logs: tail -f storage/logs/laravel.log');
        $this->line('  - Test critical features in browser');

        return Command::SUCCESS;
    }
}
