<?php

declare(strict_types=1);

namespace Supplycart\Domains\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use RuntimeException;

final class MakeDomain extends Command
{
    protected $signature = 'make:domain {name : Domain name} {--queues : Scaffold an event, listener, and queued job}';

    protected $description = 'Create a domain folder structure';

    private string $domainName;

    private string $domainNamespace;

    private string $domainPath;

    public function handle(): int
    {
        $name = $this->argument('name');

        if (! is_string($name) || $name === '') {
            $this->components->error('The domain name may not be empty.');

            return self::FAILURE;
        }

        $this->domainName = Str::studly($name);

        $this->domainNamespace = "App\\Domains\\{$this->domainName}";
        $this->domainPath = app_path('Domains'.DIRECTORY_SEPARATOR.$this->domainName);

        if (is_dir($this->domainPath) || file_exists($this->domainPath)) {
            $this->components->error("Domain [{$this->domainName}] already exists.");

            return self::FAILURE;
        }

        $this->generateDirectories();
        $this->generateFiles();

        $this->components->info("Domain [{$this->domainName}] created successfully.");

        return self::SUCCESS;
    }

    private function generateDirectories(): void
    {
        $directories = [
            $this->domainPath,
            $this->domainPath.'/Http',
            $this->domainPath.'/Http/Controllers',
            $this->domainPath.'/Models',
            $this->domainPath.'/Policies',
        ];

        if ($this->option('queues')) {
            $directories = [
                ...$directories,
                $this->domainPath.'/Events',
                $this->domainPath.'/Jobs',
                $this->domainPath.'/Listeners',
            ];
        }

        foreach ($directories as $directory) {
            if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
                throw new RuntimeException("Unable to create directory [{$directory}].");
            }
        }
    }

    private function generateFiles(): void
    {
        $this->writeStub('domain', $this->domainPath."/{$this->domainName}.php");
        $this->writeStub('model', $this->domainPath."/Models/{$this->domainName}.php");
        $this->writeStub('policy', $this->domainPath."/Policies/{$this->domainName}Policy.php");
        $this->writeStub('routes', $this->domainPath.'/Http/routes.php');
        $this->writeStub('controller', $this->domainPath."/Http/Controllers/{$this->domainName}Controller.php");

        if ($this->option('queues')) {
            $this->writeStub('event', $this->domainPath."/Events/{$this->domainName}Event.php");
            $this->writeStub('listener', $this->domainPath."/Listeners/{$this->domainName}Listener.php");
            $this->writeStub('job', $this->domainPath."/Jobs/{$this->domainName}Job.php");
        }
    }

    private function writeStub(string $stub, string $destination): void
    {
        $contents = file_get_contents(__DIR__."/../../stubs/{$stub}.stub");

        if ($contents === false) {
            throw new RuntimeException("Unable to read the [{$stub}] domain stub.");
        }

        $rendered = str_replace(
            ['{{name}}', '{{namespace}}', '{{route}}', '{{variable}}'],
            [$this->domainName, $this->domainNamespace, Str::kebab($this->domainName), Str::camel($this->domainName)],
            $contents,
        );

        if (file_put_contents($destination, $rendered) === false) {
            throw new RuntimeException("Unable to write generated file [{$destination}].");
        }
    }
}
