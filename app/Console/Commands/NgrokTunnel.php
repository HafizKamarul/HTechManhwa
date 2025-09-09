<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class NgrokTunnel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ngrok:tunnel 
                           {--port=8000 : The port to tunnel}
                           {--region=us : The ngrok region}
                           {--subdomain= : Custom subdomain (requires paid plan)}
                           {--status : Check tunnel status}
                           {--stop : Stop all tunnels}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage ngrok tunnels for HTech Manhwa platform';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('status')) {
            return $this->checkStatus();
        }

        if ($this->option('stop')) {
            return $this->stopTunnels();
        }

        return $this->startTunnel();
    }

    /**
     * Start ngrok tunnel
     */
    private function startTunnel()
    {
        $port = $this->option('port');
        $region = $this->option('region');
        $subdomain = $this->option('subdomain');

        $this->info("🚀 Starting ngrok tunnel for HTech Manhwa...");
        $this->line("📍 Port: {$port}");
        $this->line("🌍 Region: {$region}");

        // Build ngrok command
        $command = ['node', 'scripts/ngrok-tunnel.js'];
        
        if ($subdomain) {
            $this->line("🏷️  Subdomain: {$subdomain}");
        }

        $this->info("🔗 Starting tunnel...");
        
        // Check if Laravel server is running
        $this->checkLaravelServer($port);

        // Start the tunnel using Node.js script
        $process = new Process($command);
        $process->setTimeout(null);
        
        try {
            $this->line("📱 Starting tunnel process...");
            
            // Run the process and capture output
            $process->run(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    $this->error($buffer);
                } else {
                    $this->line($buffer);
                }
            });
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to start tunnel: " . $e->getMessage());
            
            if (strpos($e->getMessage(), 'ngrok') !== false) {
                $this->newLine();
                $this->warn("💡 Make sure ngrok is installed:");
                $this->line("   npm install ngrok --save-dev");
                $this->line("   OR install globally: npm install -g ngrok");
            }
            
            return 1;
        }
        
        return 0;
    }

    /**
     * Check if Laravel development server is running
     */
    private function checkLaravelServer($port)
    {
        $this->line("🔍 Checking if Laravel server is running on port {$port}...");
        
        $connection = @fsockopen('localhost', $port, $errno, $errstr, 1);
        
        if (!$connection) {
            $this->warn("⚠️  Laravel server not detected on port {$port}");
            $this->line("💡 Start the server first: php artisan serve --host=0.0.0.0 --port={$port}");
            $this->newLine();
            
            if ($this->confirm('Would you like to start the Laravel server now?', true)) {
                $this->info("🚀 Starting Laravel development server...");
                
                // Start Laravel server in background
                $serverCommand = "php artisan serve --host=0.0.0.0 --port={$port}";
                $this->line("Running: {$serverCommand}");
                
                // Note: This would typically be run in background, but for now just show the command
                $this->info("Please run this command in another terminal:");
                $this->line($serverCommand);
                $this->newLine();
            }
        } else {
            $this->info("✅ Laravel server is running on port {$port}");
            fclose($connection);
        }
    }

    /**
     * Check tunnel status
     */
    private function checkStatus()
    {
        $this->info("📊 Checking ngrok tunnel status...");
        
        // Use Node.js script to check status
        $command = ['node', 'scripts/ngrok-setup.js', 'status'];
        $process = new Process($command);
        
        try {
            $process->mustRun();
            $this->line($process->getOutput());
        } catch (\Exception $e) {
            $this->error("❌ Could not check status: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }

    /**
     * Stop all tunnels
     */
    private function stopTunnels()
    {
        $this->info("🛑 Stopping all ngrok tunnels...");
        
        $command = ['node', 'scripts/ngrok-setup.js', 'stop'];
        $process = new Process($command);
        
        try {
            $process->mustRun();
            $this->info("✅ All tunnels stopped!");
        } catch (\Exception $e) {
            $this->error("❌ Could not stop tunnels: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
