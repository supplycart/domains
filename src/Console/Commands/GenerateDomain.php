<?php

namespace Supplycart\Domains\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateDomain extends Command
{
    /**
     * Domain Name.
     *
     * @var
     */
    public $name;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain {name : Domain Name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a domain folder structure';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->name = ucfirst($this->argument('name'));

        $this->generateDirectories();

        return 1;
    }

    public function generateDirectories()
    {
        if (!File::exists(app_path('Domains'))) {
            File::makeDirectory($this->domainPath, 0777, true);
        }

        File::makeDirectory(app_path('Domains' . DIRECTORY_SEPARATOR . $this->name, 0777, true));

        File::makeDirectory(app_path('Domains' . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . 'Models'));

        File::makeDirectory(app_path('Domains' . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . 'Http'));

        File::makeDirectory(app_path('Domains' . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . 'Listeners'));

        File::makeDirectory(app_path('Domains' . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . 'Contracts'));

        File::makeDirectory(app_path('Domains' . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . 'Events'));
    }
}
