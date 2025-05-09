<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;

class ModuleEnableAll extends Command
{
    protected $signature = 'module:enable-all';
    protected $description = 'Enable all disabled modules';

    public function handle()
    {
        $modules = Module::all();

        foreach ($modules as $module) {
            if (! $module->isEnabled()) {
                $module->enable();
                $this->info("Enabled: " . $module->getName());
            }
        }

        $this->info('All modules have been enabled.');
    }
}