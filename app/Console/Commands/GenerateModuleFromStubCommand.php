<?php

namespace App\Console\Commands;

use App\Constants\ModuleConstant;
use App\Constants\StubConstant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

/**
 * Class GenerateModuleFromStubCommand
 *
 * Generate a module from stubs available in resources.
 *
 * @package App\Console\Commands
 */
class GenerateModuleFromStubCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @example generate:module --model=User --stubs=model,controller,service --module=Shared --route=admin
     *   Generate a module with the following options:
     *   - --model: Specify the model name (e.g., --model=User).
     *   - --stubs: Specify the stubs to generate (comma-separated, e.g., --stubs=model,controller,service).
     *     Allowed values for --stubs: migration, data, model, request, resource, repository, service, controller, test.
     *   - --module: Specify the module name (e.g., --module=Shared).
     *     Allowed values for --module: Shared, Example.
     *   - --route: Specify the API route (e.g., --route=admin).
     *     Allowed values for --route: admin, client.
     */

    protected $signature = 'generate:module {--model=} {--stubs=} {--seeded=true} {--module=Shared} {--route=admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a module including Controller, Service, Repository, etc.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stubs = !empty($this->option('stubs'))
            ? explode(',', rtrim($this->option('stubs'), ','))
            : StubConstant::getAllConstantValues();

        $this->validateInput($stubs);
        $this->generateStubs($stubs);

        return Command::SUCCESS;
    }

    /**
     * Validate input parameters.
     *
     * @param array $stubs
     */
    private function validateInput(array $stubs = []): void
    {
        $model = $this->option('model');
        $module = $this->option('module');
        
        if (empty($model)) {
            $this->error('Error: Model name is missing.');
            die();
        }

        $unavailableModules = array_diff([$module], ModuleConstant::getAllConstants());
        if (!empty($unavailableModules)) {
            $this->error('Error: Unavailable module: ' . $module);
            die();
        }

        $unavailableStubs = array_diff($stubs, StubConstant::getAllConstants());
        if (!empty($unavailableStubs)) {
            $this->error('Error: Unavailable stubs: ' . implode(', ', $unavailableStubs));
            die();
        }
    }

    /**
     * Generate stubs based on the provided stub types.
     *
     * @param array $availableStubs
     */
    private function generateStubs(array $availableStubs): void
    {
        foreach ($availableStubs as $stub) {
            // Add stub cases that generates 2 or more files
            if ($stub === StubConstant::TEST) {
                $this->generateTestFile();
                continue;
            }

            $fileContent = $this->generateFileContentFromStub($stub);
            switch ($stub) {
                case StubConstant::MIGRATION:
                    $this->generateMigrationFile($fileContent);
                    break;
                case StubConstant::DATA:
                    $this->generateDataFile($fileContent);
                    break;
                case StubConstant::MODEL:
                    $this->generateModelFile($fileContent);
                    break;
                case StubConstant::REQUEST:
                    $this->generateRequestFile($fileContent);
                    break;
                case StubConstant::RESOURCE:
                    $this->generateResourceFile($fileContent);
                    break;
                case StubConstant::REPOSITORY:
                    $this->generateRepositoryFile($fileContent);
                    break;
                case StubConstant::SERVICE:
                    $this->generateServiceFile($fileContent);
                    break;
                case StubConstant::CONTROLLER:
                    $this->generateControllerFile($fileContent);
                    break;
                // Add other stub cases as needed
            }
        }
    }

    /**
     * Generate file content based on a stub type.
     *
     * @param string $stubName
     * @return string|null
     */
    private function generateFileContentFromStub(string $stubName): ?string
    {
        $fileContent = $this->getStubContent(ucfirst($stubName));
        if (!empty($fileContent)) {
            return $this->replacePlaceholders($fileContent);
        }

        return null;
    }

    /**
     * Get content of a stub file.
     *
     * @param string $stubName
     * @return false|string
     */
    private function getStubContent(string $stubName)
    {
        $isSeeded = $this->option('seeded');
        $isSeeded = is_null($isSeeded) ? true : filter_var($isSeeded, FILTER_VALIDATE_BOOLEAN);

        if ($isSeeded) {
            return $this->getFileContents(resource_path("stubs/seeded/$stubName.stub"));
        }
        
        return $this->getFileContents(resource_path("stubs/default/$stubName.stub"));
    }

    /**
     * Replace placeholders in the file content with actual values.
     *
     * @param string $fileContent
     * @return string
     */
    private function replacePlaceholders(string $fileContent): string
    {
        $model = $this->option('model');
        $module = $this->option('module');
        $route = $this->option('route');
        $moduleRoutes = ModuleConstant::getModuleRoutes();

        $placeholders = [
            '{{ model }}',
            '{{ module }}',
            '{{ route }}',
            '{{ routeModule }}',
            '{{ modelLowerCaseFirst }}',
            '{{ modelPlural }}',
            '{{ modelLowerCaseFirstPlural }}',
            '{{ modelDecamelizedUpperCasePlural }}',
            '{{ modelDashedLowerCasePlural }}',
            '{{ modelSpacedLowerCase }}',
            '{{ modelSpacedUpperCaseWord }}',
            '{{ modelSpacedUpperCaseFirst }}',
            '{{ moduleDashedLowerCaseWord }}',
        ];

        $replacements = [
            ucfirst($model),
            $module,
            $route,
            $moduleRoutes[$module],
            lcfirst($model),
            Str::plural($model),
            Str::plural(lcfirst($model)),
            Str::plural(strtoupper($this->decamelize($model))),
            Str::plural(strtolower($this->convertCamelToDashed($model))),
            trim(strtolower($this->convertCamelToSpace($model))),
            trim(ucwords($this->convertCamelToSpace($model))),
            ucfirst(trim(strtolower($this->convertCamelToSpace($model)))),
            strtolower($this->convertCamelToDashed($module))
        ];

        return str_replace($placeholders, $replacements, $fileContent);
    }

    /**
     * Convert camel case to space-separated words.
     *
     * @param string $string
     * @return string
     */
    private function convertCamelToSpace(string $string): string
    {
        $pieces = preg_split('/(?=[A-Z])/', $string);
        return implode(" ", $pieces);
    }

    /**
     * Convert camel case to dashed notation.
     *
     * @param string $string
     * @return string
     */
    private function convertCamelToDashed(string $string): string
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $string));
    }

    /**
     * Decamelize a string.
     *
     * @param string $string
     * @return string
     */
    private function decamelize(string $string): string
    {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
    }

    /**
     * Get the current date as a prefix for file names.
     *
     * @return string
     */
    private function getDatePrefix(): string
    {
        return date('Y_m_d_His');
    }

    /**
     * Get the contents of the file.
     *
     * @param string $filePath
     * 
     * @return false|string
     */
    private function getFileContents(string $filePath): string|false
    {
        return file_get_contents($filePath);
    }

    /**
     * Append code for a specified model and module.
     *
     * @return void
     */
    private function appendCodeToFile(
        string $searchKey,
        string $codeToAppend,
        string $basePath
    ): void {
        $apiRouteFile = $this->getFileContents($basePath);
    
        if (strpos($apiRouteFile, $codeToAppend) === false) {
            if (preg_match($searchKey, $apiRouteFile, $matches, PREG_OFFSET_CAPTURE)) {
                $lineStartPos = strlen($matches[0][0]) + $matches[0][1] + 1;
                $newFile = substr_replace($apiRouteFile, $codeToAppend, $lineStartPos, 0);
    
                file_put_contents($basePath, $newFile);
            } else {
                $this->error("Search key not found: {$searchKey}");
                die();
            }
        }
    }

    /**
     * Add import line for the specified controller right after <?php at the top of the route file.
     *
     * @return void
     */
    private function appendDatabaseTableConstant(): void
{
    $model = $this->option('model');
    $module = $this->option('module');

    $moduleString = $this->convertCamelToDashed($module);
    $searchKey = "/\/\/ generate-module-append-constant-{$moduleString}/";
    $modelDecamelizedUpperCasePlural = Str::plural(strtoupper($this->decamelize($model)));
    $modelDashedLowerCasePlural = Str::plural(strtolower($this->convertCamelToDashed($model)));
    $codeToAppend = "\n\tconst {$modelDecamelizedUpperCasePlural} = '{$modelDashedLowerCasePlural}';";

    $routeFilePath = app_path("/Constants/DatabaseTableConstant.php");
    $this->appendCodeToFile($searchKey, $codeToAppend, $routeFilePath);
}

    /**
     * Append a generic API resource route for a specified model and module.
     *
     * @return void
     */
    private function appendGenericApiResource(): void
    {
        $model = $this->option('model');
        $module = $this->option('module');

        $modelUrl = Str::plural(strtolower($this->convertCamelToDashed($model)));
        $searchKey = "/\/\/ generate-module-append-route-{$this->option('route')}/";
        $controllerName = ucfirst($model) . 'Controller';
        $codeToAppend = "\n\tRoute::genericApiResource('" . $modelUrl . "', " . $controllerName . "::class);";

        $routeFilePath = base_path("/routes/{$module}/api.php");

        $this->appendCodeToFile($searchKey, $codeToAppend, $routeFilePath);
    }

    /**
     * Add import line for the specified controller right after <?php at the top of the route file.
     *
     * @return void
     */
    private function appendControllerImport(): void
    {
        $model = $this->option('model');
        $module = $this->option('module');

        $controllerName = ucfirst($model) . 'Controller';
        $searchKey = "/\/\/ generate-module-append-controller/";
        $codeToAppend = "\nuse App\Http\Controllers\\{$module}\\{$controllerName};";
        $routeFilePath = base_path("/routes/{$module}/api.php");

        $this->appendCodeToFile($searchKey, $codeToAppend, $routeFilePath);
    }

    /**
     * Generate a migration file with the provided file content.
     *
     * @param string $fileContent
     */
    private function generateMigrationFile(string $fileContent): void
    {
        $model = $this->option('model');
        $fileName = $this->getDatePrefix() . '_' . 'create_' . Str::plural(strtolower($this->decamelize($model))) . '_table';
        file_put_contents(database_path("/migrations/{$fileName}.php"), $fileContent);

        $this->appendDatabaseTableConstant();
    }

    /**
     * Generate a model file with the provided file content.
     *
     * @param string $fileContent
     */
    private function generateModelFile(string $fileContent): void
    {
        $model = $this->option('model');
        $fileName = ucfirst($model);
        file_put_contents(app_path("/Models/{$fileName}.php"), $fileContent);
    }

    /**
     * Generate a data file with the provided file content.
     *
     * @param string $fileContent
     */
    private function generateDataFile(string $fileContent): void
    {
        $model = $this->option('model');
        $module = $this->option('module');
        $fileName = ucfirst($model). 'Data';
        file_put_contents(app_path("/Data/{$module}/{$fileName}.php"), $fileContent);
    }

    /**
     * Generate a request file with the provided file content.
     *
     * @param string $fileContent
     */
    private function generateRequestFile(string $fileContent): void
    {
        $model = $this->option('model');
        $module = $this->option('module');
        $fileName = ucfirst($model). 'Request';
        file_put_contents(app_path("/Http/Requests/{$module}/{$fileName}.php"), $fileContent);
    }

    /**
     * Generate a resource file with the provided file content.
     *
     * @param string $fileContent
     */
    private function generateResourceFile(string $fileContent): void
    {
        $model = $this->option('model');
        $module = $this->option('module');
        $fileName = ucfirst($model). 'Resource';
        file_put_contents(app_path("/Http/Resources/{$module}/{$fileName}.php"), $fileContent);
    }

    /**
     * Generate a repository file with the provided file content.
     *
     * @param string $fileContent
     */
    private function generateRepositoryFile(string $fileContent): void
    {
        $model = $this->option('model');
        $module = $this->option('module');
        $fileName = ucfirst($model). 'Repository';
        file_put_contents(app_path("/Repositories/{$module}/{$fileName}.php"), $fileContent);
    }

    /**
     * Generate a service file with the provided file content.
     *
     * @param string $fileContent
     */
    private function generateServiceFile(string $fileContent): void
    {
        $model = $this->option('model');
        $module = $this->option('module');
        $fileName = ucfirst($model). 'Service';
        file_put_contents(app_path("/Services/{$module}/{$fileName}.php"), $fileContent);
    }

    /**
     * Generate a controller file with the provided file content.
     *
     * @param string $fileContent
     */
    private function generateControllerFile(string $fileContent): void
    {
        $model = $this->option('model');
        $module = $this->option('module');
        $fileName = ucfirst($model). 'Controller';
        file_put_contents(app_path("/Http/Controllers/{$module}/{$fileName}.php"), $fileContent);
        
        $this->appendGenericApiResource();
        $this->appendControllerImport();
    }

    /**
     * Generate a feature and unit test file with the provided file content.
     */
    private function generateTestFile(): void
    {
        $model = $this->option('model');
        $module = $this->option('module');

        $featureFileName = ucfirst($model). 'FeatureTest';
        $featureFileContent = $this->generateFileContentFromStub('featureTest');
        file_put_contents(base_path("/tests/Feature/{$module}/{$featureFileName}.php"), $featureFileContent);

        $unitFileName = ucfirst($model). 'UnitTest';
        $unitFileContent = $this->generateFileContentFromStub('unitTest');
        file_put_contents(base_path("/tests/Unit/{$module}/{$unitFileName}.php"), $unitFileContent);
    }
}
