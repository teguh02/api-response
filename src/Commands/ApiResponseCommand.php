<?php

namespace teguh02\ApiResponse\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Pluralizer;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use function Laravel\Prompts\search;

class ApiResponseCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-response:make 
        {class : API Response Class type (ApiTransformer, ApiFormatter, ApiValidator)} 
        {classname : The name of the class}
        {--force : Overwrite existing file if it exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API Response class (Transformer, Formatter, or Validator)';

    /**
     * Available class types
     *
     * @var array
     */
    protected $availableClasses = [
        'ApiTransformer',
        'ApiFormatter',
        'ApiValidator',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $classType = $this->argument('class');
        $className = $this->argument('classname');
        
        // Validate class type
        if (!in_array($classType, $this->availableClasses)) {
            $this->error("Invalid class type. Available types: " . implode(', ', $this->availableClasses));
            return self::FAILURE;
        }

        // Process namespace and path
        $baseNamespace = 'App\\Http\\Api';
        $pathToStore = app_path('Http/Api');
        $finalNamespace = $baseNamespace;
        
        // Handle nested structure (User/Note)
        if (Str::contains($className, ['/', '\\', '.'])) {
            $separator = Str::contains($className, '/') ? '/' : 
                        (Str::contains($className, '\\') ? '\\' : '.');
            
            // Get the path parts
            $pathParts = explode($separator, $className);
            $className = array_pop($pathParts); // Get the last part as class name
            
            // Build directory path
            $subDirectory = implode('/', $pathParts);
            $pathToStore .= '/' . $subDirectory;
            
            // Build namespace
            $subNamespace = str_replace(['/', '.'], '\\', $subDirectory);
            $finalNamespace .= '\\' . $subNamespace;
        }

        // Generate filename
        $filename = $this->getSingularClassName($className) . $classType . '.php';
        $fullPath = $pathToStore . '/' . $filename;

        // Check if file exists and not forcing
        if (file_exists($fullPath) && !$this->option('force')) {
            $this->error("File already exists at: {$fullPath}");
            $this->info("Use --force to overwrite");
            return self::FAILURE;
        }

        // Generate and save file
        try {
            $stub = $this->generateClassFile($classType, $className, $finalNamespace);
            
            $filesystem = new Filesystem();
            $filesystem->ensureDirectoryExists($pathToStore);
            $filesystem->put($fullPath, $stub);
            
            $this->info("{$classType} created successfully at: {$fullPath}");
            return self::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error creating file: " . $e->getMessage());
            Log::error("Failed to create API Response class", [
                'error' => $e->getMessage(),
                'class' => $classType,
                'classname' => $className
            ]);
            return self::FAILURE;
        }
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array<string, string>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'class' => fn () => search(
                label: 'Which class do you want to create?',
                placeholder: 'Type the class name (e.g. ApiTransformer)',
                options: fn ($value) => strlen($value) > 0
                    ? collect($this->availableClasses)
                        ->filter(fn ($option) => str_contains($option, $value))
                        ->values()
                        ->toArray()
                    : [],
            ),

            'classname' => 'What is the name of the class?',
        ];
    }

    /**
     * Generate the class file from stub
     *
     * @param string $classType
     * @param string $className
     * @param string $namespace
     * @return string
     * @throws \Exception
     */
    protected function generateClassFile(string $classType, string $className, string $namespace): string
    {
        $stubPath = $this->getStubPath($classType);
        
        if (!file_exists($stubPath)) {
            throw new \Exception("Stub file not found for {$classType}");
        }
        
        $replacements = [
            'NAMESPACE' => $namespace,
            'CLASS_NAME' => $this->getSingularClassName($className),
        ];
        
        return $this->replacePlaceholders(file_get_contents($stubPath), $replacements);
    }

    /**
     * Get the path to the stub file
     *
     * @param string $classType
     * @return string
     */
    protected function getStubPath(string $classType): string
    {
        return dirname(__DIR__, 2) . '/stubs/' . $classType . '.stub';
    }

    /**
     * Replace placeholders in stub content
     *
     * @param string $content
     * @param array $replacements
     * @return string
     */
    protected function replacePlaceholders(string $content, array $replacements): string
    {
        foreach ($replacements as $key => $value) {
            $content = str_replace('$' . $key . '$', $value, $content);
        }
        
        return $content;
    }

    /**
     * Get the singular form of the class name
     *
     * @param string $name
     * @return string
     */
    protected function getSingularClassName(string $name): string
    {
        return ucwords(Pluralizer::singular($name));
    }
}