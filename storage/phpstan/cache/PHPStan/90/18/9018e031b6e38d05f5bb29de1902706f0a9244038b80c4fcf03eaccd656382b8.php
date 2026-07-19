<?php declare(strict_types = 1);

// osfsl-/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../pestphp/pest/src/Expectations/OppositeExpectation.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Pest\Expectations\OppositeExpectation
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-9c3edf2f8029c55cf6a8c34e4c91a66d375a03dc8a5bf0e6c51a1d28bac4c9ac-8.3.29-6.70.0.3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Pest\\Expectations\\OppositeExpectation',
        'filename' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../pestphp/pest/src/Expectations/OppositeExpectation.php',
      ),
    ),
    'namespace' => 'Pest\\Expectations',
    'name' => 'Pest\\Expectations\\OppositeExpectation',
    'shortName' => 'OppositeExpectation',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 65568,
    'docComment' => '/**
 * @internal
 *
 * @template TValue
 *
 * @mixin Expectation<TValue>
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 36,
    'endLine' => 869,
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
      'original' => 
      array (
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'name' => 'original',
        'modifiers' => 4,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Expectation',
            'isIdentifier' => false,
          ),
        ),
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 33,
        'endColumn' => 61,
        'isPromoted' => true,
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
          'original' => 
          array (
            'name' => 'original',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'Pest\\Expectation',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 43,
            'endLine' => 43,
            'startColumn' => 33,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a new opposite expectation.
 *
 * @param  Expectation<TValue>  $original
 */',
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 65,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveKeys' => 
      array (
        'name' => 'toHaveKeys',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
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
            'startLine' => 51,
            'endLine' => 51,
            'startColumn' => 32,
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
            'name' => 'Pest\\Expectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the value array not has the provided $keys.
 *
 * @param  array<int, int|string|array<int-string, mixed>>  $keys
 * @return Expectation<TValue>
 */',
        'startLine' => 51,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toUse' => 
      array (
        'name' => 'toUse',
        'parameters' => 
        array (
          'targets' => 
          array (
            'name' => 'targets',
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
                      'name' => 'array',
                      'isIdentifier' => true,
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
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 75,
            'endLine' => 75,
            'startColumn' => 27,
            'endColumn' => 47,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target does not use any of the given dependencies.
 *
 * @param  array<int, string>|string  $targets
 */',
        'startLine' => 75,
        'endLine' => 83,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveFileSystemPermissions' => 
      array (
        'name' => 'toHaveFileSystemPermissions',
        'parameters' => 
        array (
          'permissions' => 
          array (
            'name' => 'permissions',
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
            'startLine' => 88,
            'endLine' => 88,
            'startColumn' => 49,
            'endColumn' => 67,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target does not have the given permissions
 */',
        'startLine' => 88,
        'endLine' => 99,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveLineCountLessThan' => 
      array (
        'name' => 'toHaveLineCountLessThan',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Not supported.
 */',
        'startLine' => 104,
        'endLine' => 107,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveMethodsDocumented' => 
      array (
        'name' => 'toHaveMethodsDocumented',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Not supported.
 */',
        'startLine' => 112,
        'endLine' => 129,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHavePropertiesDocumented' => 
      array (
        'name' => 'toHavePropertiesDocumented',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Not supported.
 */',
        'startLine' => 134,
        'endLine' => 152,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toUseStrictTypes' => 
      array (
        'name' => 'toUseStrictTypes',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target does not use the "declare(strict_types=1)" declaration.
 */',
        'startLine' => 157,
        'endLine' => 168,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toUseStrictEquality' => 
      array (
        'name' => 'toUseStrictEquality',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target does not use the strict equality operator.
 */',
        'startLine' => 173,
        'endLine' => 184,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeFinal' => 
      array (
        'name' => 'toBeFinal',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target is not final.
 */',
        'startLine' => 189,
        'endLine' => 200,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeReadonly' => 
      array (
        'name' => 'toBeReadonly',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target is not readonly.
 */',
        'startLine' => 205,
        'endLine' => 216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeTrait' => 
      array (
        'name' => 'toBeTrait',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target is not trait.
 */',
        'startLine' => 221,
        'endLine' => 232,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeTraits' => 
      array (
        'name' => 'toBeTraits',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation targets are not traits.
 */',
        'startLine' => 237,
        'endLine' => 240,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeAbstract' => 
      array (
        'name' => 'toBeAbstract',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target is not abstract.
 */',
        'startLine' => 245,
        'endLine' => 256,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveMethod' => 
      array (
        'name' => 'toHaveMethod',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
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
                      'name' => 'array',
                      'isIdentifier' => true,
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
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 263,
            'endLine' => 263,
            'startColumn' => 34,
            'endColumn' => 53,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target does not have a specific method.
 *
 * @param  array<int, string>|string  $method
 */',
        'startLine' => 263,
        'endLine' => 279,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveMethods' => 
      array (
        'name' => 'toHaveMethods',
        'parameters' => 
        array (
          'methods' => 
          array (
            'name' => 'methods',
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
            'startLine' => 286,
            'endLine' => 286,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target does not have the given methods.
 *
 * @param  array<int, string>  $methods
 */',
        'startLine' => 286,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHavePublicMethodsBesides' => 
      array (
        'name' => 'toHavePublicMethodsBesides',
        'parameters' => 
        array (
          'methods' => 
          array (
            'name' => 'methods',
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
                      'name' => 'array',
                      'isIdentifier' => true,
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
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 296,
            'endLine' => 296,
            'startColumn' => 48,
            'endColumn' => 68,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target not to have the public methods besides the given methods.
 *
 * @param  array<int, string>|string  $methods
 */',
        'startLine' => 296,
        'endLine' => 327,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHavePublicMethods' => 
      array (
        'name' => 'toHavePublicMethods',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target not to have the public methods.
 */',
        'startLine' => 332,
        'endLine' => 335,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveProtectedMethodsBesides' => 
      array (
        'name' => 'toHaveProtectedMethodsBesides',
        'parameters' => 
        array (
          'methods' => 
          array (
            'name' => 'methods',
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
                      'name' => 'array',
                      'isIdentifier' => true,
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
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 342,
            'endLine' => 342,
            'startColumn' => 51,
            'endColumn' => 71,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target not to have the protected methods besides the given methods.
 *
 * @param  array<int, string>|string  $methods
 */',
        'startLine' => 342,
        'endLine' => 373,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveProtectedMethods' => 
      array (
        'name' => 'toHaveProtectedMethods',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target not to have the protected methods.
 */',
        'startLine' => 378,
        'endLine' => 381,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHavePrivateMethodsBesides' => 
      array (
        'name' => 'toHavePrivateMethodsBesides',
        'parameters' => 
        array (
          'methods' => 
          array (
            'name' => 'methods',
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
                      'name' => 'array',
                      'isIdentifier' => true,
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
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 388,
            'endLine' => 388,
            'startColumn' => 49,
            'endColumn' => 69,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target not to have the private methods besides the given methods.
 *
 * @param  array<int, string>|string  $methods
 */',
        'startLine' => 388,
        'endLine' => 419,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHavePrivateMethods' => 
      array (
        'name' => 'toHavePrivateMethods',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target not to have the private methods.
 */',
        'startLine' => 424,
        'endLine' => 427,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeEnum' => 
      array (
        'name' => 'toBeEnum',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target is not enum.
 */',
        'startLine' => 432,
        'endLine' => 443,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeEnums' => 
      array (
        'name' => 'toBeEnums',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation targets are not enums.
 */',
        'startLine' => 448,
        'endLine' => 451,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeClass' => 
      array (
        'name' => 'toBeClass',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation targets is not class.
 */',
        'startLine' => 456,
        'endLine' => 467,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeClasses' => 
      array (
        'name' => 'toBeClasses',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation targets are not classes.
 */',
        'startLine' => 472,
        'endLine' => 475,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeInterface' => 
      array (
        'name' => 'toBeInterface',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target is not interface.
 */',
        'startLine' => 480,
        'endLine' => 491,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeInterfaces' => 
      array (
        'name' => 'toBeInterfaces',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation targets are not interfaces.
 */',
        'startLine' => 496,
        'endLine' => 499,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toExtend' => 
      array (
        'name' => 'toExtend',
        'parameters' => 
        array (
          'class' => 
          array (
            'name' => 'class',
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
            'startLine' => 504,
            'endLine' => 504,
            'startColumn' => 30,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target to be not subclass of the given class.
 */',
        'startLine' => 504,
        'endLine' => 515,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toExtendNothing' => 
      array (
        'name' => 'toExtendNothing',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target to be not have any parent class.
 */',
        'startLine' => 520,
        'endLine' => 531,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toUseTrait' => 
      array (
        'name' => 'toUseTrait',
        'parameters' => 
        array (
          'trait' => 
          array (
            'name' => 'trait',
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
            'startLine' => 536,
            'endLine' => 536,
            'startColumn' => 32,
            'endColumn' => 44,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target not to use the given trait.
 */',
        'startLine' => 536,
        'endLine' => 539,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toUseTraits' => 
      array (
        'name' => 'toUseTraits',
        'parameters' => 
        array (
          'traits' => 
          array (
            'name' => 'traits',
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
                      'name' => 'array',
                      'isIdentifier' => true,
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
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 546,
            'endLine' => 546,
            'startColumn' => 33,
            'endColumn' => 52,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target not to use the given traits.
 *
 * @param  array<int, string>|string  $traits
 */',
        'startLine' => 546,
        'endLine' => 567,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toImplement' => 
      array (
        'name' => 'toImplement',
        'parameters' => 
        array (
          'interfaces' => 
          array (
            'name' => 'interfaces',
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
                      'name' => 'array',
                      'isIdentifier' => true,
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
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 574,
            'endLine' => 574,
            'startColumn' => 33,
            'endColumn' => 56,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target not to implement the given interfaces.
 *
 * @param  array<int, string>|string  $interfaces
 */',
        'startLine' => 574,
        'endLine' => 595,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toImplementNothing' => 
      array (
        'name' => 'toImplementNothing',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target to not implement any interfaces.
 */',
        'startLine' => 600,
        'endLine' => 611,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toOnlyImplement' => 
      array (
        'name' => 'toOnlyImplement',
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
        'docComment' => '/**
 * Not supported.
 */',
        'startLine' => 616,
        'endLine' => 619,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHavePrefix' => 
      array (
        'name' => 'toHavePrefix',
        'parameters' => 
        array (
          'prefix' => 
          array (
            'name' => 'prefix',
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
            'startLine' => 624,
            'endLine' => 624,
            'startColumn' => 34,
            'endColumn' => 47,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target to not have the given prefix.
 */',
        'startLine' => 624,
        'endLine' => 635,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveSuffix' => 
      array (
        'name' => 'toHaveSuffix',
        'parameters' => 
        array (
          'suffix' => 
          array (
            'name' => 'suffix',
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
            'startLine' => 640,
            'endLine' => 640,
            'startColumn' => 34,
            'endColumn' => 47,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target to not have the given suffix.
 */',
        'startLine' => 640,
        'endLine' => 651,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toOnlyUse' => 
      array (
        'name' => 'toOnlyUse',
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
        'docComment' => '/**
 * Not supported.
 */',
        'startLine' => 656,
        'endLine' => 659,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toUseNothing' => 
      array (
        'name' => 'toUseNothing',
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
        'docComment' => '/**
 * Not supported.
 */',
        'startLine' => 664,
        'endLine' => 667,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeUsed' => 
      array (
        'name' => 'toBeUsed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation dependency is not used.
 */',
        'startLine' => 672,
        'endLine' => 678,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeUsedIn' => 
      array (
        'name' => 'toBeUsedIn',
        'parameters' => 
        array (
          'targets' => 
          array (
            'name' => 'targets',
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
                      'name' => 'array',
                      'isIdentifier' => true,
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
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 685,
            'endLine' => 685,
            'startColumn' => 32,
            'endColumn' => 52,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation dependency is not used by any of the given targets.
 *
 * @param  array<int, string>|string  $targets
 */',
        'startLine' => 685,
        'endLine' => 693,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toOnlyBeUsedIn' => 
      array (
        'name' => 'toOnlyBeUsedIn',
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
        'startLine' => 695,
        'endLine' => 698,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeUsedInNothing' => 
      array (
        'name' => 'toBeUsedInNothing',
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
        'docComment' => '/**
 * Asserts that the given expectation dependency is not used.
 */',
        'startLine' => 703,
        'endLine' => 706,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeInvokable' => 
      array (
        'name' => 'toBeInvokable',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation dependency is not an invokable class.
 */',
        'startLine' => 711,
        'endLine' => 722,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveAttribute' => 
      array (
        'name' => 'toHaveAttribute',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
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
            'startLine' => 727,
            'endLine' => 727,
            'startColumn' => 37,
            'endColumn' => 53,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target not to have the given attribute.
 */',
        'startLine' => 727,
        'endLine' => 738,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 746,
            'endLine' => 746,
            'startColumn' => 28,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
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
            'startLine' => 746,
            'endLine' => 746,
            'startColumn' => 42,
            'endColumn' => 57,
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
            'name' => 'Pest\\Expectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Handle dynamic method calls into the original expectation.
 *
 * @param  array<int, mixed>  $arguments
 * @return Expectation<TValue>|Expectation<mixed>|never
 */',
        'startLine' => 746,
        'endLine' => 760,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      '__get' => 
      array (
        'name' => '__get',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 767,
            'endLine' => 767,
            'startColumn' => 27,
            'endColumn' => 38,
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
            'name' => 'Pest\\Expectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Handle dynamic properties gets into the original expectation.
 *
 * @return Expectation<TValue>|Expectation<mixed>|never
 */',
        'startLine' => 767,
        'endLine' => 780,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'throwExpectationFailedException' => 
      array (
        'name' => 'throwExpectationFailedException',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
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
            'startLine' => 787,
            'endLine' => 787,
            'startColumn' => 53,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 787,
                'endLine' => 787,
                'startTokenPos' => 4882,
                'startFilePos' => 28096,
                'endTokenPos' => 4883,
                'endFilePos' => 28097,
              ),
            ),
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
                      'name' => 'string',
                      'isIdentifier' => true,
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
            'startLine' => 787,
            'endLine' => 787,
            'startColumn' => 67,
            'endColumn' => 94,
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
            'name' => 'never',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Creates a new expectation failed exception with a nice readable message.
 *
 * @param  array<int, mixed>|string  $arguments
 */',
        'startLine' => 787,
        'endLine' => 801,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveConstructor' => 
      array (
        'name' => 'toHaveConstructor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target does not have a constructor method.
 */',
        'startLine' => 806,
        'endLine' => 809,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toHaveDestructor' => 
      array (
        'name' => 'toHaveDestructor',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target does not have a destructor method.
 */',
        'startLine' => 814,
        'endLine' => 817,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeBackedEnum' => 
      array (
        'name' => 'toBeBackedEnum',
        'parameters' => 
        array (
          'backingType' => 
          array (
            'name' => 'backingType',
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
            'startLine' => 822,
            'endLine' => 822,
            'startColumn' => 37,
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
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target is not a backed enum of given type.
 */',
        'startLine' => 822,
        'endLine' => 836,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeStringBackedEnums' => 
      array (
        'name' => 'toBeStringBackedEnums',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation targets are not string backed enums.
 */',
        'startLine' => 841,
        'endLine' => 844,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeIntBackedEnums' => 
      array (
        'name' => 'toBeIntBackedEnums',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation targets are not int backed enums.
 */',
        'startLine' => 849,
        'endLine' => 852,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeStringBackedEnum' => 
      array (
        'name' => 'toBeStringBackedEnum',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target is not a string backed enum.
 */',
        'startLine' => 857,
        'endLine' => 860,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'aliasName' => NULL,
      ),
      'toBeIntBackedEnum' => 
      array (
        'name' => 'toBeIntBackedEnum',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Pest\\Arch\\Contracts\\ArchExpectation',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the given expectation target is not an int backed enum.
 */',
        'startLine' => 865,
        'endLine' => 868,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Pest\\Expectations',
        'declaringClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'implementingClassName' => 'Pest\\Expectations\\OppositeExpectation',
        'currentClassName' => 'Pest\\Expectations\\OppositeExpectation',
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