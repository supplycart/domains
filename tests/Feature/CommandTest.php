<?php

namespace Supplycart\Domains\Tests\Feature;

use Illuminate\Support\Facades\File;
use Supplycart\Domains\Tests\TestCase;

class CommandTest extends TestCase
{
    public function test_can_populate_domain()
    {
        $this->withoutExceptionHandling();

        $domainPath = app_path('Domains' . DIRECTORY_SEPARATOR . 'Test');
        $modelPath = $domainPath . DIRECTORY_SEPARATOR . 'Models';
        $domainModelPath = $domainPath . DIRECTORY_SEPARATOR . 'Test.php';

        $this->artisan('make:domain', ['name' => 'test']);

        $this->assertTrue(File::exists($domainPath));
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
}
