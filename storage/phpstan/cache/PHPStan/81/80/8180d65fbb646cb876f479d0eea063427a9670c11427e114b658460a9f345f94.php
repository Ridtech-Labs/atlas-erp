<?php declare(strict_types = 1);

// osfsl-/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../laravel/framework/src/Illuminate/Testing/TestResponse.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Illuminate\Testing\TestResponse
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-65c3b0e60585a8e451ead46e1a39aaa26e1ec705fc188ef8c88dbcb365997a2b-8.3.29-6.70.0.3',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Illuminate\\Testing\\TestResponse',
        'filename' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../laravel/framework/src/Illuminate/Testing/TestResponse.php',
      ),
    ),
    'namespace' => 'Illuminate\\Testing',
    'name' => 'Illuminate\\Testing\\TestResponse',
    'shortName' => 'TestResponse',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @template TResponse of \\Symfony\\Component\\HttpFoundation\\Response
 *
 * @mixin \\Illuminate\\Http\\Response
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 36,
    'endLine' => 2019,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
      0 => 'ArrayAccess',
    ),
    'traitClassNames' => 
    array (
      0 => 'Illuminate\\Testing\\Concerns\\AssertsStatusCodes',
      1 => 'Illuminate\\Support\\Traits\\Conditionable',
      2 => 'Illuminate\\Support\\Traits\\Dumpable',
      3 => 'Illuminate\\Support\\Traits\\Tappable',
      4 => 'Illuminate\\Support\\Traits\\Macroable',
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'baseRequest' => 
      array (
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'name' => 'baseRequest',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The original request.
 *
 * @var \\Illuminate\\Http\\Request|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 47,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'baseResponse' => 
      array (
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'name' => 'baseResponse',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The response to delegate to.
 *
 * @var TResponse
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 25,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'exceptions' => 
      array (
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'name' => 'exceptions',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The collection of logged exceptions for the request.
 *
 * @var \\Illuminate\\Support\\Collection
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 61,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'streamedContent' => 
      array (
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'name' => 'streamedContent',
        'modifiers' => 2,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The streamed content of the response.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 68,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 31,
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
          'response' => 
          array (
            'name' => 'response',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 76,
            'endLine' => 76,
            'startColumn' => 33,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'request' => 
          array (
            'name' => 'request',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 76,
                'endLine' => 76,
                'startTokenPos' => 222,
                'startFilePos' => 2026,
                'endTokenPos' => 222,
                'endFilePos' => 2029,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 76,
            'endLine' => 76,
            'startColumn' => 44,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new test response instance.
 *
 * @param  TResponse  $response
 * @param  \\Illuminate\\Http\\Request|null  $request
 */',
        'startLine' => 76,
        'endLine' => 81,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'fromBaseResponse' => 
      array (
        'name' => 'fromBaseResponse',
        'parameters' => 
        array (
          'response' => 
          array (
            'name' => 'response',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 45,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'request' => 
          array (
            'name' => 'request',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 92,
                'endLine' => 92,
                'startTokenPos' => 275,
                'startFilePos' => 2461,
                'endTokenPos' => 275,
                'endFilePos' => 2464,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 92,
            'endLine' => 92,
            'startColumn' => 56,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Create a new TestResponse from another response.
 *
 * @template R of TResponse
 *
 * @param  R  $response
 * @param  \\Illuminate\\Http\\Request|null  $request
 * @return static<R>
 */',
        'startLine' => 92,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSuccessful' => 
      array (
        'name' => 'assertSuccessful',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response has a successful status code.
 *
 * @return $this
 */',
        'startLine' => 102,
        'endLine' => 110,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSuccessfulPrecognition' => 
      array (
        'name' => 'assertSuccessfulPrecognition',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the Precognition request was successful.
 *
 * @return $this
 */',
        'startLine' => 117,
        'endLine' => 133,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertClientError' => 
      array (
        'name' => 'assertClientError',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response is a client error.
 *
 * @return $this
 */',
        'startLine' => 140,
        'endLine' => 148,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertServerError' => 
      array (
        'name' => 'assertServerError',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response is a server error.
 *
 * @return $this
 */',
        'startLine' => 155,
        'endLine' => 163,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertStatus' => 
      array (
        'name' => 'assertStatus',
        'parameters' => 
        array (
          'status' => 
          array (
            'name' => 'status',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 171,
            'endLine' => 171,
            'startColumn' => 34,
            'endColumn' => 40,
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
 * Assert that the response has the given status code.
 *
 * @param  int  $status
 * @return $this
 */',
        'startLine' => 171,
        'endLine' => 178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'statusMessageWithDetails' => 
      array (
        'name' => 'statusMessageWithDetails',
        'parameters' => 
        array (
          'expected' => 
          array (
            'name' => 'expected',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 187,
            'endLine' => 187,
            'startColumn' => 49,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'actual' => 
          array (
            'name' => 'actual',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 187,
            'endLine' => 187,
            'startColumn' => 60,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an assertion message for a status assertion containing extra details when available.
 *
 * @param  string|int  $expected
 * @param  string|int  $actual
 * @return string
 */',
        'startLine' => 187,
        'endLine' => 190,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertRedirect' => 
      array (
        'name' => 'assertRedirect',
        'parameters' => 
        array (
          'uri' => 
          array (
            'name' => 'uri',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 198,
                'endLine' => 198,
                'startTokenPos' => 640,
                'startFilePos' => 5178,
                'endTokenPos' => 640,
                'endFilePos' => 5181,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 198,
            'endLine' => 198,
            'startColumn' => 36,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert whether the response is redirecting to a given URI.
 *
 * @param  string|null  $uri
 * @return $this
 */',
        'startLine' => 198,
        'endLine' => 210,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertRedirectContains' => 
      array (
        'name' => 'assertRedirectContains',
        'parameters' => 
        array (
          'uri' => 
          array (
            'name' => 'uri',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 218,
            'endLine' => 218,
            'startColumn' => 44,
            'endColumn' => 47,
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
 * Assert whether the response is redirecting to a URI that contains the given URI.
 *
 * @param  string  $uri
 * @return $this
 */',
        'startLine' => 218,
        'endLine' => 230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertRedirectBack' => 
      array (
        'name' => 'assertRedirectBack',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert whether the response is redirecting back to the previous location.
 *
 * @return $this
 */',
        'startLine' => 237,
        'endLine' => 247,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertRedirectBackWithErrors' => 
      array (
        'name' => 'assertRedirectBackWithErrors',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 257,
                'endLine' => 257,
                'startTokenPos' => 894,
                'startFilePos' => 6923,
                'endTokenPos' => 895,
                'endFilePos' => 6924,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
            'startColumn' => 50,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'format' => 
          array (
            'name' => 'format',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 257,
                'endLine' => 257,
                'startTokenPos' => 902,
                'startFilePos' => 6937,
                'endTokenPos' => 902,
                'endFilePos' => 6940,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
            'startColumn' => 62,
            'endColumn' => 75,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'errorBag' => 
          array (
            'name' => 'errorBag',
            'default' => 
            array (
              'code' => '\'default\'',
              'attributes' => 
              array (
                'startLine' => 257,
                'endLine' => 257,
                'startTokenPos' => 909,
                'startFilePos' => 6955,
                'endTokenPos' => 909,
                'endFilePos' => 6963,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 257,
            'endLine' => 257,
            'startColumn' => 78,
            'endColumn' => 98,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert whether the response is redirecting back to the previous location with the given errors in the session.
 *
 * @param  string|array  $keys
 * @param  mixed  $format
 * @param  string  $errorBag
 * @return $this
 */',
        'startLine' => 257,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertRedirectBackWithoutErrors' => 
      array (
        'name' => 'assertRedirectBackWithoutErrors',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert whether the response is redirecting back to the previous location with no errors in the session.
 *
 * @return $this
 */',
        'startLine' => 271,
        'endLine' => 278,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertRedirectToRoute' => 
      array (
        'name' => 'assertRedirectToRoute',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 287,
            'endLine' => 287,
            'startColumn' => 43,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 287,
                'endLine' => 287,
                'startTokenPos' => 990,
                'startFilePos' => 7682,
                'endTokenPos' => 991,
                'endFilePos' => 7683,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 287,
            'endLine' => 287,
            'startColumn' => 50,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert whether the response is redirecting to a given route.
 *
 * @param  \\BackedEnum|string  $name
 * @param  mixed  $parameters
 * @return $this
 */',
        'startLine' => 287,
        'endLine' => 299,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertRedirectToSignedRoute' => 
      array (
        'name' => 'assertRedirectToSignedRoute',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 309,
                'endLine' => 309,
                'startTokenPos' => 1071,
                'startFilePos' => 8284,
                'endTokenPos' => 1071,
                'endFilePos' => 8287,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 309,
            'endLine' => 309,
            'startColumn' => 49,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 309,
                'endLine' => 309,
                'startTokenPos' => 1078,
                'startFilePos' => 8304,
                'endTokenPos' => 1079,
                'endFilePos' => 8305,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 309,
            'endLine' => 309,
            'startColumn' => 63,
            'endColumn' => 78,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'absolute' => 
          array (
            'name' => 'absolute',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 309,
                'endLine' => 309,
                'startTokenPos' => 1086,
                'startFilePos' => 8320,
                'endTokenPos' => 1086,
                'endFilePos' => 8323,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 309,
            'endLine' => 309,
            'startColumn' => 81,
            'endColumn' => 96,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert whether the response is redirecting to a given signed route.
 *
 * @param  \\BackedEnum|string|null  $name
 * @param  mixed  $parameters
 * @param  bool  $absolute
 * @return $this
 */',
        'startLine' => 309,
        'endLine' => 338,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertRedirectToAction' => 
      array (
        'name' => 'assertRedirectToAction',
        'parameters' => 
        array (
          'name' => 
          array (
            'name' => 'name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 347,
            'endLine' => 347,
            'startColumn' => 44,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'parameters' => 
          array (
            'name' => 'parameters',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 347,
                'endLine' => 347,
                'startTokenPos' => 1293,
                'startFilePos' => 9461,
                'endTokenPos' => 1294,
                'endFilePos' => 9462,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 347,
            'endLine' => 347,
            'startColumn' => 51,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert whether the response is redirecting to a given controller action.
 *
 * @param  string|array  $name
 * @param  array  $parameters
 * @return $this
 */',
        'startLine' => 347,
        'endLine' => 359,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertHeader' => 
      array (
        'name' => 'assertHeader',
        'parameters' => 
        array (
          'headerName' => 
          array (
            'name' => 'headerName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 368,
            'endLine' => 368,
            'startColumn' => 34,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 368,
                'endLine' => 368,
                'startTokenPos' => 1377,
                'startFilePos' => 10031,
                'endTokenPos' => 1377,
                'endFilePos' => 10034,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 368,
            'endLine' => 368,
            'startColumn' => 47,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the response contains the given header and equals the optional value.
 *
 * @param  string  $headerName
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 368,
        'endLine' => 384,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertHeaderContains' => 
      array (
        'name' => 'assertHeaderContains',
        'parameters' => 
        array (
          'headerName' => 
          array (
            'name' => 'headerName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 393,
            'endLine' => 393,
            'startColumn' => 42,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 393,
            'endLine' => 393,
            'startColumn' => 55,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the response contains the given header and that its value contains the given string.
 *
 * @param  string  $headerName
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 393,
        'endLine' => 407,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertHeaderMissing' => 
      array (
        'name' => 'assertHeaderMissing',
        'parameters' => 
        array (
          'headerName' => 
          array (
            'name' => 'headerName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 415,
            'endLine' => 415,
            'startColumn' => 41,
            'endColumn' => 51,
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
 * Asserts that the response does not contain the given header.
 *
 * @param  string  $headerName
 * @return $this
 */',
        'startLine' => 415,
        'endLine' => 422,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertLocation' => 
      array (
        'name' => 'assertLocation',
        'parameters' => 
        array (
          'uri' => 
          array (
            'name' => 'uri',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 430,
            'endLine' => 430,
            'startColumn' => 36,
            'endColumn' => 39,
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
 * Assert that the current location header matches the given URI.
 *
 * @param  string  $uri
 * @return $this
 */',
        'startLine' => 430,
        'endLine' => 437,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertDownload' => 
      array (
        'name' => 'assertDownload',
        'parameters' => 
        array (
          'filename' => 
          array (
            'name' => 'filename',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 445,
                'endLine' => 445,
                'startTokenPos' => 1729,
                'startFilePos' => 12251,
                'endTokenPos' => 1729,
                'endFilePos' => 12254,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 445,
            'endLine' => 445,
            'startColumn' => 36,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response offers a file download.
 *
 * @param  string|null  $filename
 * @return $this
 */',
        'startLine' => 445,
        'endLine' => 485,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertPlainCookie' => 
      array (
        'name' => 'assertPlainCookie',
        'parameters' => 
        array (
          'cookieName' => 
          array (
            'name' => 'cookieName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 494,
            'endLine' => 494,
            'startColumn' => 39,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 494,
                'endLine' => 494,
                'startTokenPos' => 2058,
                'startFilePos' => 14092,
                'endTokenPos' => 2058,
                'endFilePos' => 14095,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 494,
            'endLine' => 494,
            'startColumn' => 52,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the response contains the given cookie and equals the optional value.
 *
 * @param  string  $cookieName
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 494,
        'endLine' => 499,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertCookie' => 
      array (
        'name' => 'assertCookie',
        'parameters' => 
        array (
          'cookieName' => 
          array (
            'name' => 'cookieName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 510,
            'endLine' => 510,
            'startColumn' => 34,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 510,
                'endLine' => 510,
                'startTokenPos' => 2099,
                'startFilePos' => 14510,
                'endTokenPos' => 2099,
                'endFilePos' => 14513,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 510,
            'endLine' => 510,
            'startColumn' => 47,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'encrypted' => 
          array (
            'name' => 'encrypted',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 510,
                'endLine' => 510,
                'startTokenPos' => 2106,
                'startFilePos' => 14529,
                'endTokenPos' => 2106,
                'endFilePos' => 14532,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 510,
            'endLine' => 510,
            'startColumn' => 62,
            'endColumn' => 78,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'unserialize' => 
          array (
            'name' => 'unserialize',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 510,
                'endLine' => 510,
                'startTokenPos' => 2113,
                'startFilePos' => 14550,
                'endTokenPos' => 2113,
                'endFilePos' => 14554,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 510,
            'endLine' => 510,
            'startColumn' => 81,
            'endColumn' => 100,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Asserts that the response contains the given cookie and equals the optional value.
 *
 * @param  string  $cookieName
 * @param  mixed  $value
 * @param  bool  $encrypted
 * @param  bool  $unserialize
 * @return $this
 */',
        'startLine' => 510,
        'endLine' => 529,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertCookieExpired' => 
      array (
        'name' => 'assertCookieExpired',
        'parameters' => 
        array (
          'cookieName' => 
          array (
            'name' => 'cookieName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 537,
            'endLine' => 537,
            'startColumn' => 41,
            'endColumn' => 51,
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
 * Asserts that the response contains the given cookie and is expired.
 *
 * @param  string  $cookieName
 * @return $this
 */',
        'startLine' => 537,
        'endLine' => 552,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertCookieNotExpired' => 
      array (
        'name' => 'assertCookieNotExpired',
        'parameters' => 
        array (
          'cookieName' => 
          array (
            'name' => 'cookieName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 560,
            'endLine' => 560,
            'startColumn' => 44,
            'endColumn' => 54,
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
 * Asserts that the response contains the given cookie and is not expired.
 *
 * @param  string  $cookieName
 * @return $this
 */',
        'startLine' => 560,
        'endLine' => 575,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertCookieMissing' => 
      array (
        'name' => 'assertCookieMissing',
        'parameters' => 
        array (
          'cookieName' => 
          array (
            'name' => 'cookieName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 583,
            'endLine' => 583,
            'startColumn' => 41,
            'endColumn' => 51,
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
 * Asserts that the response does not contain the given cookie.
 *
 * @param  string  $cookieName
 * @return $this
 */',
        'startLine' => 583,
        'endLine' => 591,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'getCookie' => 
      array (
        'name' => 'getCookie',
        'parameters' => 
        array (
          'cookieName' => 
          array (
            'name' => 'cookieName',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 601,
            'endLine' => 601,
            'startColumn' => 31,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'decrypt' => 
          array (
            'name' => 'decrypt',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 601,
                'endLine' => 601,
                'startTokenPos' => 2562,
                'startFilePos' => 17373,
                'endTokenPos' => 2562,
                'endFilePos' => 17376,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 601,
            'endLine' => 601,
            'startColumn' => 44,
            'endColumn' => 58,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'unserialize' => 
          array (
            'name' => 'unserialize',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 601,
                'endLine' => 601,
                'startTokenPos' => 2569,
                'startFilePos' => 17394,
                'endTokenPos' => 2569,
                'endFilePos' => 17398,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 601,
            'endLine' => 601,
            'startColumn' => 61,
            'endColumn' => 80,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the given cookie from the response.
 *
 * @param  string  $cookieName
 * @param  bool  $decrypt
 * @param  bool  $unserialize
 * @return \\Symfony\\Component\\HttpFoundation\\Cookie|null
 */',
        'startLine' => 601,
        'endLine' => 627,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertContent' => 
      array (
        'name' => 'assertContent',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 635,
            'endLine' => 635,
            'startColumn' => 35,
            'endColumn' => 40,
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
 * Assert that the given string matches the response content.
 *
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 635,
        'endLine' => 640,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertStreamed' => 
      array (
        'name' => 'assertStreamed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response was streamed.
 *
 * @return $this
 */',
        'startLine' => 647,
        'endLine' => 655,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertNotStreamed' => 
      array (
        'name' => 'assertNotStreamed',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response was not streamed.
 *
 * @return $this
 */',
        'startLine' => 662,
        'endLine' => 670,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertStreamedContent' => 
      array (
        'name' => 'assertStreamedContent',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 678,
            'endLine' => 678,
            'startColumn' => 43,
            'endColumn' => 48,
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
 * Assert that the given string matches the streamed response content.
 *
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 678,
        'endLine' => 683,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertStreamedJsonContent' => 
      array (
        'name' => 'assertStreamedJsonContent',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 691,
            'endLine' => 691,
            'startColumn' => 47,
            'endColumn' => 52,
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
 * Assert that the given array matches the streamed JSON response content.
 *
 * @param  array  $value
 * @return $this
 */',
        'startLine' => 691,
        'endLine' => 694,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSee' => 
      array (
        'name' => 'assertSee',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 703,
            'endLine' => 703,
            'startColumn' => 31,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'escape' => 
          array (
            'name' => 'escape',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 703,
                'endLine' => 703,
                'startTokenPos' => 2971,
                'startFilePos' => 20260,
                'endTokenPos' => 2971,
                'endFilePos' => 20263,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 703,
            'endLine' => 703,
            'startColumn' => 39,
            'endColumn' => 52,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the given string or array of strings are contained within the response.
 *
 * @param  string|list<string>  $value
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 703,
        'endLine' => 714,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSeeHtml' => 
      array (
        'name' => 'assertSeeHtml',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 722,
            'endLine' => 722,
            'startColumn' => 35,
            'endColumn' => 40,
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
 * Assert that the given HTML string or array of HTML strings are contained within the response.
 *
 * @param  string|list<string>  $value
 * @return $this
 */',
        'startLine' => 722,
        'endLine' => 725,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSeeInOrder' => 
      array (
        'name' => 'assertSeeInOrder',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 734,
            'endLine' => 734,
            'startColumn' => 38,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'escape' => 
          array (
            'name' => 'escape',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 734,
                'endLine' => 734,
                'startTokenPos' => 3100,
                'startFilePos' => 21104,
                'endTokenPos' => 3100,
                'endFilePos' => 21107,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 734,
            'endLine' => 734,
            'startColumn' => 53,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the given strings are contained in order within the response.
 *
 * @param  list<string>  $values
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 734,
        'endLine' => 741,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSeeHtmlInOrder' => 
      array (
        'name' => 'assertSeeHtmlInOrder',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 749,
            'endLine' => 749,
            'startColumn' => 42,
            'endColumn' => 54,
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
 * Assert that the given HTML strings are contained in order within the response.
 *
 * @param  list<string>  $values
 * @return $this
 */',
        'startLine' => 749,
        'endLine' => 752,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSeeText' => 
      array (
        'name' => 'assertSeeText',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 761,
            'endLine' => 761,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'escape' => 
          array (
            'name' => 'escape',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 761,
                'endLine' => 761,
                'startTokenPos' => 3206,
                'startFilePos' => 21865,
                'endTokenPos' => 3206,
                'endFilePos' => 21868,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 761,
            'endLine' => 761,
            'startColumn' => 43,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the given string or array of strings are contained within the response text.
 *
 * @param  string|list<string>  $value
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 761,
        'endLine' => 774,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSeeTextInOrder' => 
      array (
        'name' => 'assertSeeTextInOrder',
        'parameters' => 
        array (
          'values' => 
          array (
            'name' => 'values',
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
            'startLine' => 783,
            'endLine' => 783,
            'startColumn' => 42,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'escape' => 
          array (
            'name' => 'escape',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 783,
                'endLine' => 783,
                'startTokenPos' => 3317,
                'startFilePos' => 22469,
                'endTokenPos' => 3317,
                'endFilePos' => 22472,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 783,
            'endLine' => 783,
            'startColumn' => 57,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the given strings are contained in order within the response text.
 *
 * @param  list<string>  $values
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 783,
        'endLine' => 790,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertDontSee' => 
      array (
        'name' => 'assertDontSee',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 799,
            'endLine' => 799,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'escape' => 
          array (
            'name' => 'escape',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 799,
                'endLine' => 799,
                'startTokenPos' => 3396,
                'startFilePos' => 22949,
                'endTokenPos' => 3396,
                'endFilePos' => 22952,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 799,
            'endLine' => 799,
            'startColumn' => 43,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the given string or array of strings are not contained within the response.
 *
 * @param  string|list<string>  $value
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 799,
        'endLine' => 810,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertDontSeeHtml' => 
      array (
        'name' => 'assertDontSeeHtml',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 818,
            'endLine' => 818,
            'startColumn' => 39,
            'endColumn' => 44,
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
 * Assert that the given HTML string or array of HTML strings are not contained within the response.
 *
 * @param  string|list<string>  $value
 * @return $this
 */',
        'startLine' => 818,
        'endLine' => 821,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertDontSeeText' => 
      array (
        'name' => 'assertDontSeeText',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 830,
            'endLine' => 830,
            'startColumn' => 39,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'escape' => 
          array (
            'name' => 'escape',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 830,
                'endLine' => 830,
                'startTokenPos' => 3523,
                'startFilePos' => 23827,
                'endTokenPos' => 3523,
                'endFilePos' => 23830,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 830,
            'endLine' => 830,
            'startColumn' => 47,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the given string or array of strings are not contained within the response text.
 *
 * @param  string|list<string>  $value
 * @param  bool  $escape
 * @return $this
 */',
        'startLine' => 830,
        'endLine' => 843,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJson' => 
      array (
        'name' => 'assertJson',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 852,
            'endLine' => 852,
            'startColumn' => 32,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'strict' => 
          array (
            'name' => 'strict',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 852,
                'endLine' => 852,
                'startTokenPos' => 3632,
                'startFilePos' => 24397,
                'endTokenPos' => 3632,
                'endFilePos' => 24401,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 852,
            'endLine' => 852,
            'startColumn' => 40,
            'endColumn' => 54,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response is a superset of the given JSON.
 *
 * @param  array|callable  $value
 * @param  bool  $strict
 * @return $this
 */',
        'startLine' => 852,
        'endLine' => 869,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonPath' => 
      array (
        'name' => 'assertJsonPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 878,
            'endLine' => 878,
            'startColumn' => 36,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'expect' => 
          array (
            'name' => 'expect',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 878,
            'endLine' => 878,
            'startColumn' => 43,
            'endColumn' => 49,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the expected value and type exists at the given path in the response.
 *
 * @param  string  $path
 * @param  mixed  $expect
 * @return $this
 */',
        'startLine' => 878,
        'endLine' => 883,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonPathCanonicalizing' => 
      array (
        'name' => 'assertJsonPathCanonicalizing',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 892,
            'endLine' => 892,
            'startColumn' => 50,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'expect' => 
          array (
            'name' => 'expect',
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
            'startLine' => 892,
            'endLine' => 892,
            'startColumn' => 57,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the given path in the response contains all of the expected values without looking at the order.
 *
 * @param  string  $path
 * @param  array  $expect
 * @return $this
 */',
        'startLine' => 892,
        'endLine' => 897,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertExactJson' => 
      array (
        'name' => 'assertExactJson',
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
            'startLine' => 905,
            'endLine' => 905,
            'startColumn' => 37,
            'endColumn' => 47,
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
 * Assert that the response has the exact given JSON.
 *
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 905,
        'endLine' => 910,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSimilarJson' => 
      array (
        'name' => 'assertSimilarJson',
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
            'startLine' => 918,
            'endLine' => 918,
            'startColumn' => 39,
            'endColumn' => 49,
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
 * Assert that the response has the similar JSON as given.
 *
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 918,
        'endLine' => 923,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonFragments' => 
      array (
        'name' => 'assertJsonFragments',
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
            'startLine' => 931,
            'endLine' => 931,
            'startColumn' => 41,
            'endColumn' => 51,
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
 * Assert that the response contains the given JSON fragments.
 *
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 931,
        'endLine' => 938,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonFragment' => 
      array (
        'name' => 'assertJsonFragment',
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
            'startLine' => 946,
            'endLine' => 946,
            'startColumn' => 40,
            'endColumn' => 50,
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
 * Assert that the response contains the given JSON fragment.
 *
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 946,
        'endLine' => 951,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonMissing' => 
      array (
        'name' => 'assertJsonMissing',
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
            'startLine' => 960,
            'endLine' => 960,
            'startColumn' => 39,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'exact' => 
          array (
            'name' => 'exact',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 960,
                'endLine' => 960,
                'startTokenPos' => 3970,
                'startFilePos' => 26951,
                'endTokenPos' => 3970,
                'endFilePos' => 26955,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 960,
            'endLine' => 960,
            'startColumn' => 52,
            'endColumn' => 65,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response does not contain the given JSON fragment.
 *
 * @param  array  $data
 * @param  bool  $exact
 * @return $this
 */',
        'startLine' => 960,
        'endLine' => 965,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonMissingExact' => 
      array (
        'name' => 'assertJsonMissingExact',
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
            'startLine' => 973,
            'endLine' => 973,
            'startColumn' => 44,
            'endColumn' => 54,
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
 * Assert that the response does not contain the exact JSON fragment.
 *
 * @param  array  $data
 * @return $this
 */',
        'startLine' => 973,
        'endLine' => 978,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonMissingPath' => 
      array (
        'name' => 'assertJsonMissingPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
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
            'startLine' => 986,
            'endLine' => 986,
            'startColumn' => 43,
            'endColumn' => 54,
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
 * Assert that the response does not contain the given path.
 *
 * @param  string  $path
 * @return $this
 */',
        'startLine' => 986,
        'endLine' => 991,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonStructure' => 
      array (
        'name' => 'assertJsonStructure',
        'parameters' => 
        array (
          'structure' => 
          array (
            'name' => 'structure',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1000,
                'endLine' => 1000,
                'startTokenPos' => 4080,
                'startFilePos' => 27899,
                'endTokenPos' => 4080,
                'endFilePos' => 27902,
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
                      'name' => 'null',
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
            'startLine' => 1000,
            'endLine' => 1000,
            'startColumn' => 41,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'responseData' => 
          array (
            'name' => 'responseData',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1000,
                'endLine' => 1000,
                'startTokenPos' => 4090,
                'startFilePos' => 27928,
                'endTokenPos' => 4090,
                'endFilePos' => 27931,
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
                      'name' => 'null',
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
            'startLine' => 1000,
            'endLine' => 1000,
            'startColumn' => 67,
            'endColumn' => 93,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response has a given JSON structure.
 *
 * @param  array|null  $structure
 * @param  array|null  $responseData
 * @return $this
 */',
        'startLine' => 1000,
        'endLine' => 1005,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertExactJsonStructure' => 
      array (
        'name' => 'assertExactJsonStructure',
        'parameters' => 
        array (
          'structure' => 
          array (
            'name' => 'structure',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1014,
                'endLine' => 1014,
                'startTokenPos' => 4132,
                'startFilePos' => 28301,
                'endTokenPos' => 4132,
                'endFilePos' => 28304,
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
                      'name' => 'null',
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
            'startLine' => 1014,
            'endLine' => 1014,
            'startColumn' => 46,
            'endColumn' => 69,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'responseData' => 
          array (
            'name' => 'responseData',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1014,
                'endLine' => 1014,
                'startTokenPos' => 4142,
                'startFilePos' => 28330,
                'endTokenPos' => 4142,
                'endFilePos' => 28333,
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
                      'name' => 'null',
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
            'startLine' => 1014,
            'endLine' => 1014,
            'startColumn' => 72,
            'endColumn' => 98,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response has the exact JSON structure.
 *
 * @param  array|null  $structure
 * @param  array|null  $responseData
 * @return $this
 */',
        'startLine' => 1014,
        'endLine' => 1019,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonCount' => 
      array (
        'name' => 'assertJsonCount',
        'parameters' => 
        array (
          'count' => 
          array (
            'name' => 'count',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1028,
            'endLine' => 1028,
            'startColumn' => 37,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1028,
                'endLine' => 1028,
                'startTokenPos' => 4189,
                'startFilePos' => 28705,
                'endTokenPos' => 4189,
                'endFilePos' => 28708,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1028,
            'endLine' => 1028,
            'startColumn' => 49,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response JSON has the expected count of items at the given key.
 *
 * @param  int  $count
 * @param  string|null  $key
 * @return $this
 */',
        'startLine' => 1028,
        'endLine' => 1033,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonValidationErrors' => 
      array (
        'name' => 'assertJsonValidationErrors',
        'parameters' => 
        array (
          'errors' => 
          array (
            'name' => 'errors',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1042,
            'endLine' => 1042,
            'startColumn' => 48,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'responseKey' => 
          array (
            'name' => 'responseKey',
            'default' => 
            array (
              'code' => '\'errors\'',
              'attributes' => 
              array (
                'startLine' => 1042,
                'endLine' => 1042,
                'startTokenPos' => 4231,
                'startFilePos' => 29069,
                'endTokenPos' => 4231,
                'endFilePos' => 29076,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1042,
            'endLine' => 1042,
            'startColumn' => 57,
            'endColumn' => 79,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response has the given JSON validation errors.
 *
 * @param  string|array  $errors
 * @param  string  $responseKey
 * @return $this
 */',
        'startLine' => 1042,
        'endLine' => 1084,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertOnlyJsonValidationErrors' => 
      array (
        'name' => 'assertOnlyJsonValidationErrors',
        'parameters' => 
        array (
          'errors' => 
          array (
            'name' => 'errors',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1093,
            'endLine' => 1093,
            'startColumn' => 52,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'responseKey' => 
          array (
            'name' => 'responseKey',
            'default' => 
            array (
              'code' => '\'errors\'',
              'attributes' => 
              array (
                'startLine' => 1093,
                'endLine' => 1093,
                'startTokenPos' => 4517,
                'startFilePos' => 30901,
                'endTokenPos' => 4517,
                'endFilePos' => 30908,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1093,
            'endLine' => 1093,
            'startColumn' => 61,
            'endColumn' => 83,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response has the given JSON validation errors but does not have any other JSON validation errors.
 *
 * @param  string|array  $errors
 * @param  string  $responseKey
 * @return $this
 */',
        'startLine' => 1093,
        'endLine' => 1111,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonValidationErrorFor' => 
      array (
        'name' => 'assertJsonValidationErrorFor',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1120,
            'endLine' => 1120,
            'startColumn' => 50,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'responseKey' => 
          array (
            'name' => 'responseKey',
            'default' => 
            array (
              'code' => '\'errors\'',
              'attributes' => 
              array (
                'startLine' => 1120,
                'endLine' => 1120,
                'startTokenPos' => 4703,
                'startFilePos' => 31816,
                'endTokenPos' => 4703,
                'endFilePos' => 31823,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1120,
            'endLine' => 1120,
            'startColumn' => 56,
            'endColumn' => 78,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert the response has any JSON validation errors for the given key.
 *
 * @param  string  $key
 * @param  string  $responseKey
 * @return $this
 */',
        'startLine' => 1120,
        'endLine' => 1136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonMissingValidationErrors' => 
      array (
        'name' => 'assertJsonMissingValidationErrors',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1145,
                'endLine' => 1145,
                'startTokenPos' => 4822,
                'startFilePos' => 32716,
                'endTokenPos' => 4822,
                'endFilePos' => 32719,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1145,
            'endLine' => 1145,
            'startColumn' => 55,
            'endColumn' => 66,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'responseKey' => 
          array (
            'name' => 'responseKey',
            'default' => 
            array (
              'code' => '\'errors\'',
              'attributes' => 
              array (
                'startLine' => 1145,
                'endLine' => 1145,
                'startTokenPos' => 4829,
                'startFilePos' => 32737,
                'endTokenPos' => 4829,
                'endFilePos' => 32744,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1145,
            'endLine' => 1145,
            'startColumn' => 69,
            'endColumn' => 91,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response has no JSON validation errors for the given keys.
 *
 * @param  string|array|null  $keys
 * @param  string  $responseKey
 * @return $this
 */',
        'startLine' => 1145,
        'endLine' => 1178,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonIsArray' => 
      array (
        'name' => 'assertJsonIsArray',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1186,
                'endLine' => 1186,
                'startTokenPos' => 5066,
                'startFilePos' => 33870,
                'endTokenPos' => 5066,
                'endFilePos' => 33873,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1186,
            'endLine' => 1186,
            'startColumn' => 39,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the given key is a JSON array.
 *
 * @param  string|null  $key
 * @return $this
 */',
        'startLine' => 1186,
        'endLine' => 1199,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertJsonIsObject' => 
      array (
        'name' => 'assertJsonIsObject',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1207,
                'endLine' => 1207,
                'startTokenPos' => 5150,
                'startFilePos' => 34353,
                'endTokenPos' => 5150,
                'endFilePos' => 34356,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1207,
            'endLine' => 1207,
            'startColumn' => 40,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the given key is a JSON object.
 *
 * @param  string|null  $key
 * @return $this
 */',
        'startLine' => 1207,
        'endLine' => 1220,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'decodeResponseJson' => 
      array (
        'name' => 'decodeResponseJson',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Validate the decoded response JSON.
 *
 * @return \\Illuminate\\Testing\\AssertableJsonString
 *
 * @throws \\Throwable
 */',
        'startLine' => 1229,
        'endLine' => 1249,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'json' => 
      array (
        'name' => 'json',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1257,
                'endLine' => 1257,
                'startTokenPos' => 5387,
                'startFilePos' => 35689,
                'endTokenPos' => 5387,
                'endFilePos' => 35692,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1257,
            'endLine' => 1257,
            'startColumn' => 26,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return the decoded response JSON.
 *
 * @param  string|null  $key
 * @return mixed
 */',
        'startLine' => 1257,
        'endLine' => 1260,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'collect' => 
      array (
        'name' => 'collect',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1268,
                'endLine' => 1268,
                'startTokenPos' => 5420,
                'startFilePos' => 35967,
                'endTokenPos' => 5420,
                'endFilePos' => 35970,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1268,
            'endLine' => 1268,
            'startColumn' => 29,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the decoded JSON body of the response as a collection.
 *
 * @param  string|null  $key
 * @return \\Illuminate\\Support\\Collection
 */',
        'startLine' => 1268,
        'endLine' => 1271,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertViewIs' => 
      array (
        'name' => 'assertViewIs',
        'parameters' => 
        array (
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1279,
            'endLine' => 1279,
            'startColumn' => 34,
            'endColumn' => 39,
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
 * Assert that the response view equals the given value.
 *
 * @param  string  $value
 * @return $this
 */',
        'startLine' => 1279,
        'endLine' => 1286,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertViewHas' => 
      array (
        'name' => 'assertViewHas',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1295,
            'endLine' => 1295,
            'startColumn' => 35,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1295,
                'endLine' => 1295,
                'startTokenPos' => 5506,
                'startFilePos' => 36600,
                'endTokenPos' => 5506,
                'endFilePos' => 36603,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1295,
            'endLine' => 1295,
            'startColumn' => 41,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response view has a given piece of bound data.
 *
 * @param  string|array  $key
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 1295,
        'endLine' => 1321,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertViewHasAll' => 
      array (
        'name' => 'assertViewHasAll',
        'parameters' => 
        array (
          'bindings' => 
          array (
            'name' => 'bindings',
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
            'startLine' => 1329,
            'endLine' => 1329,
            'startColumn' => 38,
            'endColumn' => 52,
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
 * Assert that the response view has a given list of bound data.
 *
 * @param  array  $bindings
 * @return $this
 */',
        'startLine' => 1329,
        'endLine' => 1340,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'viewData' => 
      array (
        'name' => 'viewData',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1348,
                'endLine' => 1348,
                'startTokenPos' => 5919,
                'startFilePos' => 38671,
                'endTokenPos' => 5919,
                'endFilePos' => 38674,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1348,
            'endLine' => 1348,
            'startColumn' => 30,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a piece of data from the original view.
 *
 * @param  string|null  $key
 * @return mixed
 */',
        'startLine' => 1348,
        'endLine' => 1359,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertViewMissing' => 
      array (
        'name' => 'assertViewMissing',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1367,
            'endLine' => 1367,
            'startColumn' => 39,
            'endColumn' => 42,
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
 * Assert that the response view is missing a piece of bound data.
 *
 * @param  string  $key
 * @return $this
 */',
        'startLine' => 1367,
        'endLine' => 1374,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'ensureResponseHasView' => 
      array (
        'name' => 'ensureResponseHasView',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Ensure that the response has a view as its original content.
 *
 * @return $this
 */',
        'startLine' => 1381,
        'endLine' => 1388,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'responseHasView' => 
      array (
        'name' => 'responseHasView',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the original response is a view.
 *
 * @return bool
 */',
        'startLine' => 1395,
        'endLine' => 1398,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertValid' => 
      array (
        'name' => 'assertValid',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1408,
                'endLine' => 1408,
                'startTokenPos' => 6122,
                'startFilePos' => 40050,
                'endTokenPos' => 6122,
                'endFilePos' => 40053,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1408,
            'endLine' => 1408,
            'startColumn' => 33,
            'endColumn' => 44,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'errorBag' => 
          array (
            'name' => 'errorBag',
            'default' => 
            array (
              'code' => '\'default\'',
              'attributes' => 
              array (
                'startLine' => 1408,
                'endLine' => 1408,
                'startTokenPos' => 6129,
                'startFilePos' => 40068,
                'endTokenPos' => 6129,
                'endFilePos' => 40076,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1408,
            'endLine' => 1408,
            'startColumn' => 47,
            'endColumn' => 67,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'responseKey' => 
          array (
            'name' => 'responseKey',
            'default' => 
            array (
              'code' => '\'errors\'',
              'attributes' => 
              array (
                'startLine' => 1408,
                'endLine' => 1408,
                'startTokenPos' => 6136,
                'startFilePos' => 40094,
                'endTokenPos' => 6136,
                'endFilePos' => 40101,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1408,
            'endLine' => 1408,
            'startColumn' => 70,
            'endColumn' => 92,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the given keys do not have validation errors.
 *
 * @param  string|array|null  $keys
 * @param  string  $errorBag
 * @param  string  $responseKey
 * @return $this
 */',
        'startLine' => 1408,
        'endLine' => 1441,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertInvalid' => 
      array (
        'name' => 'assertInvalid',
        'parameters' => 
        array (
          'errors' => 
          array (
            'name' => 'errors',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1451,
                'endLine' => 1451,
                'startTokenPos' => 6394,
                'startFilePos' => 41447,
                'endTokenPos' => 6394,
                'endFilePos' => 41450,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1451,
            'endLine' => 1451,
            'startColumn' => 35,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'errorBag' => 
          array (
            'name' => 'errorBag',
            'default' => 
            array (
              'code' => '\'default\'',
              'attributes' => 
              array (
                'startLine' => 1452,
                'endLine' => 1452,
                'startTokenPos' => 6401,
                'startFilePos' => 41499,
                'endTokenPos' => 6401,
                'endFilePos' => 41507,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1452,
            'endLine' => 1452,
            'startColumn' => 35,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'responseKey' => 
          array (
            'name' => 'responseKey',
            'default' => 
            array (
              'code' => '\'errors\'',
              'attributes' => 
              array (
                'startLine' => 1453,
                'endLine' => 1453,
                'startTokenPos' => 6408,
                'startFilePos' => 41559,
                'endTokenPos' => 6408,
                'endFilePos' => 41566,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1453,
            'endLine' => 1453,
            'startColumn' => 35,
            'endColumn' => 57,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response has the given validation errors.
 *
 * @param  string|array|null  $errors
 * @param  string  $errorBag
 * @param  string  $responseKey
 * @return $this
 */',
        'startLine' => 1451,
        'endLine' => 1497,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertOnlyInvalid' => 
      array (
        'name' => 'assertOnlyInvalid',
        'parameters' => 
        array (
          'errors' => 
          array (
            'name' => 'errors',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1507,
                'endLine' => 1507,
                'startTokenPos' => 6742,
                'startFilePos' => 43667,
                'endTokenPos' => 6742,
                'endFilePos' => 43670,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1507,
            'endLine' => 1507,
            'startColumn' => 39,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'errorBag' => 
          array (
            'name' => 'errorBag',
            'default' => 
            array (
              'code' => '\'default\'',
              'attributes' => 
              array (
                'startLine' => 1507,
                'endLine' => 1507,
                'startTokenPos' => 6749,
                'startFilePos' => 43685,
                'endTokenPos' => 6749,
                'endFilePos' => 43693,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1507,
            'endLine' => 1507,
            'startColumn' => 55,
            'endColumn' => 75,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'responseKey' => 
          array (
            'name' => 'responseKey',
            'default' => 
            array (
              'code' => '\'errors\'',
              'attributes' => 
              array (
                'startLine' => 1507,
                'endLine' => 1507,
                'startTokenPos' => 6756,
                'startFilePos' => 43711,
                'endTokenPos' => 6756,
                'endFilePos' => 43718,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1507,
            'endLine' => 1507,
            'startColumn' => 78,
            'endColumn' => 100,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the response has the given validation errors but does not have any other validation errors.
 *
 * @param  string|array|null  $errors
 * @param  string  $errorBag
 * @param  string  $responseKey
 * @return $this
 */',
        'startLine' => 1507,
        'endLine' => 1531,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSessionHas' => 
      array (
        'name' => 'assertSessionHas',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1540,
            'endLine' => 1540,
            'startColumn' => 38,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1540,
                'endLine' => 1540,
                'startTokenPos' => 6978,
                'startFilePos' => 44791,
                'endTokenPos' => 6978,
                'endFilePos' => 44794,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1540,
            'endLine' => 1540,
            'startColumn' => 44,
            'endColumn' => 56,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the session has a given value.
 *
 * @param  string|array  $key
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 1540,
        'endLine' => 1558,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSessionHasAll' => 
      array (
        'name' => 'assertSessionHasAll',
        'parameters' => 
        array (
          'bindings' => 
          array (
            'name' => 'bindings',
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
            'startLine' => 1566,
            'endLine' => 1566,
            'startColumn' => 41,
            'endColumn' => 55,
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
 * Assert that the session has a given list of values.
 *
 * @param  array  $bindings
 * @return $this
 */',
        'startLine' => 1566,
        'endLine' => 1587,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSessionHasInput' => 
      array (
        'name' => 'assertSessionHasInput',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1596,
            'endLine' => 1596,
            'startColumn' => 43,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1596,
                'endLine' => 1596,
                'startTokenPos' => 7312,
                'startFilePos' => 46410,
                'endTokenPos' => 7312,
                'endFilePos' => 46413,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1596,
            'endLine' => 1596,
            'startColumn' => 49,
            'endColumn' => 61,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the session has a given value in the flashed input array.
 *
 * @param  string|array  $key
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 1596,
        'endLine' => 1622,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSessionHasErrors' => 
      array (
        'name' => 'assertSessionHasErrors',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1632,
                'endLine' => 1632,
                'startTokenPos' => 7526,
                'startFilePos' => 47497,
                'endTokenPos' => 7527,
                'endFilePos' => 47498,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1632,
            'endLine' => 1632,
            'startColumn' => 44,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'format' => 
          array (
            'name' => 'format',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1632,
                'endLine' => 1632,
                'startTokenPos' => 7534,
                'startFilePos' => 47511,
                'endTokenPos' => 7534,
                'endFilePos' => 47514,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1632,
            'endLine' => 1632,
            'startColumn' => 56,
            'endColumn' => 69,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'errorBag' => 
          array (
            'name' => 'errorBag',
            'default' => 
            array (
              'code' => '\'default\'',
              'attributes' => 
              array (
                'startLine' => 1632,
                'endLine' => 1632,
                'startTokenPos' => 7541,
                'startFilePos' => 47529,
                'endTokenPos' => 7541,
                'endFilePos' => 47537,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1632,
            'endLine' => 1632,
            'startColumn' => 72,
            'endColumn' => 92,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the session has the given errors.
 *
 * @param  string|array  $keys
 * @param  mixed  $format
 * @param  string  $errorBag
 * @return $this
 */',
        'startLine' => 1632,
        'endLine' => 1649,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSessionDoesntHaveErrors' => 
      array (
        'name' => 'assertSessionDoesntHaveErrors',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1659,
                'endLine' => 1659,
                'startTokenPos' => 7701,
                'startFilePos' => 48358,
                'endTokenPos' => 7702,
                'endFilePos' => 48359,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1659,
            'endLine' => 1659,
            'startColumn' => 51,
            'endColumn' => 60,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'format' => 
          array (
            'name' => 'format',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1659,
                'endLine' => 1659,
                'startTokenPos' => 7709,
                'startFilePos' => 48372,
                'endTokenPos' => 7709,
                'endFilePos' => 48375,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1659,
            'endLine' => 1659,
            'startColumn' => 63,
            'endColumn' => 76,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'errorBag' => 
          array (
            'name' => 'errorBag',
            'default' => 
            array (
              'code' => '\'default\'',
              'attributes' => 
              array (
                'startLine' => 1659,
                'endLine' => 1659,
                'startTokenPos' => 7716,
                'startFilePos' => 48390,
                'endTokenPos' => 7716,
                'endFilePos' => 48398,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1659,
            'endLine' => 1659,
            'startColumn' => 79,
            'endColumn' => 99,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the session is missing the given errors.
 *
 * @param  string|array  $keys
 * @param  string|null  $format
 * @param  string  $errorBag
 * @return $this
 */',
        'startLine' => 1659,
        'endLine' => 1684,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSessionHasNoErrors' => 
      array (
        'name' => 'assertSessionHasNoErrors',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the session has no errors.
 *
 * @return $this
 */',
        'startLine' => 1691,
        'endLine' => 1716,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSessionHasErrorsIn' => 
      array (
        'name' => 'assertSessionHasErrorsIn',
        'parameters' => 
        array (
          'errorBag' => 
          array (
            'name' => 'errorBag',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1726,
            'endLine' => 1726,
            'startColumn' => 46,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'keys' => 
          array (
            'name' => 'keys',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1726,
                'endLine' => 1726,
                'startTokenPos' => 8121,
                'startFilePos' => 50416,
                'endTokenPos' => 8122,
                'endFilePos' => 50417,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1726,
            'endLine' => 1726,
            'startColumn' => 57,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'format' => 
          array (
            'name' => 'format',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1726,
                'endLine' => 1726,
                'startTokenPos' => 8129,
                'startFilePos' => 50430,
                'endTokenPos' => 8129,
                'endFilePos' => 50433,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1726,
            'endLine' => 1726,
            'startColumn' => 69,
            'endColumn' => 82,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the session has the given errors.
 *
 * @param  string  $errorBag
 * @param  string|array  $keys
 * @param  mixed  $format
 * @return $this
 */',
        'startLine' => 1726,
        'endLine' => 1729,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'assertSessionMissing' => 
      array (
        'name' => 'assertSessionMissing',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1738,
            'endLine' => 1738,
            'startColumn' => 42,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1738,
                'endLine' => 1738,
                'startTokenPos' => 8167,
                'startFilePos' => 50743,
                'endTokenPos' => 8167,
                'endFilePos' => 50746,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1738,
            'endLine' => 1738,
            'startColumn' => 48,
            'endColumn' => 60,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Assert that the session does not have a given key.
 *
 * @param  string|array  $key
 * @param  mixed  $value
 * @return $this
 */',
        'startLine' => 1738,
        'endLine' => 1760,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'session' => 
      array (
        'name' => 'session',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the current session store.
 *
 * @return \\Illuminate\\Session\\Store
 */',
        'startLine' => 1767,
        'endLine' => 1776,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'ddHeaders' => 
      array (
        'name' => 'ddHeaders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Dump the headers from the response and end the script.
 *
 * @return never
 */',
        'startLine' => 1783,
        'endLine' => 1788,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'ddBody' => 
      array (
        'name' => 'ddBody',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1796,
                'endLine' => 1796,
                'startTokenPos' => 8426,
                'startFilePos' => 52085,
                'endTokenPos' => 8426,
                'endFilePos' => 52088,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1796,
            'endLine' => 1796,
            'startColumn' => 28,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Dump the body of the response and end the script.
 *
 * @param  string|null  $key
 * @return never
 */',
        'startLine' => 1796,
        'endLine' => 1805,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'ddJson' => 
      array (
        'name' => 'ddJson',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1813,
                'endLine' => 1813,
                'startTokenPos' => 8483,
                'startFilePos' => 52425,
                'endTokenPos' => 8483,
                'endFilePos' => 52428,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1813,
            'endLine' => 1813,
            'startColumn' => 28,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Dump the JSON payload from the response and end the script.
 *
 * @param  string|null  $key
 * @return never
 */',
        'startLine' => 1813,
        'endLine' => 1816,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'ddSession' => 
      array (
        'name' => 'ddSession',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1824,
                'endLine' => 1824,
                'startTokenPos' => 8513,
                'startFilePos' => 52654,
                'endTokenPos' => 8514,
                'endFilePos' => 52655,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1824,
            'endLine' => 1824,
            'startColumn' => 31,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Dump the session from the response and end the script.
 *
 * @param  string|array  $keys
 * @return never
 */',
        'startLine' => 1824,
        'endLine' => 1829,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'dump' => 
      array (
        'name' => 'dump',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 1837,
                'endLine' => 1837,
                'startTokenPos' => 8547,
                'startFilePos' => 52876,
                'endTokenPos' => 8547,
                'endFilePos' => 52879,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1837,
            'endLine' => 1837,
            'startColumn' => 26,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Dump the content from the response.
 *
 * @param  string|null  $key
 * @return $this
 */',
        'startLine' => 1837,
        'endLine' => 1854,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'dumpHeaders' => 
      array (
        'name' => 'dumpHeaders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Dump the headers from the response.
 *
 * @return $this
 */',
        'startLine' => 1861,
        'endLine' => 1866,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'dumpSession' => 
      array (
        'name' => 'dumpSession',
        'parameters' => 
        array (
          'keys' => 
          array (
            'name' => 'keys',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 1874,
                'endLine' => 1874,
                'startTokenPos' => 8685,
                'startFilePos' => 53579,
                'endTokenPos' => 8686,
                'endFilePos' => 53580,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1874,
            'endLine' => 1874,
            'startColumn' => 33,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Dump the session from the response.
 *
 * @param  string|array  $keys
 * @return $this
 */',
        'startLine' => 1874,
        'endLine' => 1885,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'streamedContent' => 
      array (
        'name' => 'streamedContent',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the streamed content from the response.
 *
 * @return string
 */',
        'startLine' => 1892,
        'endLine' => 1914,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'withExceptions' => 
      array (
        'name' => 'withExceptions',
        'parameters' => 
        array (
          'exceptions' => 
          array (
            'name' => 'exceptions',
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
            'startLine' => 1922,
            'endLine' => 1922,
            'startColumn' => 36,
            'endColumn' => 57,
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
 * Set the previous exceptions on the response.
 *
 * @param  \\Illuminate\\Support\\Collection  $exceptions
 * @return $this
 */',
        'startLine' => 1922,
        'endLine' => 1927,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      '__get' => 
      array (
        'name' => '__get',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1935,
            'endLine' => 1935,
            'startColumn' => 27,
            'endColumn' => 30,
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
 * Dynamically access base response parameters.
 *
 * @param  string  $key
 * @return mixed
 */',
        'startLine' => 1935,
        'endLine' => 1938,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      '__isset' => 
      array (
        'name' => '__isset',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1946,
            'endLine' => 1946,
            'startColumn' => 29,
            'endColumn' => 32,
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
 * Proxy isset() checks to the underlying base response.
 *
 * @param  string  $key
 * @return bool
 */',
        'startLine' => 1946,
        'endLine' => 1949,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'offsetExists' => 
      array (
        'name' => 'offsetExists',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1957,
            'endLine' => 1957,
            'startColumn' => 34,
            'endColumn' => 40,
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
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if the given offset exists.
 *
 * @param  string  $offset
 * @return bool
 */',
        'startLine' => 1957,
        'endLine' => 1962,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'offsetGet' => 
      array (
        'name' => 'offsetGet',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1970,
            'endLine' => 1970,
            'startColumn' => 31,
            'endColumn' => 37,
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
            'name' => 'mixed',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the value for a given offset.
 *
 * @param  string  $offset
 * @return mixed
 */',
        'startLine' => 1970,
        'endLine' => 1975,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'offsetSet' => 
      array (
        'name' => 'offsetSet',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1986,
            'endLine' => 1986,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1986,
            'endLine' => 1986,
            'startColumn' => 40,
            'endColumn' => 45,
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
        'docComment' => '/**
 * Set the value at the given offset.
 *
 * @param  string  $offset
 * @param  mixed  $value
 * @return void
 *
 * @throws \\LogicException
 */',
        'startLine' => 1986,
        'endLine' => 1989,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      'offsetUnset' => 
      array (
        'name' => 'offsetUnset',
        'parameters' => 
        array (
          'offset' => 
          array (
            'name' => 'offset',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1999,
            'endLine' => 1999,
            'startColumn' => 33,
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
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Unset the value at the given offset.
 *
 * @param  string  $offset
 * @return void
 *
 * @throws \\LogicException
 */',
        'startLine' => 1999,
        'endLine' => 2002,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2011,
            'endLine' => 2011,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'args' => 
          array (
            'name' => 'args',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2011,
            'endLine' => 2011,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Handle dynamic calls into macros or pass missing methods to the base response.
 *
 * @param  string  $method
 * @param  array  $args
 * @return mixed
 */',
        'startLine' => 2011,
        'endLine' => 2018,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Illuminate\\Testing',
        'declaringClassName' => 'Illuminate\\Testing\\TestResponse',
        'implementingClassName' => 'Illuminate\\Testing\\TestResponse',
        'currentClassName' => 'Illuminate\\Testing\\TestResponse',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
        'Illuminate\\Testing\\Concerns\\AssertsStatusCodes' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\testing\\concerns\\assertsstatuscodes::__call',
          ),
        ),
        'Illuminate\\Support\\Traits\\Conditionable' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\conditionable::__call',
          ),
        ),
        'Illuminate\\Support\\Traits\\Dumpable' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\dumpable::__call',
          ),
        ),
        'Illuminate\\Support\\Traits\\Tappable' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\tappable::__call',
          ),
        ),
        'Illuminate\\Support\\Traits\\Macroable' => 
        array (
          0 => 
          array (
            'alias' => 'macroCall',
            'method' => '__call',
            'hash' => 'illuminate\\support\\traits\\macroable::__call',
          ),
        ),
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
        'illuminate\\testing\\concerns\\assertsstatuscodes::__call' => 'Illuminate\\Testing\\Concerns\\AssertsStatusCodes::__call',
        'illuminate\\support\\traits\\conditionable::__call' => 'Illuminate\\Support\\Traits\\Conditionable::__call',
        'illuminate\\support\\traits\\dumpable::__call' => 'Illuminate\\Support\\Traits\\Dumpable::__call',
        'illuminate\\support\\traits\\tappable::__call' => 'Illuminate\\Support\\Traits\\Tappable::__call',
        'illuminate\\support\\traits\\macroable::__call' => 'Illuminate\\Support\\Traits\\Macroable::__call',
      ),
    ),
  ),
));