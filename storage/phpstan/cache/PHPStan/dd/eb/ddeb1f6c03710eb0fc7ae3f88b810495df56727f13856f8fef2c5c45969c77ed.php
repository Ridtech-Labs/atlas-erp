<?php declare(strict_types = 1);

// odsl-/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Core\Tenancy\Models\Tenant
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.3-8.3.29-17d523b2b445d5cd361c1dec5010f18384cb003b1a90a491241fb1a93153108d',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'filename' => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php',
      ),
    ),
    'namespace' => 'App\\Core\\Tenancy\\Models',
    'name' => 'App\\Core\\Tenancy\\Models\\Tenant',
    'shortName' => 'Tenant',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 100,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\MediaLibrary\\HasMedia',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
      2 => 'Spatie\\MediaLibrary\\InteractsWithMedia',
      3 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
      4 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'implementingClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'uuid\', \'name\', \'slug\', \'email\', \'phone\', \'timezone\', \'currency\', \'status\', \'logo_path\', \'address\', \'city\', \'country\']',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 45,
            'startTokenPos' => 132,
            'startFilePos' => 874,
            'endTokenPos' => 170,
            'endFilePos' => 1095,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'appends' => 
      array (
        'declaringClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'implementingClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'name' => 'appends',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'is_active\']',
          'attributes' => 
          array (
            'startLine' => 47,
            'endLine' => 49,
            'startTokenPos' => 179,
            'startFilePos' => 1124,
            'endTokenPos' => 184,
            'endFilePos' => 1151,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 49,
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
      'casts' => 
      array (
        'name' => 'casts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 51,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'App\\Core\\Tenancy\\Models',
        'declaringClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'implementingClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'currentClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'aliasName' => NULL,
      ),
      'newFactory' => 
      array (
        'name' => 'newFactory',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Database\\Factories\\TenantFactory',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 58,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 18,
        'namespace' => 'App\\Core\\Tenancy\\Models',
        'declaringClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'implementingClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'currentClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'aliasName' => NULL,
      ),
      'users' => 
      array (
        'name' => 'users',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return HasMany<User, $this>
 */',
        'startLine' => 66,
        'endLine' => 69,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Core\\Tenancy\\Models',
        'declaringClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'implementingClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'currentClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'aliasName' => NULL,
      ),
      'settings' => 
      array (
        'name' => 'settings',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return HasMany<Setting, $this>
 */',
        'startLine' => 74,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Core\\Tenancy\\Models',
        'declaringClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'implementingClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'currentClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'aliasName' => NULL,
      ),
      'getActivitylogOptions' => 
      array (
        'name' => 'getActivitylogOptions',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Spatie\\Activitylog\\LogOptions',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 79,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Core\\Tenancy\\Models',
        'declaringClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'implementingClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'currentClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'aliasName' => NULL,
      ),
      'tapActivity' => 
      array (
        'name' => 'tapActivity',
        'parameters' => 
        array (
          'activity' => 
          array (
            'name' => 'activity',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Spatie\\Activitylog\\Models\\Activity',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 33,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'eventName' => 
          array (
            'name' => 'eventName',
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
            'startLine' => 87,
            'endLine' => 87,
            'startColumn' => 53,
            'endColumn' => 69,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 87,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Core\\Tenancy\\Models',
        'declaringClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'implementingClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'currentClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'aliasName' => NULL,
      ),
      'getIsActiveAttribute' => 
      array (
        'name' => 'getIsActiveAttribute',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 96,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Core\\Tenancy\\Models',
        'declaringClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'implementingClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
        'currentClassName' => 'App\\Core\\Tenancy\\Models\\Tenant',
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