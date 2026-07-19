<?php declare(strict_types = 1);

// osfsl-/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/filament/src/Resources/Pages/Concerns/HasRelationManagers.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Filament\Resources\Pages\Concerns\HasRelationManagers
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-1bf5bdd8ff4a1d2ad52b0eac26762a5765618dbdd3df6da255ded2f754727186-8.3.29-6.70.0.3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'filename' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/filament/src/Resources/Pages/Concerns/HasRelationManagers.php',
      ),
    ),
    'namespace' => 'Filament\\Resources\\Pages\\Concerns',
    'name' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
    'shortName' => 'HasRelationManagers',
    'isInterface' => false,
    'isTrait' => true,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 207,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'activeRelationManager' => 
      array (
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'name' => 'activeRelationManager',
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
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 97,
            'startFilePos' => 710,
            'endTokenPos' => 97,
            'endFilePos' => 713,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
          0 => 
          array (
            'name' => 'Livewire\\Attributes\\Url',
            'isRepeated' => false,
            'arguments' => 
            array (
              'as' => 
              array (
                'code' => '\'relation\'',
                'attributes' => 
                array (
                  'startLine' => 21,
                  'endLine' => 21,
                  'startTokenPos' => 84,
                  'startFilePos' => 653,
                  'endTokenPos' => 84,
                  'endFilePos' => 662,
                ),
              ),
            ),
          ),
        ),
        'startLine' => 21,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 49,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'cachedRelationManagers' => 
      array (
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'name' => 'cachedRelationManagers',
        'modifiers' => 2,
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
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 111,
            'startFilePos' => 883,
            'endTokenPos' => 111,
            'endFilePos' => 886,
          ),
        ),
        'docComment' => '/**
 * @var array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration> | null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 52,
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
      'getAllRelationManagers' => 
      array (
        'name' => 'getAllRelationManagers',
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
 * @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration>
 */',
        'startLine' => 32,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'aliasName' => NULL,
      ),
      'getCachedRelationManagers' => 
      array (
        'name' => 'getCachedRelationManagers',
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
 * @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration>
 */',
        'startLine' => 40,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'aliasName' => NULL,
      ),
      'getRelationManagers' => 
      array (
        'name' => 'getRelationManagers',
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
 * @return array<class-string<RelationManager> | RelationGroup | RelationManagerConfiguration>
 */',
        'startLine' => 52,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'aliasName' => NULL,
      ),
      'normalizeRelationManagerClass' => 
      array (
        'name' => 'normalizeRelationManagerClass',
        'parameters' => 
        array (
          'manager' => 
          array (
            'name' => 'manager',
            'default' => NULL,
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
                      'name' => 'Filament\\Resources\\RelationManagers\\RelationManagerConfiguration',
                      'isIdentifier' => false,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 72,
            'endLine' => 72,
            'startColumn' => 54,
            'endColumn' => 99,
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
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param  class-string<RelationManager> | RelationManagerConfiguration  $manager
 * @return class-string<RelationManager>
 */',
        'startLine' => 72,
        'endLine' => 79,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'aliasName' => NULL,
      ),
      'renderingHasRelationManagers' => 
      array (
        'name' => 'renderingHasRelationManagers',
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
        'startLine' => 81,
        'endLine' => 94,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'aliasName' => NULL,
      ),
      'hasCombinedRelationManagerTabsWithContent' => 
      array (
        'name' => 'hasCombinedRelationManagerTabsWithContent',
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
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'aliasName' => NULL,
      ),
      'getContentTabComponent' => 
      array (
        'name' => 'getContentTabComponent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Filament\\Schemas\\Components\\Tabs\\Tab',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 101,
        'endLine' => 105,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'aliasName' => NULL,
      ),
      'getContentTabLabel' => 
      array (
        'name' => 'getContentTabLabel',
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
        'startLine' => 107,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'aliasName' => NULL,
      ),
      'getContentTabIcon' => 
      array (
        'name' => 'getContentTabIcon',
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
                  'name' => 'BackedEnum',
                  'isIdentifier' => false,
                ),
              ),
              2 => 
              array (
                'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                'data' => 
                array (
                  'name' => 'Illuminate\\Contracts\\Support\\Htmlable',
                  'isIdentifier' => false,
                ),
              ),
              3 => 
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
        'startLine' => 112,
        'endLine' => 115,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'aliasName' => NULL,
      ),
      'getContentTabPosition' => 
      array (
        'name' => 'getContentTabPosition',
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
                  'name' => 'Filament\\Resources\\Pages\\Enums\\ContentTabPosition',
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
        'startLine' => 117,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'aliasName' => NULL,
      ),
      'getRelationManagersContentComponent' => 
      array (
        'name' => 'getRelationManagersContentComponent',
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
        'startLine' => 122,
        'endLine' => 206,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Filament\\Resources\\Pages\\Concerns',
        'declaringClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'implementingClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
        'currentClassName' => 'Filament\\Resources\\Pages\\Concerns\\HasRelationManagers',
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