<?php

namespace Kokst\Core\Console\Commands;

use function Safe\file_get_contents;
use function Safe\file_put_contents;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeModuleCommand extends Command
{
    protected $signature = 'kok:make-module {name*} {--R|resource} {--B|basic}';

    protected $description = 'Create a new module.';

    protected $files;

    protected $module;

    protected $moduleDashcase;

    protected $moduleSnakecase;

    protected $moduleLowercase;

    protected $modulePlural;

    protected $modulePluralLowercase;

    protected $modulePluralSnakecase;

    protected $modulePath;

    protected $modulesPath;

    protected $basic;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;

        $this->modulesPath = base_path("Modules");
    }

    protected function getStub($type)
    {
        return file_get_contents(base_path('vendor') . "/kokst/core/Resources/stubs/$type.stub");
    }

    protected function replaceContent($stub)
    {
        $content = str_replace(
            ['{{ Module }}', '{{ ModuleDash }}', '{{ ModuleLower }}', '{{ ModulePlural }}', '{{ ModulePluralLower }}', '{{ ModulePluralSnake }}', '{{ ModuleSnake }}', '{{ Basic }}'],
            [$this->module, $this->moduleDashcase, $this->moduleLowercase, $this->modulePlural, $this->modulePluralLowercase, $this->modulePluralSnakecase, $this->moduleSnakecase, $this->basic],
            $this->getStub($stub)
        );

        return $content;
    }

    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
        return $path;
    }

    public function handle()
    {
        $names = (array) $this->argument('name');
        $resource = $this->option('resource');
        $basic = $this->option('basic');

        if (!$resource && $basic) {
            $this->warn('Warning: Ignoring Basic option (-B / --basic); only available for Resource modules (-R / --resource)');
        }

        $this->basic = $basic ? 'true' : 'false';

        $bar = $this->output->createProgressBar(count($names));
        $bar->start();

        foreach ($names as $name) {
            $this->module = $name;
            $this->moduleDashcase = str_replace('_', '-', snake_case($name));
            $this->moduleSnakecase = snake_case($name);
            $this->moduleLowercase = strtolower($name);
            $this->modulePlural = str_plural($name);
            $this->modulePluralLowercase = strtolower(str_plural($name));
            $this->modulePluralSnakecase = snake_case(str_plural($name));
            $this->modulePath = $this->modulesPath . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;

            if (! $this->files->isDirectory($this->modulePath)) {
                $this->makeDirectory($this->modulePath . DIRECTORY_SEPARATOR . "dir");

                $this->createConfig();
                $this->createControllers();
                $this->createMenuMiddleware();
                $this->createProviders();
                $this->createTranslations();
                $this->createViews();
                $this->createRoutes();
                $this->createFeatureTests();
                $this->createModuleFile();

                if ($resource) {
                    $this->createFactories();
                    $this->createMigrations();
                    $this->createSeeders();
                    $this->createEntities();
                    $this->createResourceControllers();
                    $this->createObservers();
                    $this->createPresenters();
                    $this->createResourceProviders();
                    $this->createResourceTranslations();
                    $this->createResourceViews();
                    $this->createResourceRoutes();
                    $this->createResourceFeatureTests();
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\n");
    }

    protected function createConfig()
    {
        $path = $this->modulePath . "Config";

        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        $content = str_replace(
            ['{{ Module }}'],
            [$this->module],
            $this->getStub('config')
        );

        file_put_contents("$path/config.php", $content);
    }

    protected function createControllers()
    {
        $path = $this->modulePath . "Http" . DIRECTORY_SEPARATOR . "Controllers";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('controller'));
    }

    protected function createMenuMiddleware()
    {
        $path = $this->modulePath . "Http" . DIRECTORY_SEPARATOR . "Middleware";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/DefineMenus.php", $this->replaceContent('middleware-definemenus'));
    }

    protected function createProviders()
    {
        $path = $this->modulePath . "Providers";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}ServiceProvider.php", $this->replaceContent('service-provider-module'));
        file_put_contents("$path/RouteServiceProvider.php", $this->replaceContent('service-provider-route'));
    }

    protected function createTranslations()
    {
        $path = $this->modulePath . "Resources" . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . "en";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/index.php", $this->replaceContent('lang-en-index'));
    }

    protected function createViews()
    {
        $path = $this->modulePath . "Resources" . DIRECTORY_SEPARATOR . "views";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/index.blade.php", $this->replaceContent('views-index'));
    }

    protected function createRoutes()
    {
        $path = $this->modulePath . "Routes";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/web.php", $this->replaceContent('routes-web'));
        file_put_contents("$path/api.php", $this->replaceContent('routes-api'));
    }

    protected function createFeatureTests()
    {
        $path = $this->modulePath . "Tests" . DIRECTORY_SEPARATOR . "Feature";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/IndexTest.php", $this->replaceContent('tests-feature-index'));
    }

    protected function createModuleFile()
    {
        $path = $this->modulePath;

        file_put_contents("$path/module.json", $this->replaceContent('module'));
    }

    protected function createFactories()
    {
        $path = $this->modulePath . "Database" . DIRECTORY_SEPARATOR . "factories";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}Factory.php", $this->replaceContent('factory'));
    }

    protected function createMigrations()
    {
        $path = $this->modulePath . "Database" . DIRECTORY_SEPARATOR . "Migrations";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        $filename = date('Y_m_d_His') . "_create_{$this->modulePluralSnakecase}_table.php" ;

        file_put_contents("$path/$filename", $this->replaceContent('migration-create'));
    }

    protected function createSeeders()
    {
        $path = $this->modulePath . "Database" . DIRECTORY_SEPARATOR . "Seeders";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}TableSeeder.php", $this->replaceContent('seeder-table'));
    }

    protected function createEntities()
    {
        $path = $this->modulePath . "Entities";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}.php", $this->replaceContent('entity'));
    }

    protected function createResourceControllers()
    {
        $path = $this->modulePath . "Http" . DIRECTORY_SEPARATOR . "Controllers";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller'));
    }

    protected function createObservers()
    {
        $path = $this->modulePath . "Observers";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}Observer.php", $this->replaceContent('observer'));
    }

    protected function createPresenters()
    {
        $path = $this->modulePath . "Presenters";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}Presenter.php", $this->replaceContent('presenter'));
    }

    protected function createResourceProviders()
    {
        $path = $this->modulePath . "Providers";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}ServiceProvider.php", $this->replaceContent('resource-service-provider-module'));
    }

    protected function createResourceTranslations()
    {
        $path = $this->modulePath . "Resources" . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . "en";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/create.php", $this->replaceContent('resource-lang-en-create'));
        file_put_contents("$path/edit.php", $this->replaceContent('resource-lang-en-edit'));
        file_put_contents("$path/form.php", $this->replaceContent('resource-lang-en-form'));
        file_put_contents("$path/index.php", $this->replaceContent('resource-lang-en-index'));
    }

    protected function createResourceViews()
    {
        $path = $this->modulePath . "Resources" . DIRECTORY_SEPARATOR . "views";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/show.blade.php", $this->replaceContent('resource-views-show'));
    }

    protected function createResourceRoutes()
    {
        $path = $this->modulePath . "Routes";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/web.php", $this->replaceContent('resource-routes-web'));
    }

    protected function createResourceFeatureTests()
    {
        $path = $this->modulePath . "Tests" . DIRECTORY_SEPARATOR . "Feature";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/ResourceTest.php", $this->replaceContent('resource-tests-feature-resource'));
    }
}
