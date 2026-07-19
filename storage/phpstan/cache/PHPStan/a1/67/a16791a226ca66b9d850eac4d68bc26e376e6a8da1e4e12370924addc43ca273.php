<?php declare(strict_types = 1);

// osfsl-/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-health/src/Models/HealthCheckResultHistoryItem.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\Health\Models\HealthCheckResultHistoryItem
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-f1e46a732585ef12199e0c4b0d4c6e2ac75fde0fdf7c632760166ec9fbb3d598-8.3.29-6.70.0.3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'filename' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-health/src/Models/HealthCheckResultHistoryItem.php',
      ),
    ),
    'namespace' => 'Spatie\\Health\\Models',
    'name' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
    'shortName' => 'HealthCheckResultHistoryItem',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $batch
 * @property string $ended_at
 * @property string $notification_message
 * @property string $short_summary
 * @property array<string, mixed> $meta
 * @property string $status
 * @property string $check_name
 * @property string $check_label
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 24,
    'endLine' => 50,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      1 => 'Illuminate\\Database\\Eloquent\\MassPrunable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'guarded' => 
      array (
        'declaringClassName' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'implementingClassName' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'name' => 'guarded',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 29,
            'endLine' => 29,
            'startTokenPos' => 65,
            'startFilePos' => 760,
            'endTokenPos' => 66,
            'endFilePos' => 761,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 29,
        'endLine' => 29,
        'startColumn' => 5,
        'endColumn' => 28,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'implementingClassName' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'name' => 'casts',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'meta\' => \'array\', \'started_failing_at\' => \'timestamp\']',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 35,
            'startTokenPos' => 77,
            'startFilePos' => 822,
            'endTokenPos' => 93,
            'endFilePos' => 900,
          ),
        ),
        'docComment' => '/** @var array<string,string> */',
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'getConnectionName' => 
      array (
        'name' => 'getConnectionName',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 37,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Health\\Models',
        'declaringClassName' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'implementingClassName' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'currentClassName' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'aliasName' => NULL,
      ),
      'prunable' => 
      array (
        'name' => 'prunable',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Builder',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 44,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Health\\Models',
        'declaringClassName' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'implementingClassName' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'currentClassName' => 'Spatie\\Health\\Models\\HealthCheckResultHistoryItem',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));