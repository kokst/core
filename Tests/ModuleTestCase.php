<?php

namespace Kokst\Core\Tests;

use Kokst\Core\Http\User;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\View;

abstract class ModuleTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/Factories');

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__.'/../Database/Migrations'),
        ]);

        View::addLocation(__DIR__. '/../Resources/views');
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->app = $app;

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('logging.default', 'single');
        $app['config']->set('logging.channels.single.path', __DIR__ . '/logs/laravel.log');

        $config = $app->make('config');

        $config->set(['auth.providers.users.model' => User::class]);

        $kernel = $app->make('Illuminate\Contracts\Http\Kernel');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Collective\Html\HtmlServiceProvider::class,
            \Kokst\Core\Providers\CoreServiceProvider::class,
            \Kokst\Core\Providers\RouteServiceProvider::class,
            \Laravolt\Avatar\ServiceProvider::class,
            \Lavary\Menu\ServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Avatar' => \Laravolt\Avatar\Facade::class,
            'Form' => \Collective\Html\FormFacade::class,
            'Menu' => \Lavary\Menu\Facade::class,
        ];
    }
}
