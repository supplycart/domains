<?php

namespace Supplycart\Domains\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDomain extends Command
{
    /**
     * Domain Name.
     *
     * @var
     */
    public $name;

    /**
     * Namespace.
     *
     * @var
     */
    public $namespace;

    /**
     * Domain Path.
     *
     * @var
     */
    public $domainPath;

    /**
     * Model Path.
     *
     * @var
     */
    public $modelPath;

    /**
     * Http Path.
     *
     * @var
     */
    public $httpPath;

    /**
     * Listener Path.
     *
     * @var
     */
    public $listenerPath;

    /**
     * Event Path.
     *
     * @var
     */
    public $eventPath;

    /**
     * Job Path.
     *
     * @var
     */
    public $jobPath;

    /**
     * Contract Path.
     *
     * @var
     */
    public $contractPath;

    /**
     * Policy Path.
     *
     * @var
     */
    public $policyPath;

    /**
     * Controller Path.
     *
     * @var
     */
    public $controllerPath;

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

        $this->namespace = "App\\Domains\\{$this->name}";

        $this->domainPath = app_path('Domains' . DIRECTORY_SEPARATOR . $this->name);

        $this->modelPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Models';

        $this->httpPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Http';

        $this->jobPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Jobs';

        $this->listenerPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Listeners';

        $this->policyPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Policies';

        $this->eventPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Event';

        $this->contractPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Contracts';

        $this->controllerPath = $this->httpPath . DIRECTORY_SEPARATOR . 'Controllers';

        if (File::exists($this->domainPath)) {
            $this->error('Domain already exists!');

            return 0;
        }

        $this->generateDirectories();

        $this->generateFiles();

        $this->info("Domain {$this->name} has been generated successfully!");

        return 1;
    }

    public function generateDirectories()
    {
        File::makeDirectory($this->domainPath, 0777, true);

        File::makeDirectory($this->modelPath, 0777, true);

        File::makeDirectory($this->httpPath, 0777, true);

        File::makeDirectory($this->listenerPath, 0777, true);

        File::makeDirectory($this->contractPath, 0777, true);

        File::makeDirectory($this->eventPath, 0777, true);

        File::makeDirectory($this->jobPath, 0777, true);

        File::makeDirectory($this->httpPath . DIRECTORY_SEPARATOR . 'Controllers', 0777, true);

        File::makeDirectory($this->policyPath, 0777, true);
    }

    public function generateFiles()
    {
        file_put_contents(
            $this->domainPath . DIRECTORY_SEPARATOR . "{$this->name}.php",
            $this->prepareFile(file_get_contents(__DIR__ . '/../../stubs/domain.stub'))
        );

        file_put_contents(
            $this->modelPath . DIRECTORY_SEPARATOR . "{$this->name}.php",
            $this->prepareFile(file_get_contents(__DIR__ . '/../../stubs/model.stub'))
        );

        file_put_contents(
            $this->listenerPath . DIRECTORY_SEPARATOR . "{$this->name}Listener.php",
            $this->prepareFile(file_get_contents(__DIR__ . '/../../stubs/listener.stub'))
        );

        file_put_contents(
            $this->eventPath . DIRECTORY_SEPARATOR . "{$this->name}Event.php",
            $this->prepareFile(file_get_contents(__DIR__ . '/../../stubs/event.stub'))
        );

        file_put_contents(
            $this->jobPath . DIRECTORY_SEPARATOR . "{$this->name}Job.php",
            $this->prepareFile(file_get_contents(__DIR__ . '/../../stubs/job.stub'))
        );

        file_put_contents(
            $this->policyPath . DIRECTORY_SEPARATOR . "{$this->name}Policy.php",
            $this->prepareFile(file_get_contents(__DIR__ . '/../../stubs/policy.stub'))
        );

        file_put_contents(
            $this->httpPath . DIRECTORY_SEPARATOR . "routes.php",
            $this->prepareFile(file_get_contents(__DIR__ . '/../../stubs/routes.stub'))
        );

        file_put_contents(
            $this->httpPath . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . "{$this->name}Controller.php",
            $this->prepareFile(file_get_contents(__DIR__ . '/../../stubs/controller.stub'))
        );
    }

    public function prepareFile($fileContents)
    {
        $replacings = [
            '{{name}}',
            '{{namespace}}',
        ];

        $replacements = [
            $this->name,
            $this->namespace,
        ];

        return str_replace($replacings, $replacements, $fileContents);
    }
}
