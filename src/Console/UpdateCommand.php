<?php

namespace LaravelDaily\Invoices\Console;

use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Invoices Views and Translations';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('This will overwrite default templates and translations.');

        if ($this->confirm('Do you wish to continue?')) {
            $this->comment('Updating Invoices Views...');
            $this->callSilent('vendor:publish', ['--tag' => 'invoices.views', '--force' => true]);

            $this->comment('Updating Invoices Translations...');
            $this->callSilent('vendor:publish', ['--tag' => 'invoices.translations', '--force' => true]);

            $this->info('Invoices Views and Translations updated successfully.');
        }
    }
}
