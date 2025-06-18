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

        // -- DTO Creation --
        if ($this->option('dto')) {
            // Use the dto option value directly, or fall back to dto-name, or default
            $dtoName = Str::studly($this->option('dto') !== true ? $this->option('dto') : ($this->option('dto-name') ?? "{$modelName}DTO"));
            $dtoPath = base_path("domain/{$domain}/DataTransferObjects");
            $dtoFile = "{$dtoPath}/{$dtoName}.php";
            $dtoNamespace = "Domain\\{$domain}\\DataTransferObjects";

            if (!File::isDirectory($dtoPath)) {
                File::makeDirectory($dtoPath, 0755, true);
            }

            $dtoContent = <<<PHP
<?php

namespace {$dtoNamespace};

class {$dtoName}
{
    public function __construct(
        // Add your typed properties here
    ) {}
}
PHP;

            File::put($dtoFile, $dtoContent);
            $this->info("✅ DTO created at: {$dtoFile}");
        }

        // -- Action Creation --
        if ($this->option('action')) {
            // Use the action option value directly, or fall back to action-name, or default
            $actionName = Str::studly($this->option('action') !== true ? $this->option('action') : ($this->option('action-name') ?? "Create{$modelName}"));
            $actionPath = base_path("domain/{$domain}/Actions");
            $actionFile = "{$actionPath}/{$actionName}.php";
            $actionNamespace = "Domain\\{$domain}\\Actions";

            if (!File::isDirectory($actionPath)) {
                File::makeDirectory($actionPath, 0755, true);
            }

            // Use the DTO name from the dto option or fallback
            $dtoName = Str::studly($this->option('dto') && $this->option('dto') !== true ? $this->option('dto') : ($this->option('dto-name') ?? "{$modelName}DTO"));

            $actionContent = <<<PHP
<?php

namespace {$actionNamespace};

use Domain\\{$domain}\\DataTransferObjects\\{$dtoName};
use Domain\\{$domain}\\Models\\{$modelName};

class {$actionName}
{
    public function handle({$dtoName} \$dto): {$modelName}
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
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'The domain name (e.g., Ingredients)')
            ->addOption('dto', null, InputOption::VALUE_OPTIONAL, 'Create DTO with optional custom name')
            ->addOption('dto-name', null, InputOption::VALUE_OPTIONAL, 'Custom DTO class name (deprecated, use --dto instead)')
            ->addOption('action', null, InputOption::VALUE_OPTIONAL, 'Create Action with optional custom name')
            ->addOption('action-name', null, InputOption::VALUE_OPTIONAL, 'Custom Action class name (deprecated, use --action instead)');
    }
}