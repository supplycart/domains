<?php

declare(strict_types=1);

namespace Supplycart\Domains\Tests\Feature;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Supplycart\Domains\Tests\TestCase;

final class CommandTest extends TestCase
{
    private string $domainPath;

    public function test_can_populate_domain(): void
    {
        $modelPath = $this->domainPath.DIRECTORY_SEPARATOR.'Models';
        $domainModelPath = $this->domainPath.DIRECTORY_SEPARATOR.'Test.php';

        $this->assertSame(Command::SUCCESS, Artisan::call('make:domain', ['name' => 'test']));

        $this->assertDirectoryExists($this->domainPath);
        $this->assertDirectoryExists($modelPath);
        $this->assertFileExists($domainModelPath);

        $expectedContents = <<<CLASS
        <?php

        declare(strict_types=1);

        namespace App\Domains\Test;

        use Supplycart\Domains\Domain;

        final class Test extends Domain
        {
            public static function registerRoutes(): void
            {
                require __DIR__.'/Http/routes.php';
            }
        }
        CLASS;

        $this->assertSame($expectedContents.PHP_EOL, file_get_contents($domainModelPath));
        $this->assertStringContainsString("Route::get('/test'", (string) file_get_contents($this->domainPath.'/Http/routes.php'));
        $this->assertStringContainsString('#[UsePolicy(TestPolicy::class)]', (string) file_get_contents($this->domainPath.'/Models/Test.php'));
    }

    public function test_can_populate_queues_path(): void
    {
        $domainListenersDirectoryPath = $this->domainPath.DIRECTORY_SEPARATOR.'Listeners';
        $domainListenersPath = $this->domainPath.DIRECTORY_SEPARATOR.'Listeners'.DIRECTORY_SEPARATOR.'TestListener.php';
        $domainEventsDirectoryPath = $this->domainPath.DIRECTORY_SEPARATOR.'Events';
        $domainEventsPath = $this->domainPath.DIRECTORY_SEPARATOR.'Events'.DIRECTORY_SEPARATOR.'TestEvent.php';
        $domainJobsDirectoryPath = $this->domainPath.DIRECTORY_SEPARATOR.'Jobs';
        $domainJobsPath = $this->domainPath.DIRECTORY_SEPARATOR.'Jobs'.DIRECTORY_SEPARATOR.'TestJob.php';

        $this->assertSame(Command::SUCCESS, Artisan::call('make:domain', ['name' => 'test', '--queues' => true]));

        $this->assertTrue(File::exists($this->domainPath));
        $this->assertTrue(File::isDirectory($domainListenersDirectoryPath));
        $this->assertTrue(File::isDirectory($domainEventsDirectoryPath));
        $this->assertTrue(File::isDirectory($domainJobsDirectoryPath));
        $this->assertTrue(File::exists($domainListenersPath));
        $this->assertTrue(File::exists($domainEventsPath));
        $this->assertTrue(File::exists($domainJobsPath));
        $this->assertStringContainsString('handle(TestEvent $event): void', (string) file_get_contents($domainListenersPath));
    }

    public function test_existing_domain_returns_failure(): void
    {
        File::makeDirectory($this->domainPath, 0755, true);

        $this->assertSame(Command::FAILURE, Artisan::call('make:domain', ['name' => 'test']));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->domainPath = app_path('Domains'.DIRECTORY_SEPARATOR.'Test');
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(app_path('Domains'));

        parent::tearDown();
    }
}
