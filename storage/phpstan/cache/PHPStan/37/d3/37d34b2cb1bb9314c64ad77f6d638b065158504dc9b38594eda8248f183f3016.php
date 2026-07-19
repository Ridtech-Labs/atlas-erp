<?php declare(strict_types = 1);

// osfsl-/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-health/src/ResultStores/EloquentHealthResultStore.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Spatie\Health\ResultStores\EloquentHealthResultStore
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-5c268a0de23ca9ea4f66cf33cb71573eccb0f995f73b1d2dce4838dffb5f1f1d-8.3.29-6.70.0.3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'filename' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-health/src/ResultStores/EloquentHealthResultStore.php',
      ),
    ),
    'namespace' => 'Spatie\\Health\\ResultStores',
    'name' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
    'shortName' => 'EloquentHealthResultStore',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 81,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'Spatie\\Health\\ResultStores\\ResultStore',
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'determineHistoryItemModel' => 
      array (
        'name' => 'determineHistoryItemModel',
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
        'startLine' => 15,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Spatie\\Health\\ResultStores',
        'declaringClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'implementingClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'currentClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'aliasName' => NULL,
      ),
      'getHistoryItemInstance' => 
      array (
        'name' => 'getHistoryItemInstance',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/** @return HealthCheckResultHistoryItem|object */',
        'startLine' => 30,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Spatie\\Health\\ResultStores',
        'declaringClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'implementingClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'currentClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'aliasName' => NULL,
      ),
      'save' => 
      array (
        'name' => 'save',
        'parameters' => 
        array (
          'checkResults' => 
          array (
            'name' => 'checkResults',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Illuminate\\Support\\Collection',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 38,
            'endLine' => 38,
            'startColumn' => 26,
            'endColumn' => 49,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/** @param  Collection<int, Result>  $checkResults */',
        'startLine' => 38,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Health\\ResultStores',
        'declaringClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'implementingClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'currentClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'aliasName' => NULL,
      ),
      'latestResults' => 
      array (
        'name' => 'latestResults',
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
                  'name' => 'Spatie\\Health\\ResultStores\\StoredCheckResults\\StoredCheckResults',
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
        'startLine' => 55,
        'endLine' => 80,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Spatie\\Health\\ResultStores',
        'declaringClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'implementingClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
        'currentClassName' => 'Spatie\\Health\\ResultStores\\EloquentHealthResultStore',
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