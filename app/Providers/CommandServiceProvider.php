<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\ImportCommand;

class CommandServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.sync-data', function()
        {
            return new ImportCommand;
        });

        $this->commands(
            'command.sync-data'
        );
    }
}
