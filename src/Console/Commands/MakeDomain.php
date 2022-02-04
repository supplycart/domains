<?php

namespace Supplycart\Domains\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDomain extends Command
{
	/**
	 * Domain Name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Namespace.
	 *
	 * @var string
	 */
	public $namespace;

	/**
	 * Domain Path.
	 *
	 * @var string
	 */
	public $domainPath;

	/**
	 * Model Path.
	 *
	 * @var string
	 */
	public $modelPath;

	/**
	 * Http Path.
	 *
	 * @var string
	 */
	public $httpPath;

	/**
	 * Listener Path.
	 *
	 * @var string
	 */
	public $listenerPath;

	/**
	 * Event Path.
	 *
	 * @var string
	 */
	public $eventPath;

	/**
	 * Job Path.
	 *
	 * @var string
	 */
	public $jobPath;

	/**
	 * Contract Path.
	 *
	 * @var string
	 */
	public $contractPath;

	/**
	 * Policy Path.
	 *
	 * @var string
	 */
	public $policyPath;

	/**
	 * @var string
	 */
	public $controllerPath;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'make:domain {name : Domain Name} {--queues : Whether to scaffold Job, Events and Listeners}';

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
	 *
	 */
	public function handle(): int
	{
		$this->name = ucfirst($this->argument('name'));

		$this->namespace = "App\\Domains\\{$this->name}";

		$this->domainPath = app_path('Domains' . DIRECTORY_SEPARATOR . $this->name);

		if (File::exists($this->domainPath)) {
			$this->error('Domain already exists!');

			return 0;
		}

		$this->modelPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Models';

		$this->httpPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Http';

		$this->jobPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Jobs';

		$this->listenerPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Listeners';

		$this->policyPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Policies';

		$this->eventPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Events';

		$this->contractPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Contracts';

		$this->controllerPath = $this->httpPath . DIRECTORY_SEPARATOR . 'Controllers';

		$this->generateDirectories();

		$this->generateFiles();

		$this->info("Domain {$this->name} has been generated successfully!");

		return 1;
	}

	public function generateDirectories(): void
	{
		File::makeDirectory($this->domainPath, 0777, true);

		File::makeDirectory($this->modelPath, 0777, true);

		File::makeDirectory($this->httpPath, 0777, true);

		File::makeDirectory($this->contractPath, 0777, true);

		File::makeDirectory($this->httpPath . DIRECTORY_SEPARATOR . 'Controllers', 0777, true);

		File::makeDirectory($this->policyPath, 0777, true);

		if ($this->option('queues')) {
			$this->generateQueuesDirectoryFiles();
		}
	}

	protected function generateQueuesDirectoryFiles(): void
	{
		File::makeDirectory($this->listenerPath, 0777, true);

		File::makeDirectory($this->eventPath, 0777, true);

		File::makeDirectory($this->jobPath, 0777, true);

		$this->replaceFileContents($this->listenerPath . DIRECTORY_SEPARATOR . "{$this->name}Listener.php", file_get_contents(__DIR__ . '/../../stubs/listener.stub'));

		$this->replaceFileContents($this->eventPath . DIRECTORY_SEPARATOR . "{$this->name}Event.php", file_get_contents(__DIR__ . '/../../stubs/event.stub'));

		$this->replaceFileContents($this->jobPath . DIRECTORY_SEPARATOR . "{$this->name}Job.php", file_get_contents(__DIR__ . '/../../stubs/job.stub'));
	}

	protected function replaceFileContents($path, $fileContents): void
	{
		file_put_contents($path, $this->prepareFile($fileContents));
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

	public function generateFiles(): void
	{
		$this->replaceFileContents($this->domainPath . DIRECTORY_SEPARATOR . "{$this->name}.php", file_get_contents(__DIR__ . '/../../stubs/domain.stub'));

		$this->replaceFileContents($this->modelPath . DIRECTORY_SEPARATOR . "{$this->name}.php", file_get_contents(__DIR__ . '/../../stubs/model.stub'));

		$this->replaceFileContents($this->policyPath . DIRECTORY_SEPARATOR . "{$this->name}Policy.php", file_get_contents(__DIR__ . '/../../stubs/policy.stub'));

		$this->replaceFileContents($this->httpPath . DIRECTORY_SEPARATOR . "routes.php", file_get_contents(__DIR__ . '/../../stubs/routes.stub'));

		$this->replaceFileContents(
			$this->httpPath . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . "{$this->name}Controller.php",
			file_get_contents(__DIR__ . '/../../stubs/controller.stub')
		);
	}
}
