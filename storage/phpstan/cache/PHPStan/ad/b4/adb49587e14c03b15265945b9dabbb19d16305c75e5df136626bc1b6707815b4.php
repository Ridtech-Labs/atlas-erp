<?php declare(strict_types = 1);

// osfsl-/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/filament/src/Resources/Pages/CreateRecord.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Filament\Resources\Pages\CreateRecord
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7e81fe37e01dbcc69db6905c3749f2be2c904f220d81bb37052c4a5e7b0525c9-8.3.29-6.70.0.3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Filament\\Resources\\Pages\\CreateRecord',
        'filename' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/filament/src/Resources/Pages/CreateRecord.php',
      ),
    ),
    'namespace' => 'Filament\\Resources\\Pages',
    'name' => 'Filament\\Resources\\Pages\\CreateRecord',
    'shortName' => 'CreateRecord',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @template TModel of Model = Model
 *
 * @property-read Schema $form
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 33,
    'endLine' => 439,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Filament\\Resources\\Pages\\Page',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Filament\\Pages\\Concerns\\CanUseDatabaseTransactions',
      1 => 'Filament\\Pages\\Concerns\\HasUnsavedDataChangesAlert',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'record' => 
      array (
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'name' => 'record',
        'modifiers' => 1,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'Illuminate\\Database\\Eloquent\\Model',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 150,
            'startFilePos' => 1132,
            'endTokenPos' => 150,
            'endFilePos' => 1135,
          ),
        ),
        'docComment' => '/** @var ?TModel */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'data' => 
      array (
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'name' => 'data',
        'modifiers' => 1,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'array',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 44,
            'endLine' => 44,
            'startTokenPos' => 164,
            'startFilePos' => 1221,
            'endTokenPos' => 165,
            'endFilePos' => 1222,
          ),
        ),
        'docComment' => '/**
 * @var array<string, mixed> | null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 44,
        'endLine' => 44,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'previousUrl' => 
      array (
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'name' => 'previousUrl',
        'modifiers' => 1,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 46,
            'startTokenPos' => 177,
            'startFilePos' => 1260,
            'endTokenPos' => 177,
            'endFilePos' => 1263,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'canCreateAnother' => 
      array (
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'name' => 'canCreateAnother',
        'modifiers' => 18,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 48,
            'endLine' => 48,
            'startTokenPos' => 190,
            'startFilePos' => 1313,
            'endTokenPos' => 190,
            'endFilePos' => 1316,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 48,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 51,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isCreating' => 
      array (
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'name' => 'isCreating',
        'modifiers' => 1,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 205,
            'startFilePos' => 1364,
            'endTokenPos' => 205,
            'endFilePos' => 1368,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'Livewire\\Attributes\\Locked',
            'isRepeated' => false,
            'arguments' => 
            array (
            ),
          ),
        ),
        'startLine' => 50,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 36,
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
      'getBreadcrumb' => 
      array (
        'name' => 'getBreadcrumb',
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
        'startLine' => 53,
        'endLine' => 56,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'mount' => 
      array (
        'name' => 'mount',
        'parameters' => 
        array (
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
        'startLine' => 58,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'authorizeAccess' => 
      array (
        'name' => 'authorizeAccess',
        'parameters' => 
        array (
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
        'startLine' => 67,
        'endLine' => 70,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'hydrate' => 
      array (
        'name' => 'hydrate',
        'parameters' => 
        array (
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
        'startLine' => 72,
        'endLine' => 75,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'fillForm' => 
      array (
        'name' => 'fillForm',
        'parameters' => 
        array (
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
        'startLine' => 77,
        'endLine' => 84,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'create' => 
      array (
        'name' => 'create',
        'parameters' => 
        array (
          'another' => 
          array (
            'name' => 'another',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 86,
                'endLine' => 86,
                'startTokenPos' => 387,
                'startFilePos' => 2100,
                'endTokenPos' => 387,
                'endFilePos' => 2104,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 86,
            'endLine' => 86,
            'startColumn' => 28,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
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
        'startLine' => 86,
        'endLine' => 167,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'preserveFormDataWhenCreatingAnother' => 
      array (
        'name' => 'preserveFormDataWhenCreatingAnother',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
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
            'startLine' => 173,
            'endLine' => 173,
            'startColumn' => 60,
            'endColumn' => 70,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, mixed>  $data
 * @return array<string, mixed>
 */',
        'startLine' => 173,
        'endLine' => 176,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getCreatedNotification' => 
      array (
        'name' => 'getCreatedNotification',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'Filament\\Notifications\\Notification',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 178,
        'endLine' => 189,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getCreatedNotificationTitle' => 
      array (
        'name' => 'getCreatedNotificationTitle',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 191,
        'endLine' => 194,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getCreatedNotificationMessage' => 
      array (
        'name' => 'getCreatedNotificationMessage',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated Use `getCreatedNotificationTitle()` instead.
 */',
        'startLine' => 199,
        'endLine' => 202,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'createAnother' => 
      array (
        'name' => 'createAnother',
        'parameters' => 
        array (
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
        'startLine' => 204,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'handleRecordCreation' => 
      array (
        'name' => 'handleRecordCreation',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
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
            'startLine' => 213,
            'endLine' => 213,
            'startColumn' => 45,
            'endColumn' => 55,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Model',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, mixed>  $data
 * @return TModel
 */',
        'startLine' => 213,
        'endLine' => 224,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'associateRecordWithParent' => 
      array (
        'name' => 'associateRecordWithParent',
        'parameters' => 
        array (
          'record' => 
          array (
            'name' => 'record',
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
            'startLine' => 226,
            'endLine' => 226,
            'startColumn' => 50,
            'endColumn' => 62,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parent' => 
          array (
            'name' => 'parent',
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
            'startLine' => 226,
            'endLine' => 226,
            'startColumn' => 65,
            'endColumn' => 77,
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
            'name' => 'Illuminate\\Database\\Eloquent\\Model',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 226,
        'endLine' => 229,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'mutateFormDataBeforeCreate' => 
      array (
        'name' => 'mutateFormDataBeforeCreate',
        'parameters' => 
        array (
          'data' => 
          array (
            'name' => 'data',
            'default' => NULL,
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
            'startLine' => 235,
            'endLine' => 235,
            'startColumn' => 51,
            'endColumn' => 61,
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
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  array<string, mixed>  $data
 * @return array<string, mixed>
 */',
        'startLine' => 235,
        'endLine' => 238,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getFormActions' => 
      array (
        'name' => 'getFormActions',
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
        'docComment' => '/**
 * @return array<Action | ActionGroup>
 */',
        'startLine' => 243,
        'endLine' => 250,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getCreateFormAction' => 
      array (
        'name' => 'getCreateFormAction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Filament\\Actions\\Action',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 252,
        'endLine' => 261,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getSubmitFormAction' => 
      array (
        'name' => 'getSubmitFormAction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Filament\\Actions\\Action',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 263,
        'endLine' => 266,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getSubmitFormLivewireMethodName' => 
      array (
        'name' => 'getSubmitFormLivewireMethodName',
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
        'startLine' => 268,
        'endLine' => 271,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getCreateAnotherFormAction' => 
      array (
        'name' => 'getCreateAnotherFormAction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Filament\\Actions\\Action',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 273,
        'endLine' => 280,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getCancelFormAction' => 
      array (
        'name' => 'getCancelFormAction',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Filament\\Actions\\Action',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 282,
        'endLine' => 294,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getTitle' => 
      array (
        'name' => 'getTitle',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'Illuminate\\Contracts\\Support\\Htmlable',
                  'isIdentifier' => false,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 296,
        'endLine' => 305,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'defaultForm' => 
      array (
        'name' => 'defaultForm',
        'parameters' => 
        array (
          'schema' => 
          array (
            'name' => 'schema',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Filament\\Schemas\\Schema',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 307,
            'endLine' => 307,
            'startColumn' => 33,
            'endColumn' => 46,
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
            'name' => 'Filament\\Schemas\\Schema',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 307,
        'endLine' => 318,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'form' => 
      array (
        'name' => 'form',
        'parameters' => 
        array (
          'schema' => 
          array (
            'name' => 'schema',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Filament\\Schemas\\Schema',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 320,
            'endLine' => 320,
            'startColumn' => 26,
            'endColumn' => 39,
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
            'name' => 'Filament\\Schemas\\Schema',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 320,
        'endLine' => 323,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getRedirectUrl' => 
      array (
        'name' => 'getRedirectUrl',
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
        'startLine' => 325,
        'endLine' => 349,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getRedirectUrlParameters' => 
      array (
        'name' => 'getRedirectUrlParameters',
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
        'docComment' => '/**
 * @return array<string, mixed>
 */',
        'startLine' => 354,
        'endLine' => 357,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getMountedActionSchemaModel' => 
      array (
        'name' => 'getMountedActionSchemaModel',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'Illuminate\\Database\\Eloquent\\Model',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'string',
                  'isIdentifier' => true,
                ),
              ),
              2 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return TModel|class-string<TModel>|null
 */',
        'startLine' => 362,
        'endLine' => 365,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'canCreateAnother' => 
      array (
        'name' => 'canCreateAnother',
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
        'startLine' => 367,
        'endLine' => 370,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'disableCreateAnother' => 
      array (
        'name' => 'disableCreateAnother',
        'parameters' => 
        array (
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
        'startLine' => 372,
        'endLine' => 375,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getRecord' => 
      array (
        'name' => 'getRecord',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
          'data' => 
          array (
            'types' => 
            array (
              0 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'Illuminate\\Database\\Eloquent\\Model',
                  'isIdentifier' => false,
                ),
              ),
              1 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'null',
                  'isIdentifier' => true,
                ),
              ),
            ),
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return ?TModel
 */',
        'startLine' => 380,
        'endLine' => 383,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'content' => 
      array (
        'name' => 'content',
        'parameters' => 
        array (
          'schema' => 
          array (
            'name' => 'schema',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Filament\\Schemas\\Schema',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 385,
            'endLine' => 385,
            'startColumn' => 29,
            'endColumn' => 42,
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
            'name' => 'Filament\\Schemas\\Schema',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 385,
        'endLine' => 391,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getFormContentComponent' => 
      array (
        'name' => 'getFormContentComponent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Filament\\Schemas\\Components\\Component',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 393,
        'endLine' => 408,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getFormActionsContentComponent' => 
      array (
        'name' => 'getFormActionsContentComponent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Filament\\Schemas\\Components\\Component',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 410,
        'endLine' => 417,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'hasFormWrapper' => 
      array (
        'name' => 'hasFormWrapper',
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
        'startLine' => 419,
        'endLine' => 422,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'getPageClasses' => 
      array (
        'name' => 'getPageClasses',
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
        'docComment' => '/**
 * @return array<string>
 */',
        'startLine' => 427,
        'endLine' => 433,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'aliasName' => NULL,
      ),
      'hasFullWidthFormActions' => 
      array (
        'name' => 'hasFullWidthFormActions',
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
        'startLine' => 435,
        'endLine' => 438,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages',
        'declaringClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'implementingClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
        'currentClassName' => 'Filament\\Resources\\Pages\\CreateRecord',
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