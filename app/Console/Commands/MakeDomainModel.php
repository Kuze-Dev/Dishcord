<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:domain-model', description: 'Create a model in the domain folder with optional migration, DTO, and Action')]
class MakeDomainModel extends Command
{
    public function handle(): int
    {
        $modelName = Str::studly($this->argument('name'));
        $domain = Str::studly($this->option('domain') ?? 'Common');

        // Create Model
        $modelPath = base_path("domain/{$domain}/Models");
        $modelFile = "{$modelPath}/{$modelName}.php";
        $modelNamespace = "Domain\\{$domain}\\Models";

        if (!File::isDirectory($modelPath)) {
            File::makeDirectory($modelPath, 0755, true);
        }

        $modelContent = <<<PHP
<?php

namespace {$modelNamespace};

use Illuminate\Database\Eloquent\Model;

class {$modelName} extends Model
{
    //
}

PHP;

        File::put($modelFile, $modelContent);
        $this->info("✅ Model created at: {$modelFile}");

        // Create Migration
        if ($this->option('migration')) {
            $table = Str::snake(Str::pluralStudly($modelName));
            $migrationName = "create_{$table}_table";
            $this->call('make:migration', ['name' => $migrationName]);
        }

        // Create DTO
        if ($this->option('dto')) {
            $dtoPath = base_path("domain/{$domain}/DataTransferObjects");
            $dtoFile = "{$dtoPath}/{$modelName}DTO.php";
            $dtoNamespace = "Domain\\{$domain}\\DataTransferObjects";

            if (!File::isDirectory($dtoPath)) {
                File::makeDirectory($dtoPath, 0755, true);
            }

            $dtoContent = <<<PHP
<?php

namespace {$dtoNamespace};

class {$modelName}DTO
{
    public function __construct(
        // Add your typed properties here
    ) {}
}
PHP;

            File::put($dtoFile, $dtoContent);
            $this->info("✅ DTO created at: {$dtoFile}");
        }

        // Create Action
        if ($this->option('action')) {
            $actionPath = base_path("domain/{$domain}/Actions");
            $actionFile = "{$actionPath}/Create{$modelName}.php";
            $actionNamespace = "Domain\\{$domain}\\Actions";

            if (!File::isDirectory($actionPath)) {
                File::makeDirectory($actionPath, 0755, true);
            }

            $actionContent = <<<PHP
<?php

namespace {$actionNamespace};

use Domain\\{$domain}\\DataTransferObjects\\{$modelName}DTO;
use Domain\\{$domain}\\Models\\{$modelName};

class Create{$modelName}
{
    public function handle({$modelName}DTO \$dto): {$modelName}
    {
        return {$modelName}::create([
            // Map DTO properties here, e.g. 'name' => \$dto->name,
        ]);
    }
}
PHP;

            File::put($actionFile, $actionContent);
            $this->info("✅ Action created at: {$actionFile}");
        }

        // Create Controller
    if ($this->option('controller')) {
        $controllerPath = base_path("domain/{$domain}/Controllers");
        $controllerFile = "{$controllerPath}/{$modelName}Controller.php";
        $controllerNamespace = "Domain\\{$domain}\\Controllers";

        if (!File::isDirectory($controllerPath)) {
            File::makeDirectory($controllerPath, 0755, true);
        }

        $controllerContent = <<<PHP
<?php

    namespace {$controllerNamespace};

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

class {$modelName}Controller extends Controller
{
    public function index()
    {
        //
    }
    public function store(Request \$request)
    {
        //
    }
    public function show(\$id)
    {
        //
    }
    public function update(Request \$request, \$id)
    {
        //
    }
    public function destroy(\$id)
    {
        //
    }
}
PHP;

        File::put($controllerFile, $controllerContent);
        $this->info("✅ Controller created at: {$controllerFile}");
    }

        return static::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The model name')
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'The domain name (e.g., Ingridients)')
            ->addOption('migration', null, InputOption::VALUE_NONE, 'Create migration')
            ->addOption('dto', null, InputOption::VALUE_NONE, 'Create DTO')
            ->addOption('action', null, InputOption::VALUE_NONE, 'Create Action')
            ->addOption('controller', null, InputOption::VALUE_NONE, 'Create Controller');
            
    }
}
