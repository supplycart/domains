<?php

namespace Supplycart\Domains\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Supplycart\Domains\Tests\TestCase;

class CommandTest extends TestCase
{
	public ?string $domainPath;

	protected function setUp(): void
	{
		parent::setUp();

		$this->domainPath = app_path('Domains' . DIRECTORY_SEPARATOR . 'Test');
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		File::deleteDirectory(app_path('Domains'));
	}

	public function test_can_populate_domain(): void
    {
        $modelPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Models';
        $domainModelPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Test.php';

        $this->artisan('make:domain', ['name' => 'test', '--queues']);

        $this->assertTrue(File::exists($this->domainPath));
        $this->assertTrue(File::exists($modelPath));
        $this->assertTrue(File::exists($domainModelPath));

        $expectedContents = <<<CLASS
        <?php

        namespace App\Domains\Test;

        use Supplycart\Domains\Domain;

        class Test extends Domain
        {
            //
        }
        CLASS;

        $this->assertEquals($expectedContents, file_get_contents($domainModelPath));
    }

	public function test_can_populate_queues_path(): void
	{
		$domainListenersDirectoryPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Listeners';
		$domainListenersPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Listeners' . DIRECTORY_SEPARATOR . 'TestListener.php';
		$domainEventsDirectoryPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Events';
		$domainEventsPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Events' . DIRECTORY_SEPARATOR . 'TestEvent.php';
		$domainJobsDirectoryPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Jobs';
		$domainJobsPath = $this->domainPath . DIRECTORY_SEPARATOR . 'Jobs' . DIRECTORY_SEPARATOR . 'TestJob.php';

		Artisan::call('make:domain test --queues');

		$this->assertTrue(File::exists($this->domainPath));
		$this->assertTrue(File::isDirectory($domainListenersDirectoryPath));
		$this->assertTrue(File::isDirectory($domainEventsDirectoryPath));
		$this->assertTrue(File::isDirectory($domainJobsDirectoryPath));
		$this->assertTrue(File::exists($domainListenersPath));
		$this->assertTrue(File::exists($domainEventsPath));
		$this->assertTrue(File::exists($domainJobsPath));
	}
}
