<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:domain-action', description: 'Create domain-layer action classes like Create, Update, Delete for a model')]
class MakeDomainAction extends Command
{
    public function handle(): int
    {
        $modelName = Str::studly($this->option('model'));
        $domain = Str::studly($this->option('domain') ?? 'Common');
        $actionType = $this->option('action');

        if (!$modelName) {
            $this->error('❌ The --model option is required.');
            return static::FAILURE;
        }

        $availableActions = ['create', 'update', 'delete'];
        $actions = $actionType === 'all' ? $availableActions : [$actionType];

        foreach ($actions as $type) {
            if (!in_array($type, $availableActions)) {
                $this->error("❌ Unsupported action type: {$type}");
                continue;
            }

            $this->generateAction($type, $modelName, $domain);
        }

        return static::SUCCESS;
    }

    protected function generateAction(string $type, string $modelName, string $domain): void
    {
        $actionPath = base_path("domain/{$domain}/Actions");
        $actionNamespace = "Domain\\{$domain}\\Actions";
        $dtoNamespace = "Domain\\{$domain}\\DataTransferObjects\\{$modelName}DTO";
        $modelNamespace = "Domain\\{$domain}\\Models\\{$modelName}";
        $actionClass = ucfirst($type) . $modelName;
        $actionFile = "{$actionPath}/{$actionClass}.php";

        if (!File::isDirectory($actionPath)) {
            File::makeDirectory($actionPath, 0755, true);
        }

        $methodBody = match ($type) {
            'create' => <<<PHP
        return {$modelName}::create([
            // Map DTO properties here
        ]);
PHP,
            'update' => <<<PHP
        \$model = {$modelName}::findOrFail(\$id);
        \$model->update([
            // Map DTO properties here
        ]);
        return \$model;
PHP,
            'delete' => <<<PHP
        \$model = {$modelName}::findOrFail(\$id);
        \$model->delete();
        return true;
PHP,
        };

        $methodSignature = $type === 'delete'
            ? "public function handle(int \$id): bool"
            : "public function handle({$modelName}DTO \$dto" . ($type === 'update' ? ", int \$id" : "") . "): {$modelName}";

        $useStatements = <<<PHP
use {$dtoNamespace};
use {$modelNamespace};
PHP;

        $actionContent = <<<PHP
<?php

namespace {$actionNamespace};

{$useStatements}

class {$actionClass}
{
    {$methodSignature}
    {
{$methodBody}
    }
}
PHP;

        File::put($actionFile, $actionContent);
        $this->info("✅ {$actionClass} created at: {$actionFile}");
    }

    protected function configure(): void
    {
        $this
            ->addOption('model', null, InputOption::VALUE_REQUIRED, 'The model name')
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'The domain name (e.g., Ingridients)')
            ->addOption('action', null, InputOption::VALUE_REQUIRED, 'Action type: create, update, delete, or all');
    }
}
 