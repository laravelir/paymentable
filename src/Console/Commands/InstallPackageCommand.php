<?php

namespace Laravelir\Paymentable\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallPackageCommand extends Command
{
    protected $signature = 'paymentable:install';

    protected $description = 'Install the paymentable';

    public function handle()
    {
        $this->line("\t... Welcome To laravelir/paymentable Installer ...");

        //config
        if (File::exists(config_path('paymentable.php'))) {
            $confirm = $this->confirm("paymentable.php already exist. Do you want to overwrite?");
            if ($confirm) {
                $this->publishConfig();
                $this->info("config overwrite finished");
            } else {
                $this->info("skipped config publish");
            }
        } else {
            $this->publishConfig();
            $this->info("config published");
        }
        $this->info("Paymentable Successfully Installed.\n");
        $this->info("\t\tGood Luck.");
    }

    private function publishConfig()
    {
        $this->call('vendor:publish', [
            '--provider' => "Laravelir\\Paymentable\\Providers\\PaymentableServiceProvider",
            '--tag'      => 'paymentable-config',
            '--force'    => true
        ]);
    }
}
