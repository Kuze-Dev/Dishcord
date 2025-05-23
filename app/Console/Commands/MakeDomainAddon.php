<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:domain-addon', description: 'Create DTO and/or Action for an existing domain model')]
class MakeDomainAddon extends Command
{
    public function handle(): int
    {
        $modelName = Str::studly($this->option('model'));
        $domain = Str::studly($this->option('domain') ?? 'Common');

        if (! $modelName) {
            $this->error('❌ The --model option is required.');
            return static::FAILURE;
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

        return static::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addOption('model', null, InputOption::VALUE_REQUIRED, 'The model name')
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'The domain name (e.g., Ingridients)')
            ->addOption('dto', null, InputOption::VALUE_NONE, 'Create DTO')
            ->addOption('action', null, InputOption::VALUE_NONE, 'Create Action');
    }
}
