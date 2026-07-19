<?php declare(strict_types = 1);

// osfsl-/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-activitylog/src/Models/Activity.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\Activitylog\Models\Activity
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-d7796edc45ced67125a8bfd39b8400a68a8667544c246d0e61fc624e1407f466-8.3.29-6.70.0.3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\Activitylog\\Models\\Activity',
        'filename' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-activitylog/src/Models/Activity.php',
      ),
    ),
    'namespace' => 'Spatie\\Activitylog\\Models',
    'name' => 'Spatie\\Activitylog\\Models\\Activity',
    'shortName' => 'Activity',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * Spatie\\Activitylog\\Models\\Activity.
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property string|null $event
 * @property string|null $batch_uuid
 * @property \\Illuminate\\Support\\Collection|null $properties
 * @property \\Carbon\\Carbon|null $created_at
 * @property \\Carbon\\Carbon|null $updated_at
 * @property-read \\Illuminate\\Database\\Eloquent\\Model|\\Eloquent|null $causer
 * @property-read \\Illuminate\\Support\\Collection $changes
 * @property-read \\Illuminate\\Database\\Eloquent\\Model|\\Eloquent|null $subject
 *
 * @method static \\Illuminate\\Database\\Eloquent\\Builder|\\Spatie\\Activitylog\\Models\\Activity causedBy(\\Illuminate\\Database\\Eloquent\\Model $causer)
 * @method static \\Illuminate\\Database\\Eloquent\\Builder|\\Spatie\\Activitylog\\Models\\Activity forBatch(string $batchUuid)
 * @method static \\Illuminate\\Database\\Eloquent\\Builder|\\Spatie\\Activitylog\\Models\\Activity forEvent(string $event)
 * @method static \\Illuminate\\Database\\Eloquent\\Builder|\\Spatie\\Activitylog\\Models\\Activity forSubject(\\Illuminate\\Database\\Eloquent\\Model $subject)
 * @method static \\Illuminate\\Database\\Eloquent\\Builder|\\Spatie\\Activitylog\\Models\\Activity hasBatch()
 * @method static \\Illuminate\\Database\\Eloquent\\Builder|\\Spatie\\Activitylog\\Models\\Activity inLog($logNames)
 * @method static \\Illuminate\\Database\\Eloquent\\Builder|\\Spatie\\Activitylog\\Models\\Activity newModelQuery()
 * @method static \\Illuminate\\Database\\Eloquent\\Builder|\\Spatie\\Activitylog\\Models\\Activity newQuery()
 * @method static \\Illuminate\\Database\\Eloquent\\Builder|\\Spatie\\Activitylog\\Models\\Activity query()
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 42,
    'endLine' => 139,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\Activitylog\\Contracts\\Activity',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'guarded' => 
      array (
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'name' => 'guarded',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 68,
            'startFilePos' => 2212,
            'endTokenPos' => 69,
            'endFilePos' => 2213,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'properties\' => \'collection\']',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 48,
            'startTokenPos' => 78,
            'startFilePos' => 2240,
            'endTokenPos' => 87,
            'endFilePos' => 2284,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 48,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 50,
                'endLine' => 50,
                'startTokenPos' => 102,
                'startFilePos' => 2340,
                'endTokenPos' => 103,
                'endFilePos' => 2341,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 50,
            'endLine' => 50,
            'startColumn' => 33,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 50,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'subject' => 
      array (
        'name' => 'subject',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return MorphTo<Model, $this>
 */',
        'startLine' => 66,
        'endLine' => 73,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'causer' => 
      array (
        'name' => 'causer',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\MorphTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return MorphTo<Model, $this>
 */',
        'startLine' => 78,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'getExtraProperty' => 
      array (
        'name' => 'getExtraProperty',
        'parameters' => 
        array (
          'propertyName' => 
          array (
            'name' => 'propertyName',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 83,
            'endLine' => 83,
            'startColumn' => 38,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'defaultValue' => 
          array (
            'name' => 'defaultValue',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 83,
                'endLine' => 83,
                'startTokenPos' => 272,
                'startFilePos' => 3163,
                'endTokenPos' => 272,
                'endFilePos' => 3166,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'mixed',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 83,
            'endLine' => 83,
            'startColumn' => 60,
            'endColumn' => 85,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 83,
        'endLine' => 86,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'changes' => 
      array (
        'name' => 'changes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 88,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'getChangesAttribute' => 
      array (
        'name' => 'getChangesAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Support\\Collection',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 97,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'scopeInLog' => 
      array (
        'name' => 'scopeInLog',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 102,
            'endLine' => 102,
            'startColumn' => 32,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'logNames' => 
          array (
            'name' => 'logNames',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 102,
            'endLine' => 102,
            'startColumn' => 48,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
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
        'startLine' => 102,
        'endLine' => 109,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'scopeCausedBy' => 
      array (
        'name' => 'scopeCausedBy',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'causer' => 
          array (
            'name' => 'causer',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 111,
            'endLine' => 111,
            'startColumn' => 51,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
        'startLine' => 111,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'scopeForSubject' => 
      array (
        'name' => 'scopeForSubject',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 37,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'subject' => 
          array (
            'name' => 'subject',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Model',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 118,
            'endLine' => 118,
            'startColumn' => 53,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
        'startLine' => 118,
        'endLine' => 123,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'scopeForEvent' => 
      array (
        'name' => 'scopeForEvent',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 125,
            'endLine' => 125,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'event' => 
          array (
            'name' => 'event',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 125,
            'endLine' => 125,
            'startColumn' => 51,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
        'startLine' => 125,
        'endLine' => 128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'scopeHasBatch' => 
      array (
        'name' => 'scopeHasBatch',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 130,
            'endLine' => 130,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
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
        'startLine' => 130,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'aliasName' => NULL,
      ),
      'scopeForBatch' => 
      array (
        'name' => 'scopeForBatch',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Database\\Eloquent\\Builder',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 135,
            'endLine' => 135,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'batchUuid' => 
          array (
            'name' => 'batchUuid',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 135,
            'endLine' => 135,
            'startColumn' => 51,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
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
        'startLine' => 135,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Activitylog\\Models',
        'declaringClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'implementingClassName' => 'Spatie\\Activitylog\\Models\\Activity',
        'currentClassName' => 'Spatie\\Activitylog\\Models\\Activity',
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