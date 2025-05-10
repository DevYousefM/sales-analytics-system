<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreatePhpClassCommand extends Command
{

    protected $signature = 'make:class {name} {--path=app}  {--force}';

    protected $description = 'This command creates a new PHP class file with namespace support.';

    public function handle()
    {
        $name = $this->argument('name');
        $basePath = rtrim($this->option('path'), '/');;

        $name = str_replace('\\', '/', $name);
        $parts = explode('/', $name);
        $className = array_pop($parts);
        $subPath = implode('/', $parts);

        $fullDir = $basePath . ($subPath ? '/' . $subPath : '');
        $filePath = $fullDir . '/' . $className . '.php';

        $namespaceParts = array_merge(
            [$basePath === 'app' ? 'App' : ucwords(str_replace('/', '\\', $basePath))],
            array_map([Str::class, 'studly'], $parts)
        );
        $namespace = implode('\\', $namespaceParts);

        if (!File::exists($fullDir)) {
            File::makeDirectory($fullDir, 0755, true);
        }

        if (File::exists(($filePath)) && !$this->option('force')) {
            $this->error("File already exists at: $filePath (use --force to overwrite)");
            return;
        }

        $content = <<<PHP
                <?php

                namespace {$namespace};

                class {$className}
                {
                }
                PHP;

        File::put($filePath, $content);
        $this->info("Class '$className' created at: $filePath");
    }
}
