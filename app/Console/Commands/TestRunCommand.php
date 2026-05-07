<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestRunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Example: php artisan test:run
     */
    protected $signature = 'test:run';

    /**
     * The console command description.
     */
    protected $description = 'Run a simulated server environment check before project upload';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔍 Checking server environment...");
        sleep(1);

        $this->line("Checking PHP version...");
        $this->line("✅ PHP 8.2 detected");
        sleep(1);

        $this->line("\nChecking installed PHP modules...");
        sleep(1);

        // Fake missing modules count
        $missingModules = 1000;

        $this->warn("⚠️ {$missingModules} PHP modules not found!");
        $this->line("Gathering module list...");

        // Simulated loading bar
        $this->output->progressStart(10);
        foreach (range(1, 10) as $step) {
            usleep(150000);
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();

        $this->newLine(2);
        $this->error("❌ Prottoy Academy Database is showing mysql_sbin.ssl not found.");
        $this->error("⚠️ {$missingModules} PHP modules are missing from this server!");
        $this->line("Please install all missing PHP modules before uploading the Laravel project.");
        $this->line("Run: sudo apt install php-xml php-mbstring php-curl php-gd php-zip php-bcmath php-tokenizer ... etc");

        $this->newLine();
        $this->info("🧰 After installation, re-run this command: php artisan 'upload:Prottoy Academy'");

        return Command::SUCCESS;
    }
}
