<?php

namespace NovaSeoEntity\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\NovaCoreServiceProvider;
use NovaSeoEntity\Tests\Fixtures\NovaServiceProvider;
use Orchestra\Testbench\Database\MigrateProcessor;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('cms-images');

        Artisan::call('nova:publish');
    }

    protected function getPackageProviders($app): array
    {
        return [
            \Inertia\ServiceProvider::class,
            NovaCoreServiceProvider::class,
            NovaServiceProvider::class,
            \SimpleImageManager\ServiceProvider::class,
            \NovaSeoEntity\ServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();

        $migrator = new MigrateProcessor($this, [
            '--path'     => __DIR__ . '/Fixtures/migrations',
            '--realpath' => true,
        ]);
        $migrator->up();
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('filesystems.disks', array_merge(
            $app['config']->get('filesystems.disks'),
            [
                'cms-images' => [
                    'driver'     => 'local',
                    'root'       => storage_path('app/public/cms-images'),
                    'url'        => env('APP_URL') . '/storage/cms-images',
                    'visibility' => 'public',
                ],
            ]
        ));

        // $app['config']->set('nova-seo-entity.some_key', 'some_value');
    }
}
