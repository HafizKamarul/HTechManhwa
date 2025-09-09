<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class VersionInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:version {--format=table : Output format (table|json|simple)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display application version information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $versionFile = base_path('VERSION.json');
        
        if (!File::exists($versionFile)) {
            $this->error('VERSION.json file not found');
            return 1;
        }

        $versionData = json_decode(File::get($versionFile), true);
        $format = $this->option('format');

        switch ($format) {
            case 'json':
                $this->line(json_encode($versionData, JSON_PRETTY_PRINT));
                break;
                
            case 'simple':
                $this->line("v{$versionData['version']} ({$versionData['status']})");
                break;
                
            case 'table':
            default:
                $this->info('HTech Manhwa - Version Information');
                $this->line('');
                
                $this->table(
                    ['Property', 'Value'],
                    [
                        ['Version', $versionData['version']],
                        ['Codename', $versionData['codename']],
                        ['Release Date', $versionData['release_date']],
                        ['Status', $this->getStatusBadge($versionData['status'])],
                        ['Description', $versionData['description']],
                    ]
                );
                
                $this->line('');
                $this->info('Features:');
                foreach ($versionData['features'] as $feature) {
                    $this->line("  • {$feature}");
                }
                
                $this->line('');
                $this->comment("For detailed changelog, run: cat CHANGELOG.md");
                $this->comment("For rollback guide, run: cat ROLLBACK_GUIDE.md");
                break;
        }

        return 0;
    }

    private function getStatusBadge($status)
    {
        return match($status) {
            'stable' => '<fg=green>●</> Stable',
            'beta' => '<fg=yellow>●</> Beta',
            'alpha' => '<fg=red>●</> Alpha',
            'deprecated' => '<fg=gray>●</> Deprecated',
            default => $status
        };
    }
}
