<?php

namespace LaravelDaily\Invoices\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Invoices resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Invoices Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'invoices.assets']);

        $this->comment('Publishing Invoices Views...');
        $this->callSilent('vendor:publish', ['--tag' => 'invoices.views']);

        $this->comment('Publishing Invoices Translations...');
        $this->callSilent('vendor:publish', ['--tag' => 'invoices.translations']);

        $this->comment('Publishing Invoices Config...');
        $this->callSilent('vendor:publish', ['--tag' => 'invoices.config']);

        $this->info('Invoices scaffolding installed successfully.');
    }
}
