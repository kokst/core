<?php

namespace Kokst\Core\Console\Commands;

use function Safe\file_get_contents;
use function Safe\file_put_contents;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeModuleCommand extends Command
{
    protected $signature = 'kok:make-module {name*} {--R|resource} {--B|basic} {--Y|yearly}';

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

    protected $yearly;

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
        $yearly = $this->option('yearly');

        if (!$resource && $basic) {
            $this->warn('Warning: Ignoring Basic option (-B / --basic); only available for Resource modules (-R / --resource)');
        }

        if (!$resource && $yearly) {
            $this->warn('Warning: Ignoring Yearly option (-Y / --yearly); only available for Resource modules (-R / --resource)');
        }

        $this->basic = $basic ? 'true' : 'false';
        $this->yearly = $yearly ? 'true' : 'false';

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

                $this->createConfig();              $bar->advance();
                $this->createControllers();         $bar->advance();
                $this->createMenuMiddleware();      $bar->advance();
                $this->createProviders();           $bar->advance();
                $this->createTranslations();        $bar->advance();
                $this->createRoutes();              $bar->advance();
                $this->createFeatureTests();        $bar->advance();
                $this->createModuleFile();          $bar->advance();

                if (!$resource) {
                    $this->createViews();           $bar->advance();
                }

                if ($resource) {
                    $this->createFactories($yearly);                    $bar->advance();
                    $this->createMigrations($yearly);                   $bar->advance();
                    $this->createSeeders();                             $bar->advance();
                    $this->createEntities($yearly);                     $bar->advance();
                    $this->createResourceControllers($yearly);          $bar->advance();
                    $this->createObservers($yearly);                    $bar->advance();
                    $this->createPresenters();                          $bar->advance();
                    $this->createResourceProviders();                   $bar->advance();
                    $this->createResourceTranslations($yearly);         $bar->advance();
                    $this->createResourceViews();                       $bar->advance();
                    $this->createResourceRoutes($yearly);               $bar->advance();
                    $this->createResourceFeatureTests();                $bar->advance();
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

    protected function createFactories($yearly)
    {
        $path = $this->modulePath . "Database" . DIRECTORY_SEPARATOR . "factories";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}Factory.php", $this->replaceContent('factory/open'));
        file_put_contents("$path/{$this->module}Factory.php", $this->replaceContent('factory/fields-default'), FILE_APPEND);

        if ($yearly) {
            file_put_contents("$path/{$this->module}Factory.php", $this->replaceContent('factory/fields-yearly'), FILE_APPEND);
        }

        file_put_contents("$path/{$this->module}Factory.php", $this->replaceContent('factory/close'), FILE_APPEND);
    }

    protected function createMigrations($yearly)
    {
        $path = $this->modulePath . "Database" . DIRECTORY_SEPARATOR . "Migrations";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        $filename = date('Y_m_d_His') . "_create_{$this->modulePluralSnakecase}_table.php" ;

        file_put_contents("$path/$filename", $this->replaceContent('migration/open'));
        file_put_contents("$path/$filename", $this->replaceContent('migration/fields-default'), FILE_APPEND);

        if ($yearly) {
            file_put_contents("$path/$filename", $this->replaceContent('migration/fields-yearly'), FILE_APPEND);
        }

        file_put_contents("$path/$filename", $this->replaceContent('migration/fields-timestamps'), FILE_APPEND);
        file_put_contents("$path/$filename", $this->replaceContent('migration/fields-softdeletes'), FILE_APPEND);
        file_put_contents("$path/$filename", $this->replaceContent('migration/close'), FILE_APPEND);
    }

    protected function createSeeders()
    {
        $path = $this->modulePath . "Database" . DIRECTORY_SEPARATOR . "Seeders";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}TableSeeder.php", $this->replaceContent('seeder-table'));
    }

    protected function createEntities($yearly)
    {
        $path = $this->modulePath . "Entities";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}.php", $this->replaceContent('entity/open'));
        file_put_contents("$path/{$this->module}.php", $this->replaceContent('entity/fields-default'), FILE_APPEND);

        if($yearly) {
            file_put_contents("$path/{$this->module}.php", $this->replaceContent('entity/fields-yearly'), FILE_APPEND);
        }

        file_put_contents("$path/{$this->module}.php", $this->replaceContent('entity/close'), FILE_APPEND);
    }

    protected function createResourceControllers($yearly)
    {
        $path = $this->modulePath . "Http" . DIRECTORY_SEPARATOR . "Controllers";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/open'));

        if ($yearly) {
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/index-yearly'), FILE_APPEND);
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/create-yearly'), FILE_APPEND);
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/store-yearly'), FILE_APPEND);
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/edit-yearly'), FILE_APPEND);
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/update-yearly'), FILE_APPEND);
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/destroy-yearly'), FILE_APPEND);
        } else {
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/index'), FILE_APPEND);
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/create'), FILE_APPEND);
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/store'), FILE_APPEND);
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/edit'), FILE_APPEND);
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/update'), FILE_APPEND);
            file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/destroy'), FILE_APPEND);
        }

        file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/show'), FILE_APPEND);
        file_put_contents("$path/{$this->module}Controller.php", $this->replaceContent('resource-controller/close'), FILE_APPEND);
    }

    protected function createObservers($yearly)
    {
        $path = $this->modulePath . "Observers";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        if ($yearly) {
            file_put_contents("$path/{$this->module}Observer.php", $this->replaceContent('observer/resource-yearly'));
        } else {
            file_put_contents("$path/{$this->module}Observer.php", $this->replaceContent('observer/resource'));
        }
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

    protected function createResourceTranslations($yearly)
    {
        $path = $this->modulePath . "Resources" . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . "en";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/create.php", $this->replaceContent('resource-lang-en-create'));
        file_put_contents("$path/edit.php", $this->replaceContent('resource-lang-en-edit'));

        if ($yearly) {
            file_put_contents("$path/form.php", $this->replaceContent('resource-lang/en/form-yearly'));
        } else {
            file_put_contents("$path/form.php", $this->replaceContent('resource-lang/en/form'));
        }

        file_put_contents("$path/index.php", $this->replaceContent('resource-lang-en-index'));
    }

    protected function createResourceViews()
    {
        $path = $this->modulePath . "Resources" . DIRECTORY_SEPARATOR . "views";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/show.blade.php", $this->replaceContent('resource-views-show'));
    }

    protected function createResourceRoutes($yearly)
    {
        $path = $this->modulePath . "Routes";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        if ($yearly) {
            file_put_contents("$path/web.php", $this->replaceContent('resource-routes/web-yearly'));
        } else {
            file_put_contents("$path/web.php", $this->replaceContent('resource-routes/web'));
        }
    }

    protected function createResourceFeatureTests()
    {
        $path = $this->modulePath . "Tests" . DIRECTORY_SEPARATOR . "Feature";
        $this->makeDirectory($path . DIRECTORY_SEPARATOR . "dir");

        file_put_contents("$path/ResourceTest.php", $this->replaceContent('resource-tests-feature-resource'));
    }
}
