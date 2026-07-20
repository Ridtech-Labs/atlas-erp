<?php declare(strict_types = 1);

return [
	'lastFullAnalysisTime' => 1784507199,
	'meta' => array (
  'cacheVersion' => 'v13-packageDependencies',
  'phpstanVersion' => '2.2.5',
  'fnsr' => false,
  'metaExtensions' => 
  array (
  ),
  'phpVersion' => 80329,
  'projectConfig' => '{conditionalTags: {Larastan\\Larastan\\Rules\\NoEnvCallsOutsideOfConfigRule: {phpstan.rules.rule: %noEnvCallsOutsideOfConfig%}, Larastan\\Larastan\\Rules\\NoModelMakeRule: {phpstan.rules.rule: %noModelMake%}, Larastan\\Larastan\\Rules\\NoUnnecessaryCollectionCallRule: {phpstan.rules.rule: %noUnnecessaryCollectionCall%}, Larastan\\Larastan\\Rules\\NoUnnecessaryEnumerableToArrayCallsRule: {phpstan.rules.rule: %noUnnecessaryEnumerableToArrayCalls%}, Larastan\\Larastan\\Rules\\OctaneCompatibilityRule: {phpstan.rules.rule: %checkOctaneCompatibility%}, Larastan\\Larastan\\Rules\\UnusedViewsRule: {phpstan.rules.rule: %checkUnusedViews%}, Larastan\\Larastan\\Rules\\NoMissingTranslationsRule: {phpstan.rules.rule: %checkMissingTranslations%}, Larastan\\Larastan\\Rules\\ModelAppendsRule: {phpstan.rules.rule: %checkModelAppends%}, Larastan\\Larastan\\Rules\\NoPublicModelScopeAndAccessorRule: {phpstan.rules.rule: %checkModelMethodVisibility%}, Larastan\\Larastan\\Rules\\NoAuthFacadeInRequestScopeRule: {phpstan.rules.rule: %checkAuthCallsWhenInRequestScope%}, Larastan\\Larastan\\Rules\\NoAuthHelperInRequestScopeRule: {phpstan.rules.rule: %checkAuthCallsWhenInRequestScope%}, Larastan\\Larastan\\ReturnTypes\\Helpers\\EnvFunctionDynamicFunctionReturnTypeExtension: {phpstan.broker.dynamicFunctionReturnTypeExtension: %generalizeEnvReturnType%}, Larastan\\Larastan\\ReturnTypes\\Helpers\\ConfigFunctionDynamicFunctionReturnTypeExtension: {phpstan.broker.dynamicFunctionReturnTypeExtension: %checkConfigTypes%}, Larastan\\Larastan\\ReturnTypes\\ConfigRepositoryDynamicMethodReturnTypeExtension: {phpstan.broker.dynamicMethodReturnTypeExtension: %checkConfigTypes%}, Larastan\\Larastan\\ReturnTypes\\ConfigFacadeCollectionDynamicStaticMethodReturnTypeExtension: {phpstan.broker.dynamicStaticMethodReturnTypeExtension: %checkConfigTypes%}, Larastan\\Larastan\\Rules\\ConfigCollectionRule: {phpstan.rules.rule: %checkConfigTypes%}}, parameters: {universalObjectCratesClasses: [Illuminate\\Http\\Request, Illuminate\\Support\\Optional], earlyTerminatingFunctionCalls: [abort, dd], mixinExcludeClasses: [Eloquent], bootstrapFiles: [bootstrap.php], checkOctaneCompatibility: false, noEnvCallsOutsideOfConfig: true, noModelMake: true, noUnnecessaryCollectionCall: true, noUnnecessaryCollectionCallOnly: [], noUnnecessaryCollectionCallExcept: [], noUnnecessaryEnumerableToArrayCalls: false, squashedMigrationsPath: [], databaseMigrationsPath: [], disableMigrationScan: false, disableSchemaScan: false, configDirectories: [], viewDirectories: [], translationDirectories: [], checkModelProperties: false, checkUnusedViews: false, checkMissingTranslations: false, checkModelAppends: true, checkModelMethodVisibility: false, generalizeEnvReturnType: false, checkConfigTypes: false, checkAuthCallsWhenInRequestScope: false, parseModelCastsMethod: false, enableMigrationCache: false, level: 8, paths: [/Users/ridwankadri/Desktop/code/atlas-erp/app, /Users/ridwankadri/Desktop/code/atlas-erp/routes, /Users/ridwankadri/Desktop/code/atlas-erp/database/factories, /Users/ridwankadri/Desktop/code/atlas-erp/database/seeders], excludePaths: {analyse: [bootstrap/cache/*, database/migrations/*, storage/*, public/build/*, vendor/*]}, tmpDir: /Users/ridwankadri/Desktop/code/atlas-erp/storage/phpstan, treatPhpDocTypesAsCertain: true}, rules: [Larastan\\Larastan\\Rules\\UselessConstructs\\NoUselessWithFunctionCallsRule, Larastan\\Larastan\\Rules\\UselessConstructs\\NoUselessValueFunctionCallsRule, Larastan\\Larastan\\Rules\\DeferrableServiceProviderMissingProvidesRule, Larastan\\Larastan\\Rules\\ConsoleCommand\\UndefinedArgumentOrOptionRule], services: {{class: Larastan\\Larastan\\Methods\\RelationForwardsCallsExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\ModelForwardsCallsExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\EloquentBuilderForwardsCallsExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\HigherOrderTapProxyExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\HigherOrderCollectionProxyExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\StorageMethodsClassReflectionExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\ContractsMethodsExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\FacadesMethodsExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\ManagersMethodsExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\AuthsMethodsExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\ModelFactoryMethodsClassReflectionExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\RedirectResponseMethodsClassReflectionExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\MacroMethodsClassReflectionExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Methods\\ViewWithMethodsClassReflectionExtension, tags: [phpstan.broker.methodsClassReflectionExtension]}, {class: Larastan\\Larastan\\Properties\\ModelAccessorExtension, tags: [phpstan.broker.propertiesClassReflectionExtension]}, {class: Larastan\\Larastan\\Properties\\ModelPropertyExtension, tags: [phpstan.broker.propertiesClassReflectionExtension]}, {class: Larastan\\Larastan\\Properties\\HigherOrderCollectionProxyPropertyExtension, tags: [phpstan.broker.propertiesClassReflectionExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\HigherOrderTapProxyExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\ContainerArrayAccessDynamicMethodReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension], arguments: {className: Illuminate\\Contracts\\Container\\Container}}, {class: Larastan\\Larastan\\ReturnTypes\\ContainerArrayAccessDynamicMethodReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension], arguments: {className: Illuminate\\Container\\Container}}, {class: Larastan\\Larastan\\ReturnTypes\\ContainerArrayAccessDynamicMethodReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension], arguments: {className: Illuminate\\Foundation\\Application}}, {class: Larastan\\Larastan\\ReturnTypes\\ContainerArrayAccessDynamicMethodReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension], arguments: {className: Illuminate\\Contracts\\Foundation\\Application}}, {class: Larastan\\Larastan\\Properties\\ModelRelationsExtension, tags: [phpstan.broker.propertiesClassReflectionExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\ModelOnlyDynamicMethodReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\ModelFactoryDynamicStaticMethodReturnTypeExtension, tags: [phpstan.broker.dynamicStaticMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\ModelDynamicStaticMethodReturnTypeExtension, tags: [phpstan.broker.dynamicStaticMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\AppMakeDynamicReturnTypeExtension, tags: [phpstan.broker.dynamicStaticMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\AuthExtension, tags: [phpstan.broker.dynamicStaticMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\GuardDynamicStaticMethodReturnTypeExtension, tags: [phpstan.broker.dynamicStaticMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\AuthManagerExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\DateExtension, tags: [phpstan.broker.dynamicStaticMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\GuardExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\RequestFileExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\RequestRouteExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\RequestUserExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\EloquentBuilderExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\RelationCollectionExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\TestCaseExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\Support\\CollectionHelper}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\AuthExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\CollectExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\NowAndTodayExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\ResponseExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\ValidatorExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\LiteralExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\CollectionFilterRejectDynamicReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\CollectionWhereNotNullDynamicReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\NewModelQueryDynamicMethodReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\FactoryDynamicMethodReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\Types\\AbortIfFunctionTypeSpecifyingExtension, tags: [phpstan.typeSpecifier.functionTypeSpecifyingExtension], arguments: {methodName: abort, negate: false}}, {class: Larastan\\Larastan\\Types\\AbortIfFunctionTypeSpecifyingExtension, tags: [phpstan.typeSpecifier.functionTypeSpecifyingExtension], arguments: {methodName: abort, negate: true}}, {class: Larastan\\Larastan\\Types\\AbortIfFunctionTypeSpecifyingExtension, tags: [phpstan.typeSpecifier.functionTypeSpecifyingExtension], arguments: {methodName: throw, negate: false}}, {class: Larastan\\Larastan\\Types\\AbortIfFunctionTypeSpecifyingExtension, tags: [phpstan.typeSpecifier.functionTypeSpecifyingExtension], arguments: {methodName: throw, negate: true}}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\AppExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\ValueExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\StrExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\TapExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\StorageDynamicStaticMethodReturnTypeExtension, tags: [phpstan.broker.dynamicStaticMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\Types\\GenericEloquentCollectionTypeNodeResolverExtension, tags: [phpstan.phpDoc.typeNodeResolverExtension]}, {class: Larastan\\Larastan\\Types\\ViewStringTypeNodeResolverExtension, tags: [phpstan.phpDoc.typeNodeResolverExtension]}, {class: Larastan\\Larastan\\Rules\\OctaneCompatibilityRule}, {class: Larastan\\Larastan\\Rules\\NoEnvCallsOutsideOfConfigRule, arguments: {configDirectories: %configDirectories%}}, {class: Larastan\\Larastan\\Rules\\NoModelMakeRule}, {class: Larastan\\Larastan\\Rules\\NoUnnecessaryCollectionCallRule, arguments: {onlyMethods: %noUnnecessaryCollectionCallOnly%, excludeMethods: %noUnnecessaryCollectionCallExcept%}}, {class: Larastan\\Larastan\\Rules\\NoUnnecessaryEnumerableToArrayCallsRule}, {class: Larastan\\Larastan\\Rules\\ModelAppendsRule}, {class: Larastan\\Larastan\\Rules\\NoPublicModelScopeAndAccessorRule}, {class: Larastan\\Larastan\\Types\\GenericEloquentBuilderTypeNodeResolverExtension, tags: [phpstan.phpDoc.typeNodeResolverExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\AppEnvironmentReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension], arguments: {class: Illuminate\\Foundation\\Application}}, {class: Larastan\\Larastan\\ReturnTypes\\AppEnvironmentReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension], arguments: {class: Illuminate\\Contracts\\Foundation\\Application}}, {class: Larastan\\Larastan\\ReturnTypes\\AppFacadeEnvironmentReturnTypeExtension, tags: [phpstan.broker.dynamicStaticMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\Types\\ModelProperty\\ModelPropertyTypeNodeResolverExtension, tags: [phpstan.phpDoc.typeNodeResolverExtension], arguments: {active: %checkModelProperties%}}, {class: Larastan\\Larastan\\Types\\CollectionOf\\CollectionOfTypeNodeResolverExtension, tags: [phpstan.phpDoc.typeNodeResolverExtension]}, {class: Larastan\\Larastan\\Properties\\MigrationHelper, arguments: {databaseMigrationPath: %databaseMigrationsPath%, disableMigrationScan: %disableMigrationScan%, parser: @migrationsParser, reflectionProvider: @reflectionProvider}}, iamcalSqlParser: {class: Larastan\\Larastan\\SQL\\IamcalSqlParser, autowired: false}, sqlParserFactory: {class: Larastan\\Larastan\\SQL\\SqlParserFactory, arguments: {iamcalSqlParser: @iamcalSqlParser}}, sqlParser: {type: Larastan\\Larastan\\SQL\\SqlParser, factory: [@sqlParserFactory, create]}, {class: Larastan\\Larastan\\Properties\\SquashedMigrationHelper, arguments: {schemaPaths: %squashedMigrationsPath%, disableSchemaScan: %disableSchemaScan%}}, {class: Larastan\\Larastan\\Properties\\ModelCastHelper, arguments: {parser: @currentPhpVersionSimpleDirectParser, parseModelCastsMethod: %parseModelCastsMethod%}}, {class: Larastan\\Larastan\\Properties\\MigrationCache, arguments: {cacheDirectory: %tmpDir%, enabled: %enableMigrationCache%}}, {class: Larastan\\Larastan\\Properties\\ModelPropertyHelper}, {class: Larastan\\Larastan\\Rules\\ModelRuleHelper}, {class: Larastan\\Larastan\\Methods\\BuilderHelper, arguments: {checkProperties: %checkModelProperties%}}, {class: Larastan\\Larastan\\Rules\\RelationExistenceRule, tags: [phpstan.rules.rule]}, {class: Larastan\\Larastan\\Rules\\CheckDispatchArgumentTypesCompatibleWithClassConstructorRule, arguments: {dispatchableClass: Illuminate\\Foundation\\Bus\\Dispatchable}, tags: [phpstan.rules.rule]}, {class: Larastan\\Larastan\\Rules\\CheckDispatchArgumentTypesCompatibleWithClassConstructorRule, arguments: {dispatchableClass: Illuminate\\Foundation\\Events\\Dispatchable}, tags: [phpstan.rules.rule]}, {class: Larastan\\Larastan\\Properties\\Schema\\MySqlDataTypeToPhpTypeConverter}, {class: Larastan\\Larastan\\LarastanStubFilesExtension, tags: [phpstan.stubFilesExtension]}, {class: Larastan\\Larastan\\Rules\\UnusedViewsRule}, {class: Larastan\\Larastan\\Collectors\\UsedViewFunctionCollector, tags: [phpstan.collector]}, {class: Larastan\\Larastan\\Collectors\\UsedEmailViewCollector, tags: [phpstan.collector]}, {class: Larastan\\Larastan\\Collectors\\UsedViewMakeCollector, tags: [phpstan.collector]}, {class: Larastan\\Larastan\\Collectors\\UsedViewFacadeMakeCollector, tags: [phpstan.collector]}, {class: Larastan\\Larastan\\Collectors\\UsedRouteFacadeViewCollector, tags: [phpstan.collector]}, {class: Larastan\\Larastan\\Collectors\\UsedViewInAnotherViewCollector}, {class: Larastan\\Larastan\\Support\\ViewFileHelper, arguments: {viewDirectories: %viewDirectories%}}, {class: Larastan\\Larastan\\Support\\ViewParser, arguments: {parser: @currentPhpVersionSimpleDirectParser}}, {class: Larastan\\Larastan\\Rules\\NoMissingTranslationsRule, arguments: {translationDirectories: %translationDirectories%}}, {class: Larastan\\Larastan\\Collectors\\UsedTranslationFunctionCollector, tags: [phpstan.collector]}, {class: Larastan\\Larastan\\Collectors\\UsedTranslationTranslatorCollector, tags: [phpstan.collector]}, {class: Larastan\\Larastan\\Collectors\\UsedTranslationFacadeCollector, tags: [phpstan.collector]}, {class: Larastan\\Larastan\\Collectors\\UsedTranslationViewCollector}, {class: Larastan\\Larastan\\ReturnTypes\\ApplicationMakeDynamicReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\ContainerMakeDynamicReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\ConsoleCommand\\ArgumentDynamicReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\ConsoleCommand\\HasArgumentDynamicReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\ConsoleCommand\\OptionDynamicReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\ConsoleCommand\\HasOptionDynamicReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\TranslatorGetReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\LangGetReturnTypeExtension, tags: [phpstan.broker.dynamicStaticMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\TransHelperReturnTypeExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\DoubleUnderscoreHelperReturnTypeExtension, tags: [phpstan.broker.dynamicFunctionReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\AppMakeHelper}, {class: Larastan\\Larastan\\Internal\\ConsoleApplicationResolver}, {class: Larastan\\Larastan\\Internal\\ConsoleApplicationHelper}, {class: Larastan\\Larastan\\Support\\HigherOrderCollectionProxyHelper}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\ConfigFunctionDynamicFunctionReturnTypeExtension}, {class: Larastan\\Larastan\\ReturnTypes\\ConfigRepositoryDynamicMethodReturnTypeExtension}, {class: Larastan\\Larastan\\ReturnTypes\\ConfigFacadeCollectionDynamicStaticMethodReturnTypeExtension}, {class: Larastan\\Larastan\\Support\\ConfigParser, arguments: {parser: @currentPhpVersionSimpleDirectParser, configPaths: %configDirectories%, treatPhpDocTypesAsCertain: %treatPhpDocTypesAsCertain%}}, {class: Larastan\\Larastan\\Internal\\ConfigHelper}, {class: Larastan\\Larastan\\ReturnTypes\\Helpers\\EnvFunctionDynamicFunctionReturnTypeExtension}, {class: Larastan\\Larastan\\ReturnTypes\\FormRequestSafeDynamicMethodReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\ReturnTypes\\EloquentCollectionMapDynamicReturnTypeExtension, tags: [phpstan.broker.dynamicMethodReturnTypeExtension]}, {class: Larastan\\Larastan\\Rules\\NoAuthFacadeInRequestScopeRule}, {class: Larastan\\Larastan\\Rules\\NoAuthHelperInRequestScopeRule}, {class: Larastan\\Larastan\\Rules\\ConfigCollectionRule}, {class: Illuminate\\Filesystem\\Filesystem, autowired: self}, migrationsParser: {class: PHPStan\\Parser\\CachedParser, arguments: {originalParser: @currentPhpVersionSimpleDirectParser, cachedNodesByStringCountMax: %cache.nodesByStringCountMax%}, autowired: false}}}',
  'analysedPaths' => 
  array (
    0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app',
    1 => '/Users/ridwankadri/Desktop/code/atlas-erp/routes',
    2 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories',
    3 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders',
  ),
  'scannedFiles' => 
  array (
  ),
  'composerLocks' => 
  array (
    '/Users/ridwankadri/Desktop/code/atlas-erp/composer.lock' => '371796a89d4766ee26fad5a4c244f24a3f00e1ff4a09c23f2cb7ab7c6408c249',
  ),
  'composerInstalled' => 
  array (
    '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/installed.php' => 
    array (
      'versions' => 
      array (
        'anourvalar/eloquent-serialize' => 
        array (
          'pretty_version' => '1.3.10',
          'version' => '1.3.10.0',
          'reference' => '2be26f176b764a2d6f20118bfa4b125f71fa88f8',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../anourvalar/eloquent-serialize',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'blade-ui-kit/blade-heroicons' => 
        array (
          'pretty_version' => '2.7.0',
          'version' => '2.7.0.0',
          'reference' => '66fa8ba09dba12e0cdb410b8cb94f3b890eca440',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../blade-ui-kit/blade-heroicons',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'blade-ui-kit/blade-icons' => 
        array (
          'pretty_version' => '1.10.1',
          'version' => '1.10.1.0',
          'reference' => '6e072d021ea6249986c330b93293c33d0c4f0e34',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../blade-ui-kit/blade-icons',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'brianium/paratest' => 
        array (
          'pretty_version' => 'v7.8.5',
          'version' => '7.8.5.0',
          'reference' => '9b324c8fc319cf9728b581c7a90e1c8f6361c5e5',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../brianium/paratest',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'brick/math' => 
        array (
          'pretty_version' => '0.14.8',
          'version' => '0.14.8.0',
          'reference' => '63422359a44b7f06cae63c3b429b59e8efcc0629',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../brick/math',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'carbonphp/carbon-doctrine-types' => 
        array (
          'pretty_version' => '3.2.0',
          'version' => '3.2.0.0',
          'reference' => '18ba5ddfec8976260ead6e866180bd5d2f71aa1d',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../carbonphp/carbon-doctrine-types',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'chillerlan/php-qrcode' => 
        array (
          'pretty_version' => '5.0.5',
          'version' => '5.0.5.0',
          'reference' => '7b66282572fc14075c0507d74d9837dab25b38d6',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../chillerlan/php-qrcode',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'chillerlan/php-settings-container' => 
        array (
          'pretty_version' => '3.3.0',
          'version' => '3.3.0.0',
          'reference' => 'a0a487cbf5344f721eb504bf0f59bada40c381b7',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../chillerlan/php-settings-container',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'composer/pcre' => 
        array (
          'pretty_version' => '3.4.0',
          'version' => '3.4.0.0',
          'reference' => 'd5a341b3fb61f3001970940afb1d332968a183ed',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/./pcre',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'composer/semver' => 
        array (
          'pretty_version' => '3.4.4',
          'version' => '3.4.4.0',
          'reference' => '198166618906cb2de69b95d7d47e5fa8aa1b2b95',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/./semver',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'cordoval/hamcrest-php' => 
        array (
          'dev_requirement' => true,
          'replaced' => 
          array (
            0 => '*',
          ),
        ),
        'danharrin/date-format-converter' => 
        array (
          'pretty_version' => 'v0.3.1',
          'version' => '0.3.1.0',
          'reference' => '7c31171bc981e48726729a5f3a05a2d2b63f0b1e',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../danharrin/date-format-converter',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'danharrin/livewire-rate-limiting' => 
        array (
          'pretty_version' => 'v2.2.0',
          'version' => '2.2.0.0',
          'reference' => 'c03e649220089f6e5a52d422e24e3f98c73e456d',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../danharrin/livewire-rate-limiting',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'davedevelopment/hamcrest-php' => 
        array (
          'dev_requirement' => true,
          'replaced' => 
          array (
            0 => '*',
          ),
        ),
        'dflydev/dot-access-data' => 
        array (
          'pretty_version' => 'v3.0.3',
          'version' => '3.0.3.0',
          'reference' => 'a23a2bf4f31d3518f3ecb38660c95715dfead60f',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../dflydev/dot-access-data',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'doctrine/deprecations' => 
        array (
          'pretty_version' => '1.1.6',
          'version' => '1.1.6.0',
          'reference' => 'd4fe3e6fd9bb9e72557a19674f44d8ac7db4c6ca',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../doctrine/deprecations',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'doctrine/inflector' => 
        array (
          'pretty_version' => '2.1.0',
          'version' => '2.1.0.0',
          'reference' => '6d6c96277ea252fc1304627204c3d5e6e15faa3b',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../doctrine/inflector',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'doctrine/lexer' => 
        array (
          'pretty_version' => '3.0.1',
          'version' => '3.0.1.0',
          'reference' => '31ad66abc0fc9e1a1f2d9bc6a42668d2fbbcd6dd',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../doctrine/lexer',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'dragonmantank/cron-expression' => 
        array (
          'pretty_version' => 'v3.6.0',
          'version' => '3.6.0.0',
          'reference' => 'd61a8a9604ec1f8c3d150d09db6ce98b32675013',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../dragonmantank/cron-expression',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'egulias/email-validator' => 
        array (
          'pretty_version' => '4.0.4',
          'version' => '4.0.4.0',
          'reference' => 'd42c8731f0624ad6bdc8d3e5e9a4524f68801cfa',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../egulias/email-validator',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'ezyang/htmlpurifier' => 
        array (
          'pretty_version' => 'v4.19.0',
          'version' => '4.19.0.0',
          'reference' => 'b287d2a16aceffbf6e0295559b39662612b77fcf',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../ezyang/htmlpurifier',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'fakerphp/faker' => 
        array (
          'pretty_version' => 'v1.24.1',
          'version' => '1.24.1.0',
          'reference' => 'e0ee18eb1e6dc3cda3ce9fd97e5a0689a88a64b5',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../fakerphp/faker',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'fidry/cpu-core-counter' => 
        array (
          'pretty_version' => '1.3.0',
          'version' => '1.3.0.0',
          'reference' => 'db9508f7b1474469d9d3c53b86f817e344732678',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../fidry/cpu-core-counter',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'filament/actions' => 
        array (
          'pretty_version' => 'v4.12.1',
          'version' => '4.12.1.0',
          'reference' => 'f6f527992a1b91ad0152b558cdd5d5172a6b996e',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/actions',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'filament/filament' => 
        array (
          'pretty_version' => 'v4.12.1',
          'version' => '4.12.1.0',
          'reference' => 'b1a6ec2e98982c89d4197997c1d0554610fd862d',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/filament',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'filament/forms' => 
        array (
          'pretty_version' => 'v4.12.1',
          'version' => '4.12.1.0',
          'reference' => 'edba08804e3e60fed8806e08b2470287d8e08706',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/forms',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'filament/infolists' => 
        array (
          'pretty_version' => 'v4.12.1',
          'version' => '4.12.1.0',
          'reference' => '78e792e8336760fbe165442d0e112f9f27036a99',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/infolists',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'filament/notifications' => 
        array (
          'pretty_version' => 'v4.12.1',
          'version' => '4.12.1.0',
          'reference' => '5b5907691ef6e735d17b7c96fbad3a595e8fc9f6',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/notifications',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'filament/query-builder' => 
        array (
          'pretty_version' => 'v4.12.1',
          'version' => '4.12.1.0',
          'reference' => '9882be4ecb062878f6eacf46468d72b31563b492',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/query-builder',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'filament/schemas' => 
        array (
          'pretty_version' => 'v4.12.1',
          'version' => '4.12.1.0',
          'reference' => 'e9b736636fae793684faad145e522a3ae46f0c99',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/schemas',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'filament/support' => 
        array (
          'pretty_version' => 'v4.12.1',
          'version' => '4.12.1.0',
          'reference' => '4ed5a7239c149b3fdc5c068008321097397f3341',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/support',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'filament/tables' => 
        array (
          'pretty_version' => 'v4.12.1',
          'version' => '4.12.1.0',
          'reference' => 'a7a1206d0e3b5c61b8ca5e4bffe812a990122ebe',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/tables',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'filament/widgets' => 
        array (
          'pretty_version' => 'v4.12.1',
          'version' => '4.12.1.0',
          'reference' => '19f1367770b5790e5ba3a11a6a37b6ca0534be9d',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filament/widgets',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'filp/whoops' => 
        array (
          'pretty_version' => '2.18.4',
          'version' => '2.18.4.0',
          'reference' => 'd2102955e48b9fd9ab24280a7ad12ed552752c4d',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../filp/whoops',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'fruitcake/php-cors' => 
        array (
          'pretty_version' => 'v1.4.0',
          'version' => '1.4.0.0',
          'reference' => '38aaa6c3fd4c157ffe2a4d10aa8b9b16ba8de379',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../fruitcake/php-cors',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'graham-campbell/result-type' => 
        array (
          'pretty_version' => 'v1.1.4',
          'version' => '1.1.4.0',
          'reference' => 'e01f4a821471308ba86aa202fed6698b6b695e3b',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../graham-campbell/result-type',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'guzzlehttp/guzzle' => 
        array (
          'pretty_version' => '7.15.1',
          'version' => '7.15.1.0',
          'reference' => '61443dfb33c62f308ee8add20f45b4d6e4bf8d2f',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../guzzlehttp/guzzle',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'guzzlehttp/promises' => 
        array (
          'pretty_version' => '2.5.1',
          'version' => '2.5.1.0',
          'reference' => '9ad1e4fc607446a055b95870c7f668e93b5cff29',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../guzzlehttp/promises',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'guzzlehttp/psr7' => 
        array (
          'pretty_version' => '2.13.0',
          'version' => '2.13.0.0',
          'reference' => 'dad89620b7a6edb60c15858442eb2e408b45d8f4',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../guzzlehttp/psr7',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'guzzlehttp/uri-template' => 
        array (
          'pretty_version' => 'v1.0.10',
          'version' => '1.0.10.0',
          'reference' => 'f6c24c21f42b990e9a58912b332d0874df6ba839',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../guzzlehttp/uri-template',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'hamcrest/hamcrest-php' => 
        array (
          'pretty_version' => 'v2.1.1',
          'version' => '2.1.1.0',
          'reference' => 'f8b1c0173b22fa6ec77a81fe63e5b01eba7e6487',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../hamcrest/hamcrest-php',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'iamcal/sql-parser' => 
        array (
          'pretty_version' => 'v0.7',
          'version' => '0.7.0.0',
          'reference' => '610392f38de49a44dab08dc1659960a29874c4b8',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../iamcal/sql-parser',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'illuminate/auth' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/broadcasting' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/bus' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/cache' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/collections' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/concurrency' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/conditionable' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/config' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/console' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/container' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/contracts' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/cookie' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/database' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/encryption' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/events' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/filesystem' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/hashing' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/http' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/json-schema' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/log' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/macroable' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/mail' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/notifications' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/pagination' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/pipeline' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/process' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/queue' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/redis' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/reflection' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/routing' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/session' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/support' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/testing' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/translation' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/validation' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'illuminate/view' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => 'v12.64.0',
          ),
        ),
        'jean85/pretty-package-versions' => 
        array (
          'pretty_version' => '2.1.1',
          'version' => '2.1.1.0',
          'reference' => '4d7aa5dab42e2a76d99559706022885de0e18e1a',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../jean85/pretty-package-versions',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'kirschbaum-development/eloquent-power-joins' => 
        array (
          'pretty_version' => '4.3.2',
          'version' => '4.3.2.0',
          'reference' => '33c189bd51a510c1ceba67222395ead08a29863a',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../kirschbaum-development/eloquent-power-joins',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'kodova/hamcrest-php' => 
        array (
          'dev_requirement' => true,
          'replaced' => 
          array (
            0 => '*',
          ),
        ),
        'larastan/larastan' => 
        array (
          'pretty_version' => 'v3.10.0',
          'version' => '3.10.0.0',
          'reference' => '2970f83398154178a739609c244577267c7ee8eb',
          'type' => 'phpstan-extension',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../larastan/larastan',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'laravel/breeze' => 
        array (
          'pretty_version' => 'v2.4.2',
          'version' => '2.4.2.0',
          'reference' => '4f20e7b2cc8d25daa85d8647241a89c8e0930305',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../laravel/breeze',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'laravel/framework' => 
        array (
          'pretty_version' => 'v12.64.0',
          'version' => '12.64.0.0',
          'reference' => '727a8ea2949c23ca8b5316b86a00984b6017b7a0',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../laravel/framework',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'laravel/pail' => 
        array (
          'pretty_version' => 'v1.2.7',
          'version' => '1.2.7.0',
          'reference' => '2f7d27dada8effc48b8c424445a69cca7007daaa',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../laravel/pail',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'laravel/pint' => 
        array (
          'pretty_version' => 'v1.29.3',
          'version' => '1.29.3.0',
          'reference' => 'da1d1111a6aa2e082d2a388b194afe1ba0a05d14',
          'type' => 'project',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../laravel/pint',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'laravel/prompts' => 
        array (
          'pretty_version' => 'v0.3.21',
          'version' => '0.3.21.0',
          'reference' => '7753c65c281c2550c7c183f14e18062073b7d821',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../laravel/prompts',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'laravel/sail' => 
        array (
          'pretty_version' => 'v1.63.0',
          'version' => '1.63.0.0',
          'reference' => '51bbce3f803c1d386cabbb44e618c955a12ff5fc',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../laravel/sail',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'laravel/serializable-closure' => 
        array (
          'pretty_version' => 'v2.0.13',
          'version' => '2.0.13.0',
          'reference' => 'b566ee0dd251f3c4078bed003a7ce015f5ea6dce',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../laravel/serializable-closure',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'laravel/tinker' => 
        array (
          'pretty_version' => 'v2.11.1',
          'version' => '2.11.1.0',
          'reference' => 'c9f80cc835649b5c1842898fb043f8cc098dd741',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../laravel/tinker',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'league/commonmark' => 
        array (
          'pretty_version' => '2.8.3',
          'version' => '2.8.3.0',
          'reference' => '1902f60f984235023acbe03db6ad614a37b3c3e7',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../league/commonmark',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'league/config' => 
        array (
          'pretty_version' => 'v1.2.0',
          'version' => '1.2.0.0',
          'reference' => '754b3604fb2984c71f4af4a9cbe7b57f346ec1f3',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../league/config',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'league/csv' => 
        array (
          'pretty_version' => '9.28.0',
          'version' => '9.28.0.0',
          'reference' => '6582ace29ae09ba5b07049d40ea13eb19c8b5073',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../league/csv',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'league/flysystem' => 
        array (
          'pretty_version' => '3.35.2',
          'version' => '3.35.2.0',
          'reference' => 'b277b5dc3d56650b68904117124e79c851e12376',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../league/flysystem',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'league/flysystem-local' => 
        array (
          'pretty_version' => '3.31.0',
          'version' => '3.31.0.0',
          'reference' => '2f669db18a4c20c755c2bb7d3a7b0b2340488079',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../league/flysystem-local',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'league/mime-type-detection' => 
        array (
          'pretty_version' => '1.17.0',
          'version' => '1.17.0.0',
          'reference' => 'f5f47eff7c48ed1003069a2ca67f316fb4021c76',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../league/mime-type-detection',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'league/uri' => 
        array (
          'pretty_version' => '7.8.1',
          'version' => '7.8.1.0',
          'reference' => '08cf38e3924d4f56238125547b5720496fac8fd4',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../league/uri',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'league/uri-components' => 
        array (
          'pretty_version' => '7.8.1',
          'version' => '7.8.1.0',
          'reference' => '848ff9db2f0be06229d6034b7c2e33d41b4fd675',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../league/uri-components',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'league/uri-interfaces' => 
        array (
          'pretty_version' => '7.8.1',
          'version' => '7.8.1.0',
          'reference' => '85d5c77c5d6d3af6c54db4a78246364908f3c928',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../league/uri-interfaces',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'livewire/livewire' => 
        array (
          'pretty_version' => 'v3.8.2',
          'version' => '3.8.2.0',
          'reference' => 'e77fce60d0615d68dc6b8fafe98a6739d9752a24',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../livewire/livewire',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'livewire/volt' => 
        array (
          'pretty_version' => 'v1.10.5',
          'version' => '1.10.5.0',
          'reference' => '32a111951779f9dcf2a08a5704acb940ac9a146c',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../livewire/volt',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'maatwebsite/excel' => 
        array (
          'pretty_version' => '3.1.69',
          'version' => '3.1.69.0',
          'reference' => 'ae5d65b7c9a2fac43bff4d44f796ac95d7a8e760',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../maatwebsite/excel',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'maennchen/zipstream-php' => 
        array (
          'pretty_version' => '3.2.2',
          'version' => '3.2.2.0',
          'reference' => '77bebeb4c6c340bb3c11c843b2cffd8bbfde4d5e',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../maennchen/zipstream-php',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'markbaker/complex' => 
        array (
          'pretty_version' => '3.0.2',
          'version' => '3.0.2.0',
          'reference' => '95c56caa1cf5c766ad6d65b6344b807c1e8405b9',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../markbaker/complex',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'markbaker/matrix' => 
        array (
          'pretty_version' => '3.0.1',
          'version' => '3.0.1.0',
          'reference' => '728434227fe21be27ff6d86621a1b13107a2562c',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../markbaker/matrix',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'masterminds/html5' => 
        array (
          'pretty_version' => '2.10.1',
          'version' => '2.10.1.0',
          'reference' => 'fd5018f6815fff903946d0564977b44ce8010e29',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../masterminds/html5',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'mockery/mockery' => 
        array (
          'pretty_version' => '1.6.12',
          'version' => '1.6.12.0',
          'reference' => '1f4efdd7d3beafe9807b08156dfcb176d18f1699',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../mockery/mockery',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'monolog/monolog' => 
        array (
          'pretty_version' => '3.10.0',
          'version' => '3.10.0.0',
          'reference' => 'b321dd6749f0bf7189444158a3ce785cc16d69b0',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../monolog/monolog',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'mtdowling/cron-expression' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => '^1.0',
          ),
        ),
        'myclabs/deep-copy' => 
        array (
          'pretty_version' => '1.13.4',
          'version' => '1.13.4.0',
          'reference' => '07d290f0c47959fd5eed98c95ee5602db07e0b6a',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../myclabs/deep-copy',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'nesbot/carbon' => 
        array (
          'pretty_version' => '3.13.1',
          'version' => '3.13.1.0',
          'reference' => '2937ad3d1d2c506fd2bc97d571438a95641f44e2',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../nesbot/carbon',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'nette/php-generator' => 
        array (
          'pretty_version' => 'v4.2.2',
          'version' => '4.2.2.0',
          'reference' => '0d7060926f5c3e8c488b9b9ced42d857f12a34b5',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../nette/php-generator',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'nette/schema' => 
        array (
          'pretty_version' => 'v1.3.5',
          'version' => '1.3.5.0',
          'reference' => 'f0ab1a3cda782dbc5da270d28545236aa80c4002',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../nette/schema',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'nette/utils' => 
        array (
          'pretty_version' => 'v4.1.5',
          'version' => '4.1.5.0',
          'reference' => 'b043439dbdf954e6c28b5ea7e34b0100f83165e0',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../nette/utils',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'nikic/php-parser' => 
        array (
          'pretty_version' => 'v5.8.0',
          'version' => '5.8.0.0',
          'reference' => '044a6a392ff8ad0d61f14370a5fbbd0a0107152f',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../nikic/php-parser',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'nunomaduro/collision' => 
        array (
          'pretty_version' => 'v8.9.5',
          'version' => '8.9.5.0',
          'reference' => 'fb53eacd509a1d303858e2d20cfebf2d630254ec',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../nunomaduro/collision',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'nunomaduro/termwind' => 
        array (
          'pretty_version' => 'v2.4.0',
          'version' => '2.4.0.0',
          'reference' => '712a31b768f5daea284c2169a7d227031001b9a8',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../nunomaduro/termwind',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'openspout/openspout' => 
        array (
          'pretty_version' => 'v4.32.0',
          'version' => '4.32.0.0',
          'reference' => '41f045c1f632e1474e15d4c7bc3abcb4a153563d',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../openspout/openspout',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'paragonie/constant_time_encoding' => 
        array (
          'pretty_version' => 'v3.1.3',
          'version' => '3.1.3.0',
          'reference' => 'd5b01a39b3415c2cd581d3bd3a3575c1ebbd8e77',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../paragonie/constant_time_encoding',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'pestphp/pest' => 
        array (
          'pretty_version' => 'v3.8.7',
          'version' => '3.8.7.0',
          'reference' => 'f108313b52e8c28dc7121ce34303f817a3790202',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../pestphp/pest',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'pestphp/pest-plugin' => 
        array (
          'pretty_version' => 'v3.0.0',
          'version' => '3.0.0.0',
          'reference' => 'e79b26c65bc11c41093b10150c1341cc5cdbea83',
          'type' => 'composer-plugin',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../pestphp/pest-plugin',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'pestphp/pest-plugin-arch' => 
        array (
          'pretty_version' => 'v3.1.1',
          'version' => '3.1.1.0',
          'reference' => 'db7bd9cb1612b223e16618d85475c6f63b9c8daa',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../pestphp/pest-plugin-arch',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'pestphp/pest-plugin-laravel' => 
        array (
          'pretty_version' => 'v3.2.0',
          'version' => '3.2.0.0',
          'reference' => '6801be82fd92b96e82dd72e563e5674b1ce365fc',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../pestphp/pest-plugin-laravel',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'pestphp/pest-plugin-mutate' => 
        array (
          'pretty_version' => 'v3.0.5',
          'version' => '3.0.5.0',
          'reference' => 'e10dbdc98c9e2f3890095b4fe2144f63a5717e08',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../pestphp/pest-plugin-mutate',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phar-io/manifest' => 
        array (
          'pretty_version' => '2.0.4',
          'version' => '2.0.4.0',
          'reference' => '54750ef60c58e43759730615a392c31c80e23176',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phar-io/manifest',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phar-io/version' => 
        array (
          'pretty_version' => '3.2.1',
          'version' => '3.2.1.0',
          'reference' => '4f7fd7836c6f332bb2933569e566a0d6c4cbed74',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phar-io/version',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpdocumentor/reflection-common' => 
        array (
          'pretty_version' => '2.2.0',
          'version' => '2.2.0.0',
          'reference' => '1d01c49d4ed62f25aa84a747ad35d5a16924662b',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpdocumentor/reflection-common',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpdocumentor/reflection-docblock' => 
        array (
          'pretty_version' => '6.0.3',
          'version' => '6.0.3.0',
          'reference' => '7bae67520aa9f5ecc506d646810bd40d9da54582',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpdocumentor/reflection-docblock',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpdocumentor/type-resolver' => 
        array (
          'pretty_version' => '2.0.0',
          'version' => '2.0.0.0',
          'reference' => '327a05bbee54120d4786a0dc67aad30226ad4cf9',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpdocumentor/type-resolver',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpoffice/phpspreadsheet' => 
        array (
          'pretty_version' => '1.30.6',
          'version' => '1.30.6.0',
          'reference' => 'a416375ffc8bf5b661c1bb4e6c60d8f3fddbe5ce',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpoffice/phpspreadsheet',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'phpoption/phpoption' => 
        array (
          'pretty_version' => '1.9.5',
          'version' => '1.9.5.0',
          'reference' => '75365b91986c2405cf5e1e012c5595cd487a98be',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpoption/phpoption',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'phpstan/phpdoc-parser' => 
        array (
          'pretty_version' => '2.3.3',
          'version' => '2.3.3.0',
          'reference' => 'fb19eedd2bb67ff8cf7a5502ad329e701d6398a3',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpstan/phpdoc-parser',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpstan/phpstan' => 
        array (
          'pretty_version' => '2.2.5',
          'version' => '2.2.5.0',
          'reference' => '909c1e5fef7989ac0d0c1c5c42e32a5c4f6198a0',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpstan/phpstan',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpunit/php-code-coverage' => 
        array (
          'pretty_version' => '11.0.12',
          'version' => '11.0.12.0',
          'reference' => '2c1ed04922802c15e1de5d7447b4856de949cf56',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpunit/php-code-coverage',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpunit/php-file-iterator' => 
        array (
          'pretty_version' => '5.1.1',
          'version' => '5.1.1.0',
          'reference' => '2f3a64888c814fc235386b7387dd5b5ed92ad903',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpunit/php-file-iterator',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpunit/php-invoker' => 
        array (
          'pretty_version' => '5.0.1',
          'version' => '5.0.1.0',
          'reference' => 'c1ca3814734c07492b3d4c5f794f4b0995333da2',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpunit/php-invoker',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpunit/php-text-template' => 
        array (
          'pretty_version' => '4.0.1',
          'version' => '4.0.1.0',
          'reference' => '3e0404dc6b300e6bf56415467ebcb3fe4f33e964',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpunit/php-text-template',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpunit/php-timer' => 
        array (
          'pretty_version' => '7.0.1',
          'version' => '7.0.1.0',
          'reference' => '3b415def83fbcb41f991d9ebf16ae4ad8b7837b3',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpunit/php-timer',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'phpunit/phpunit' => 
        array (
          'pretty_version' => '11.5.56',
          'version' => '11.5.56.0',
          'reference' => '5f83edffa6967c3db468d48a695ec7bcb02e9256',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../phpunit/phpunit',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'pragmarx/google2fa' => 
        array (
          'pretty_version' => 'v9.0.0',
          'version' => '9.0.0.0',
          'reference' => 'e6bc62dd6ae83acc475f57912e27466019a1f2cf',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../pragmarx/google2fa',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'pragmarx/google2fa-qrcode' => 
        array (
          'pretty_version' => 'v4.0.0',
          'version' => '4.0.0.0',
          'reference' => '16159f84fa0838c276f35d46de57fd90dfbb385c',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../pragmarx/google2fa-qrcode',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'psr/clock' => 
        array (
          'pretty_version' => '1.0.0',
          'version' => '1.0.0.0',
          'reference' => 'e41a24703d4560fd0acb709162f73b8adfc3aa0d',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../psr/clock',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'psr/clock-implementation' => 
        array (
          'dev_requirement' => false,
          'provided' => 
          array (
            0 => '1.0',
          ),
        ),
        'psr/container' => 
        array (
          'pretty_version' => '2.0.2',
          'version' => '2.0.2.0',
          'reference' => 'c71ecc56dfe541dbd90c5360474fbc405f8d5963',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../psr/container',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'psr/container-implementation' => 
        array (
          'dev_requirement' => false,
          'provided' => 
          array (
            0 => '1.1|2.0',
          ),
        ),
        'psr/event-dispatcher' => 
        array (
          'pretty_version' => '1.0.0',
          'version' => '1.0.0.0',
          'reference' => 'dbefd12671e8a14ec7f180cab83036ed26714bb0',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../psr/event-dispatcher',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'psr/event-dispatcher-implementation' => 
        array (
          'dev_requirement' => false,
          'provided' => 
          array (
            0 => '1.0',
          ),
        ),
        'psr/http-client' => 
        array (
          'pretty_version' => '1.0.3',
          'version' => '1.0.3.0',
          'reference' => 'bb5906edc1c324c9a05aa0873d40117941e5fa90',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../psr/http-client',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'psr/http-client-implementation' => 
        array (
          'dev_requirement' => false,
          'provided' => 
          array (
            0 => '1.0',
          ),
        ),
        'psr/http-factory' => 
        array (
          'pretty_version' => '1.1.0',
          'version' => '1.1.0.0',
          'reference' => '2b4765fddfe3b508ac62f829e852b1501d3f6e8a',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../psr/http-factory',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'psr/http-factory-implementation' => 
        array (
          'dev_requirement' => false,
          'provided' => 
          array (
            0 => '1.0',
          ),
        ),
        'psr/http-message' => 
        array (
          'pretty_version' => '2.0',
          'version' => '2.0.0.0',
          'reference' => '402d35bcb92c70c026d1a6a9883f06b2ead23d71',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../psr/http-message',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'psr/http-message-implementation' => 
        array (
          'dev_requirement' => false,
          'provided' => 
          array (
            0 => '1.0',
          ),
        ),
        'psr/log' => 
        array (
          'pretty_version' => '3.0.2',
          'version' => '3.0.2.0',
          'reference' => 'f16e1d5863e37f8d8c2a01719f5b34baa2b714d3',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../psr/log',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'psr/log-implementation' => 
        array (
          'dev_requirement' => false,
          'provided' => 
          array (
            0 => '1.0|2.0|3.0',
            1 => '3.0.0',
          ),
        ),
        'psr/simple-cache' => 
        array (
          'pretty_version' => '3.0.0',
          'version' => '3.0.0.0',
          'reference' => '764e0b3939f5ca87cb904f570ef9be2d78a07865',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../psr/simple-cache',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'psr/simple-cache-implementation' => 
        array (
          'dev_requirement' => false,
          'provided' => 
          array (
            0 => '1.0|2.0|3.0',
          ),
        ),
        'psy/psysh' => 
        array (
          'pretty_version' => 'v0.12.24',
          'version' => '0.12.24.0',
          'reference' => 'ca0fdcf8a7617afa3adfdf1b5fef573dffb69ca1',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../psy/psysh',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'ralouphie/getallheaders' => 
        array (
          'pretty_version' => '3.0.3',
          'version' => '3.0.3.0',
          'reference' => '120b605dfeb996808c31b6477290a714d356e822',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../ralouphie/getallheaders',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'ramsey/collection' => 
        array (
          'pretty_version' => '2.1.1',
          'version' => '2.1.1.0',
          'reference' => '344572933ad0181accbf4ba763e85a0306a8c5e2',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../ramsey/collection',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'ramsey/uuid' => 
        array (
          'pretty_version' => '4.9.3',
          'version' => '4.9.3.0',
          'reference' => '1df15849d00943a67d677dc9cfd80795f038c9f8',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../ramsey/uuid',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'rhumsaa/uuid' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => '4.9.3',
          ),
        ),
        'ryangjchandler/blade-capture-directive' => 
        array (
          'pretty_version' => 'v1.1.1',
          'version' => '1.1.1.0',
          'reference' => '3f9e80b56ff60b78755ef320e3e16d88850101d6',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../ryangjchandler/blade-capture-directive',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'scrivo/highlight.php' => 
        array (
          'pretty_version' => 'v9.18.1.10',
          'version' => '9.18.1.10',
          'reference' => '850f4b44697a2552e892ffe71490ba2733c2fc6e',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../scrivo/highlight.php',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'sebastian/cli-parser' => 
        array (
          'pretty_version' => '3.0.2',
          'version' => '3.0.2.0',
          'reference' => '15c5dd40dc4f38794d383bb95465193f5e0ae180',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/cli-parser',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/code-unit' => 
        array (
          'pretty_version' => '3.0.3',
          'version' => '3.0.3.0',
          'reference' => '54391c61e4af8078e5b276ab082b6d3c54c9ad64',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/code-unit',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/code-unit-reverse-lookup' => 
        array (
          'pretty_version' => '4.0.1',
          'version' => '4.0.1.0',
          'reference' => '183a9b2632194febd219bb9246eee421dad8d45e',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/code-unit-reverse-lookup',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/comparator' => 
        array (
          'pretty_version' => '6.3.3',
          'version' => '6.3.3.0',
          'reference' => '2c95e1e86cb8dd41beb8d502057d1081ccc8eca9',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/comparator',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/complexity' => 
        array (
          'pretty_version' => '4.0.1',
          'version' => '4.0.1.0',
          'reference' => 'ee41d384ab1906c68852636b6de493846e13e5a0',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/complexity',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/diff' => 
        array (
          'pretty_version' => '6.0.2',
          'version' => '6.0.2.0',
          'reference' => 'b4ccd857127db5d41a5b676f24b51371d76d8544',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/diff',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/environment' => 
        array (
          'pretty_version' => '7.2.1',
          'version' => '7.2.1.0',
          'reference' => 'a5c75038693ad2e8d4b6c15ba2403532647830c4',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/environment',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/exporter' => 
        array (
          'pretty_version' => '6.3.2',
          'version' => '6.3.2.0',
          'reference' => '70a298763b40b213ec087c51c739efcaa90bcd74',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/exporter',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/global-state' => 
        array (
          'pretty_version' => '7.0.2',
          'version' => '7.0.2.0',
          'reference' => '3be331570a721f9a4b5917f4209773de17f747d7',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/global-state',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/lines-of-code' => 
        array (
          'pretty_version' => '3.0.1',
          'version' => '3.0.1.0',
          'reference' => 'd36ad0d782e5756913e42ad87cb2890f4ffe467a',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/lines-of-code',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/object-enumerator' => 
        array (
          'pretty_version' => '6.0.1',
          'version' => '6.0.1.0',
          'reference' => 'f5b498e631a74204185071eb41f33f38d64608aa',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/object-enumerator',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/object-reflector' => 
        array (
          'pretty_version' => '4.0.1',
          'version' => '4.0.1.0',
          'reference' => '6e1a43b411b2ad34146dee7524cb13a068bb35f9',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/object-reflector',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/recursion-context' => 
        array (
          'pretty_version' => '6.0.3',
          'version' => '6.0.3.0',
          'reference' => 'f6458abbf32a6c8174f8f26261475dc133b3d9dc',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/recursion-context',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/type' => 
        array (
          'pretty_version' => '5.1.3',
          'version' => '5.1.3.0',
          'reference' => 'f77d2d4e78738c98d9a68d2596fe5e8fa380f449',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/type',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'sebastian/version' => 
        array (
          'pretty_version' => '5.0.2',
          'version' => '5.0.2.0',
          'reference' => 'c687e3387b99f5b03b6caa64c74b63e2936ff874',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../sebastian/version',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'spatie/enum' => 
        array (
          'pretty_version' => '3.13.0',
          'version' => '3.13.0.0',
          'reference' => 'f1a0f464ba909491a53e60a955ce84ad7cd93a2c',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/enum',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/image' => 
        array (
          'pretty_version' => '3.9.5',
          'version' => '3.9.5.0',
          'reference' => '7ac0b9dab1100ddfc0c98afd12720f5b9fc0cd65',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/image',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/image-optimizer' => 
        array (
          'pretty_version' => '1.10.0',
          'version' => '1.10.0.0',
          'reference' => '333c03952289dc2df0a91874636a0dffeb5b6aec',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/image-optimizer',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/invade' => 
        array (
          'pretty_version' => '2.1.0',
          'version' => '2.1.0.0',
          'reference' => 'b920f6411d21df4e8610a138e2e87ae4957d7f63',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/invade',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/laravel-activitylog' => 
        array (
          'pretty_version' => '4.12.3',
          'version' => '4.12.3.0',
          'reference' => '2a2024fcac05628b0d1bfdbb1b94dda8b0661dc0',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-activitylog',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/laravel-health' => 
        array (
          'pretty_version' => '1.40.0',
          'version' => '1.40.0.0',
          'reference' => '6fa735589c04fd99970b434fc22dddbdc716a264',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-health',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/laravel-medialibrary' => 
        array (
          'pretty_version' => '11.23.2',
          'version' => '11.23.2.0',
          'reference' => 'cc1fdc4a9a9007101df610cd4a6733be71db4828',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-medialibrary',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/laravel-package-tools' => 
        array (
          'pretty_version' => '1.93.1',
          'version' => '1.93.1.0',
          'reference' => 'd5552849801f2642aea710557463234b59ef65eb',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-package-tools',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/laravel-permission' => 
        array (
          'pretty_version' => '8.3.0',
          'version' => '8.3.0.0',
          'reference' => '60e8ed5b2fbf043c2264433fc2680c76b8b66aa6',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/laravel-permission',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/once' => 
        array (
          'dev_requirement' => false,
          'replaced' => 
          array (
            0 => '*',
          ),
        ),
        'spatie/regex' => 
        array (
          'pretty_version' => '3.1.1',
          'version' => '3.1.1.0',
          'reference' => 'd543de2019a0068e7b80da0ba24f1c51c7469303',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/regex',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/shiki-php' => 
        array (
          'pretty_version' => '2.4.0',
          'version' => '2.4.0.0',
          'reference' => 'b8b0ca32d3a82bc5c533e68ffab96c5d4ec1b9ba',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/shiki-php',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'spatie/temporary-directory' => 
        array (
          'pretty_version' => '2.4.0',
          'version' => '2.4.0.0',
          'reference' => '32cbb9645b28839cf4f476708e99a2c70e6802c9',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../spatie/temporary-directory',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'staabm/side-effects-detector' => 
        array (
          'pretty_version' => '1.0.5',
          'version' => '1.0.5.0',
          'reference' => 'd8334211a140ce329c13726d4a715adbddd0a163',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../staabm/side-effects-detector',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'symfony/clock' => 
        array (
          'pretty_version' => 'v7.4.8',
          'version' => '7.4.8.0',
          'reference' => '674fa3b98e21531dd040e613479f5f6fa8f32111',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/clock',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/console' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => '92f58bc4bf97a92ed1b9f367f0cd44f20bde0e87',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/console',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/css-selector' => 
        array (
          'pretty_version' => 'v7.4.9',
          'version' => '7.4.9.0',
          'reference' => 'b75663ed96cf4756e28e3105476f220f92886cc4',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/css-selector',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/deprecation-contracts' => 
        array (
          'pretty_version' => 'v3.7.1',
          'version' => '3.7.1.0',
          'reference' => 'f3202fa1b5097b0af062dc978b32ecf63404e31d',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/deprecation-contracts',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/error-handler' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => '4e1a093b481f323e6e326451f9760c3868430673',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/error-handler',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/event-dispatcher' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => '51fe3d170227be8d1772214b82ae506e15ed78ff',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/event-dispatcher',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/event-dispatcher-contracts' => 
        array (
          'pretty_version' => 'v3.7.1',
          'version' => '3.7.1.0',
          'reference' => 'c7de7a00ffb67842132da02ea92988a39ccd9f4e',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/event-dispatcher-contracts',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/event-dispatcher-implementation' => 
        array (
          'dev_requirement' => false,
          'provided' => 
          array (
            0 => '2.0|3.0',
          ),
        ),
        'symfony/finder' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => '13b38720174286f55d1761152b575a8d1436fc25',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/finder',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/html-sanitizer' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => 'c328df69f5b6f44a0d031d757903d955bebb23b3',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/html-sanitizer',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/http-foundation' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => '06db5ae1552177bf8572f8908839f12e3c06aed3',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/http-foundation',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/http-kernel' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => 'e99af79b1e776646eda0e1c23b7b45c184ff99be',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/http-kernel',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/mailer' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => 'f88ce03ae73e3edb5c176ce1f337709996e88495',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/mailer',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/mime' => 
        array (
          'pretty_version' => 'v7.4.13',
          'version' => '7.4.13.0',
          'reference' => 'a845722765c4f6b2ce88beaf4f4479975b186770',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/mime',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/polyfill-ctype' => 
        array (
          'pretty_version' => 'v1.37.0',
          'version' => '1.37.0.0',
          'reference' => '141046a8f9477948ff284fa65be2095baafb94f2',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/polyfill-ctype',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/polyfill-intl-grapheme' => 
        array (
          'pretty_version' => 'v1.38.1',
          'version' => '1.38.1.0',
          'reference' => 'e9247d281d694a5120554d9afaf54e070e88a603',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/polyfill-intl-grapheme',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/polyfill-intl-idn' => 
        array (
          'pretty_version' => 'v1.38.1',
          'version' => '1.38.1.0',
          'reference' => 'dc21118016c039a66235cf93d96b435ffb282412',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/polyfill-intl-idn',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/polyfill-intl-normalizer' => 
        array (
          'pretty_version' => 'v1.38.0',
          'version' => '1.38.0.0',
          'reference' => '2d446c214bdbe5b71bde5011b060a05fece3ae6b',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/polyfill-intl-normalizer',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/polyfill-mbstring' => 
        array (
          'pretty_version' => 'v1.38.2',
          'version' => '1.38.2.0',
          'reference' => 'd3d318bad5e7a1bfbd026009c8bfb8d8f99ae6b6',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/polyfill-mbstring',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/polyfill-php80' => 
        array (
          'pretty_version' => 'v1.37.0',
          'version' => '1.37.0.0',
          'reference' => 'dfb55726c3a76ea3b6459fcfda1ec2d80a682411',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/polyfill-php80',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/polyfill-php83' => 
        array (
          'pretty_version' => 'v1.38.2',
          'version' => '1.38.2.0',
          'reference' => '796a26abb75ce49f3a84433cd81bf1009d73d5f8',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/polyfill-php83',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/polyfill-php84' => 
        array (
          'pretty_version' => 'v1.38.1',
          'version' => '1.38.1.0',
          'reference' => 'f4e1dfaee5b74aba5964fe1fd4dfc7ba5e3085fa',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/polyfill-php84',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/polyfill-php85' => 
        array (
          'pretty_version' => 'v1.38.1',
          'version' => '1.38.1.0',
          'reference' => 'ba2ba04f3352cfa2dcbbcb90aee13ed967f505b1',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/polyfill-php85',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/polyfill-uuid' => 
        array (
          'pretty_version' => 'v1.37.0',
          'version' => '1.37.0.0',
          'reference' => '26dfec253c4cf3e51b541b52ddf7e42cb0908e94',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/polyfill-uuid',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/process' => 
        array (
          'pretty_version' => 'v7.4.13',
          'version' => '7.4.13.0',
          'reference' => 'f5804be144caceb570f6747519999636b664f24c',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/process',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/routing' => 
        array (
          'pretty_version' => 'v7.4.13',
          'version' => '7.4.13.0',
          'reference' => '3a162171bb008e5e0f15dce6581373a4c0e8390d',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/routing',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/service-contracts' => 
        array (
          'pretty_version' => 'v3.7.1',
          'version' => '3.7.1.0',
          'reference' => 'c0a284bab1ed8aa0417e3d69250ab437739563a0',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/service-contracts',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/string' => 
        array (
          'pretty_version' => 'v7.4.13',
          'version' => '7.4.13.0',
          'reference' => '961683010db3b27ec6ebcd7308e6e1ee8fa7ffde',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/string',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/translation' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => 'a1af4dacb24eb7ef4f1ca71b94da8ddbce572281',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/translation',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/translation-contracts' => 
        array (
          'pretty_version' => 'v3.7.1',
          'version' => '3.7.1.0',
          'reference' => 'ccb206b98faccc511ebae8e5fad50f2dc0b30621',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/translation-contracts',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/translation-implementation' => 
        array (
          'dev_requirement' => false,
          'provided' => 
          array (
            0 => '2.3|3.0',
          ),
        ),
        'symfony/uid' => 
        array (
          'pretty_version' => 'v7.4.9',
          'version' => '7.4.9.0',
          'reference' => '2676b524340abcfe4d6151ec698463cebafee439',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/uid',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/var-dumper' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => '9a3a56a4a1e65a5cb4f8d13801fe8ab0a170e358',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/var-dumper',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'symfony/yaml' => 
        array (
          'pretty_version' => 'v7.4.14',
          'version' => '7.4.14.0',
          'reference' => 'f8f328665ace2370d1e10645b807ba1646dc7dcc',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../symfony/yaml',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'ta-tikoma/phpunit-architecture-test' => 
        array (
          'pretty_version' => '0.8.7',
          'version' => '0.8.7.0',
          'reference' => '1248f3f506ca9641d4f68cebcd538fa489754db8',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../ta-tikoma/phpunit-architecture-test',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'theseer/tokenizer' => 
        array (
          'pretty_version' => '1.3.1',
          'version' => '1.3.1.0',
          'reference' => 'b7489ce515e168639d17feec34b8847c326b0b3c',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../theseer/tokenizer',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
        'tijsverkoyen/css-to-inline-styles' => 
        array (
          'pretty_version' => 'v2.4.0',
          'version' => '2.4.0.0',
          'reference' => 'f0292ccf0ec75843d65027214426b6b163b48b41',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../tijsverkoyen/css-to-inline-styles',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'ueberdosis/tiptap-php' => 
        array (
          'pretty_version' => '2.1.1',
          'version' => '2.1.1.0',
          'reference' => '74bfb7be1c8c6102b240f3879b7f984a6ab87b97',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../ueberdosis/tiptap-php',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'vlucas/phpdotenv' => 
        array (
          'pretty_version' => 'v5.6.4',
          'version' => '5.6.4.0',
          'reference' => '416df702837983f8d5ff48c9c3fee4f5f57b980b',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../vlucas/phpdotenv',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'voku/portable-ascii' => 
        array (
          'pretty_version' => '2.1.1',
          'version' => '2.1.1.0',
          'reference' => '8e1051fe39379367aecf014f41744ce7539a856f',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../voku/portable-ascii',
          'aliases' => 
          array (
          ),
          'dev_requirement' => false,
        ),
        'webmozart/assert' => 
        array (
          'pretty_version' => '2.4.1',
          'version' => '2.4.1.0',
          'reference' => '2ccb7c2e821038c03a3e6e1700c570c158c55f70',
          'type' => 'library',
          'install_path' => '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/composer/../webmozart/assert',
          'aliases' => 
          array (
          ),
          'dev_requirement' => true,
        ),
      ),
    ),
  ),
  'executedFilesHashes' => 
  array (
    '/Users/ridwankadri/Desktop/code/atlas-erp/vendor/larastan/larastan/bootstrap.php' => '5a3eacbf63b3e41659adfee92facededf8e020a932800f93c9a8b0e67f235805',
    'phar:///Users/ridwankadri/Desktop/code/atlas-erp/vendor/phpstan/phpstan/phpstan.phar/stubs/runtime/Attribute85.php' => 'cb8b31e82c61ce197871c9e8a6f122256751f2ab606dd2be90846d4fa5f8933e',
    'phar:///Users/ridwankadri/Desktop/code/atlas-erp/vendor/phpstan/phpstan/phpstan.phar/stubs/runtime/ReflectionAttribute.php' => 'c0068e383717870a304781d462f7e2afe1c6f24e9133851852a2aca96b4fa26f',
    'phar:///Users/ridwankadri/Desktop/code/atlas-erp/vendor/phpstan/phpstan/phpstan.phar/stubs/runtime/ReflectionIntersectionType.php' => '65fe0a8bc6fe285d8ddc8798ab5b9299920af70db5ad74596bc08df823e7c5d9',
    'phar:///Users/ridwankadri/Desktop/code/atlas-erp/vendor/phpstan/phpstan/phpstan.phar/stubs/runtime/ReflectionUnionType.php' => '1e2fe940e4ba4e00d9ee6adb2af3ee1bf333e6f8afe61c61deb038886d293427',
  ),
  'phpExtensions' => 
  array (
    0 => 'Core',
    1 => 'FFI',
    2 => 'PDO',
    3 => 'Phar',
    4 => 'Reflection',
    5 => 'SPL',
    6 => 'SimpleXML',
    7 => 'Zend OPcache',
    8 => 'bcmath',
    9 => 'bz2',
    10 => 'calendar',
    11 => 'ctype',
    12 => 'curl',
    13 => 'date',
    14 => 'dba',
    15 => 'dom',
    16 => 'exif',
    17 => 'fileinfo',
    18 => 'filter',
    19 => 'ftp',
    20 => 'gd',
    21 => 'gettext',
    22 => 'gmp',
    23 => 'hash',
    24 => 'herd',
    25 => 'iconv',
    26 => 'igbinary',
    27 => 'imagick',
    28 => 'imap',
    29 => 'intl',
    30 => 'json',
    31 => 'ldap',
    32 => 'libxml',
    33 => 'mbstring',
    34 => 'mongodb',
    35 => 'mysqli',
    36 => 'mysqlnd',
    37 => 'openssl',
    38 => 'pcntl',
    39 => 'pcre',
    40 => 'pdo_mysql',
    41 => 'pdo_pgsql',
    42 => 'pdo_sqlite',
    43 => 'pdo_sqlsrv',
    44 => 'pgsql',
    45 => 'posix',
    46 => 'random',
    47 => 'readline',
    48 => 'redis',
    49 => 'session',
    50 => 'shmop',
    51 => 'soap',
    52 => 'sockets',
    53 => 'sodium',
    54 => 'sqlite3',
    55 => 'sqlsrv',
    56 => 'standard',
    57 => 'sysvmsg',
    58 => 'sysvsem',
    59 => 'sysvshm',
    60 => 'tokenizer',
    61 => 'xml',
    62 => 'xmlreader',
    63 => 'xmlwriter',
    64 => 'xsl',
    65 => 'zip',
    66 => 'zlib',
    67 => 'zstd',
  ),
  'stubFiles' => 
  array (
  ),
  'level' => '8',
),
	'projectExtensionFiles' => array (
),
	'errorsCallback' => static function (): array { return array (
); },
	'locallyIgnoredErrorsCallback' => static function (): array { return array (
); },
	'linesToIgnore' => array (
),
	'unmatchedLineIgnores' => array (
),
	'collectedDataCallback' => static function (): array { return array (
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/CreateCompanyAction.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Administration\\Actions\\Companies\\CreateCompanyAction',
        1 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Administration\\Actions\\Companies\\UpdateCompanyAction',
        1 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Settings/UpdateSettingsAction.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Administration\\Actions\\Settings\\UpdateSettingsAction',
        1 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/CreateUserAction.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Administration\\Actions\\Users\\CreateUserAction',
        1 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserAction.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Administration\\Actions\\Users\\UpdateUserAction',
        1 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Administration\\Actions\\Users\\UpdateUserStatusAction',
        1 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Enums/PermissionName.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Administration\\Enums\\PermissionName',
        1 => 'values',
        2 => 'App\\Administration\\Enums\\PermissionName',
        3 => 
        array (
          0 => 'f' . "\0" . 'array_map',
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Enums/RoleName.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Administration\\Enums\\RoleName',
        1 => 'values',
        2 => 'App\\Administration\\Enums\\RoleName',
        3 => 
        array (
          0 => 'f' . "\0" . 'array_map',
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Pages/Dashboard.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Administration\\Filament\\Pages\\Dashboard',
        1 => 'getWidgets',
        2 => 'App\\Core\\Administration\\Filament\\Pages\\Dashboard',
        3 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/SystemStatusWidget.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Administration\\Filament\\Widgets\\SystemStatusWidget',
        1 => 'getStats',
        2 => 'App\\Core\\Administration\\Filament\\Widgets\\SystemStatusWidget',
        3 => 
        array (
          0 => 'm' . "\0" . 'filament\\widgets\\statsoverviewwidget\\stat' . "\0" . 'make',
          1 => 'm' . "\0" . 'app\\core\\administration\\filament\\widgets\\systemstatuswidget' . "\0" . 'databasestatus',
          2 => 'm' . "\0" . 'app\\core\\administration\\filament\\widgets\\systemstatuswidget' . "\0" . 'redisstatus',
          3 => 'f' . "\0" . 'config',
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\PossiblyPureStaticCallCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Support\\Facades\\Gate',
        1 => 'policy',
        2 => 29,
      ),
      1 => 
      array (
        0 => 'Illuminate\\Support\\Facades\\Gate',
        1 => 'policy',
        2 => 30,
      ),
      2 => 
      array (
        0 => 'Illuminate\\Support\\Facades\\Gate',
        1 => 'define',
        2 => 32,
      ),
      3 => 
      array (
        0 => 'Illuminate\\Support\\Facades\\Gate',
        1 => 'define',
        2 => 33,
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Actions/UpdateSettingAction.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Settings\\Actions\\UpdateSettingAction',
        1 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/DTOs/SettingData.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Settings\\DTOs\\SettingData',
        1 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Models/Setting.php' => 
  array (
    'PHPStan\\Rules\\Comparison\\ConstantConditionInTraitCollector' => 
    array (
      0 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeStaticMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => 'static::creating(function (\\Illuminate\\Database\\Eloquent\\Model $model): void {
    $tenantId = app(\\App\\Core\\Tenancy\\Support\\TenantContext::class)->id();
    if ($tenantId !== null && !$model->getAttribute(\'tenant_id\')) {
        $model->setAttribute(\'tenant_id\', $tenantId);
    }
}):15',
        3 => NULL,
      ),
      1 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => 'app(\\App\\Core\\Tenancy\\Support\\TenantContext::class)->id():16',
        3 => NULL,
      ),
      2 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeFunctionCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => 'app(\\App\\Core\\Tenancy\\Support\\TenantContext::class):16',
        3 => NULL,
      ),
      3 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\IfConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$tenantId !== null && !$model->getAttribute(\'tenant_id\'):18',
        3 => NULL,
      ),
      4 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\StrictComparisonOfDifferentTypesRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$tenantId !== null:18',
        3 => NULL,
      ),
      5 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\BooleanNotConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$model->getAttribute(\'tenant_id\'):18',
        3 => NULL,
      ),
      6 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$model->getAttribute(\'tenant_id\'):18',
        3 => NULL,
      ),
      7 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\BooleanAndConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$tenantId !== null:18',
        3 => NULL,
      ),
      8 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\BooleanAndConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '!$model->getAttribute(\'tenant_id\'):18',
        3 => NULL,
      ),
      9 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\BooleanAndConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$tenantId !== null && !$model->getAttribute(\'tenant_id\'):18',
        3 => NULL,
      ),
      10 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$model->setAttribute(\'tenant_id\', $tenantId):19',
        3 => NULL,
      ),
      11 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeStaticMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => 'static::addGlobalScope(\'tenant\', function (\\Illuminate\\Database\\Eloquent\\Builder $builder): void {
    $tenantId = app(\\App\\Core\\Tenancy\\Support\\TenantContext::class)->id();
    if ($tenantId !== null) {
        $builder->where($builder->getModel()->qualifyColumn(\'tenant_id\'), $tenantId);
    }
}):23',
        3 => NULL,
      ),
      12 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => 'app(\\App\\Core\\Tenancy\\Support\\TenantContext::class)->id():24',
        3 => NULL,
      ),
      13 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeFunctionCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => 'app(\\App\\Core\\Tenancy\\Support\\TenantContext::class):24',
        3 => NULL,
      ),
      14 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\IfConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$tenantId !== null:26',
        3 => NULL,
      ),
      15 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\StrictComparisonOfDifferentTypesRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$tenantId !== null:26',
        3 => NULL,
      ),
      16 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$builder->where($builder->getModel()->qualifyColumn(\'tenant_id\'), $tenantId):27',
        3 => NULL,
      ),
      17 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$builder->getModel()->qualifyColumn(\'tenant_id\'):28',
        3 => NULL,
      ),
      18 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        2 => '$builder->getModel():28',
        3 => NULL,
      ),
      19 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeStaticMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => 'static::creating(function (\\Illuminate\\Database\\Eloquent\\Model $model): void {
    if (!$model->getAttribute(\'uuid\')) {
        $model->setAttribute(\'uuid\', (string) \\Illuminate\\Support\\Str::uuid());
    }
}):14',
        3 => NULL,
      ),
      20 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\IfConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '!$model->getAttribute(\'uuid\'):15',
        3 => NULL,
      ),
      21 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\BooleanNotConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '$model->getAttribute(\'uuid\'):15',
        3 => NULL,
      ),
      22 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '$model->getAttribute(\'uuid\'):15',
        3 => NULL,
      ),
      23 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '$model->setAttribute(\'uuid\', (string) \\Illuminate\\Support\\Str::uuid()):16',
        3 => NULL,
      ),
      24 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeStaticMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '\\Illuminate\\Support\\Str::uuid():16',
        3 => NULL,
      ),
    ),
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Settings\\Models\\Setting',
        1 => 'casts',
        2 => 'App\\Core\\Settings\\Models\\Setting',
        3 => 
        array (
        ),
      ),
    ),
    'PHPStan\\Rules\\DeadCode\\PossiblyPureStaticCallCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Database\\Eloquent\\Model',
        1 => 'addGlobalScope',
        2 => 23,
      ),
    ),
    'PHPStan\\Rules\\Traits\\TraitUseCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
      ),
      1 => 
      array (
        0 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Services/SettingService.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Settings\\Services\\SettingService',
        1 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Concerns/BelongsToTenant.php' => 
  array (
    'PHPStan\\Rules\\Traits\\TraitDeclarationCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        1 => 11,
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Concerns/HasPublicUuid.php' => 
  array (
    'PHPStan\\Rules\\Traits\\TraitDeclarationCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        1 => 10,
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Exceptions/BusinessException.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Shared\\Exceptions\\BusinessException',
        1 => 
        array (
        ),
      ),
    ),
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Shared\\Exceptions\\BusinessException',
        1 => 'status',
        2 => 'App\\Core\\Shared\\Exceptions\\BusinessException',
        3 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Notifications/SystemNotification.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Shared\\Notifications\\SystemNotification',
        1 => 
        array (
        ),
      ),
    ),
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Shared\\Notifications\\SystemNotification',
        1 => 'via',
        2 => 'App\\Core\\Shared\\Notifications\\SystemNotification',
        3 => 
        array (
        ),
      ),
      1 => 
      array (
        0 => 'App\\Core\\Shared\\Notifications\\SystemNotification',
        1 => 'toArray',
        2 => 'App\\Core\\Shared\\Notifications\\SystemNotification',
        3 => 
        array (
        ),
      ),
      2 => 
      array (
        0 => 'App\\Core\\Shared\\Notifications\\SystemNotification',
        1 => 'toMail',
        2 => 'App\\Core\\Shared\\Notifications\\SystemNotification',
        3 => 
        array (
          0 => 'm' . "\0" . 'illuminate\\notifications\\messages\\simplemessage' . "\0" . 'subject',
          1 => 'm' . "\0" . 'illuminate\\notifications\\messages\\simplemessage' . "\0" . 'line',
        ),
      ),
    ),
    'PHPStan\\Rules\\Traits\\TraitUseCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Bus\\Queueable',
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php' => 
  array (
    'PHPStan\\Rules\\Comparison\\ConstantConditionInTraitCollector' => 
    array (
      0 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeStaticMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => 'static::creating(function (\\Illuminate\\Database\\Eloquent\\Model $model): void {
    if (!$model->getAttribute(\'uuid\')) {
        $model->setAttribute(\'uuid\', (string) \\Illuminate\\Support\\Str::uuid());
    }
}):14',
        3 => NULL,
      ),
      1 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\IfConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '!$model->getAttribute(\'uuid\'):15',
        3 => NULL,
      ),
      2 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\BooleanNotConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '$model->getAttribute(\'uuid\'):15',
        3 => NULL,
      ),
      3 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '$model->getAttribute(\'uuid\'):15',
        3 => NULL,
      ),
      4 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '$model->setAttribute(\'uuid\', (string) \\Illuminate\\Support\\Str::uuid()):16',
        3 => NULL,
      ),
      5 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeStaticMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '\\Illuminate\\Support\\Str::uuid():16',
        3 => NULL,
      ),
    ),
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Tenancy\\Models\\Tenant',
        1 => 'newFactory',
        2 => 'App\\Core\\Tenancy\\Models\\Tenant',
        3 => 
        array (
          0 => 'm' . "\0" . 'illuminate\\database\\eloquent\\factories\\factory' . "\0" . 'new',
        ),
      ),
      1 => 
      array (
        0 => 'App\\Core\\Tenancy\\Models\\Tenant',
        1 => 'casts',
        2 => 'App\\Core\\Tenancy\\Models\\Tenant',
        3 => 
        array (
        ),
      ),
    ),
    'PHPStan\\Rules\\Traits\\TraitUseCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      ),
      1 => 
      array (
        0 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
      ),
      2 => 
      array (
        0 => 'Spatie\\MediaLibrary\\InteractsWithMedia',
      ),
      3 => 
      array (
        0 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Support/TenantContext.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Core\\Tenancy\\Support\\TenantContext',
        1 => 'tenant',
        2 => 'App\\Core\\Tenancy\\Support\\TenantContext',
        3 => 
        array (
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Http/Controllers/Auth/VerifyEmailController.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\PossiblyPureFuncCallCollector' => 
    array (
      0 => 
      array (
        0 => 'event',
        1 => 27,
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Actions/Logout.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\PossiblyPureStaticCallCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Support\\Facades\\Session',
        1 => 'invalidate',
        2 => 28,
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Forms/LoginForm.php' => 
  array (
    'Larastan\\Larastan\\Collectors\\UsedTranslationFunctionCollector' => 
    array (
      0 => 
      array (
        0 => 'auth.failed',
        1 => 37,
      ),
      1 => 
      array (
        0 => 'auth.failed',
        1 => 47,
      ),
      2 => 
      array (
        0 => 'auth.throttle',
        1 => 98,
      ),
    ),
    'PHPStan\\Rules\\DeadCode\\PossiblyPureFuncCallCollector' => 
    array (
      0 => 
      array (
        0 => 'event',
        1 => 93,
      ),
    ),
    'PHPStan\\Rules\\DeadCode\\PossiblyPureStaticCallCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Support\\Facades\\RateLimiter',
        1 => 'hit',
        2 => 34,
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Models/User.php' => 
  array (
    'PHPStan\\Rules\\Comparison\\ConstantConditionInTraitCollector' => 
    array (
      0 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeStaticMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => 'static::creating(function (\\Illuminate\\Database\\Eloquent\\Model $model): void {
    if (!$model->getAttribute(\'uuid\')) {
        $model->setAttribute(\'uuid\', (string) \\Illuminate\\Support\\Str::uuid());
    }
}):14',
        3 => NULL,
      ),
      1 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\IfConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '!$model->getAttribute(\'uuid\'):15',
        3 => NULL,
      ),
      2 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\BooleanNotConstantConditionRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '$model->getAttribute(\'uuid\'):15',
        3 => NULL,
      ),
      3 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '$model->getAttribute(\'uuid\'):15',
        3 => NULL,
      ),
      4 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '$model->setAttribute(\'uuid\', (string) \\Illuminate\\Support\\Str::uuid()):16',
        3 => NULL,
      ),
      5 => 
      array (
        0 => 'PHPStan\\Rules\\Comparison\\ImpossibleCheckTypeStaticMethodCallRule',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => '\\Illuminate\\Support\\Str::uuid():16',
        3 => NULL,
      ),
    ),
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\Models\\User',
        1 => 'casts',
        2 => 'App\\Models\\User',
        3 => 
        array (
        ),
      ),
      1 => 
      array (
        0 => 'App\\Models\\User',
        1 => 'getFullNameAttribute',
        2 => 'App\\Models\\User',
        3 => 
        array (
        ),
      ),
    ),
    'PHPStan\\Rules\\Traits\\TraitUseCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
      ),
      1 => 
      array (
        0 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
      ),
      2 => 
      array (
        0 => 'Spatie\\Permission\\Traits\\HasRoles',
      ),
      3 => 
      array (
        0 => 'Spatie\\MediaLibrary\\InteractsWithMedia',
      ),
      4 => 
      array (
        0 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
      ),
      5 => 
      array (
        0 => 'Illuminate\\Notifications\\Notifiable',
      ),
      6 => 
      array (
        0 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/AppServiceProvider.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\PossiblyPureStaticCallCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Database\\Eloquent\\Relations\\Relation',
        1 => 'enforceMorphMap',
        2 => 27,
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/Filament/AdminPanelProvider.php' => 
  array (
    'PHPStan\\Rules\\Methods\\NamedArgumentParameterMethodCallsCollector' => 
    array (
      0 => 
      array (
        0 => 'Filament\\Panel',
        1 => 'discoverResources',
        2 => 'in',
        3 => 36,
      ),
      1 => 
      array (
        0 => 'Filament\\Panel',
        1 => 'discoverResources',
        2 => 'for',
        3 => 36,
      ),
      2 => 
      array (
        0 => 'Filament\\Panel',
        1 => 'discoverPages',
        2 => 'in',
        3 => 37,
      ),
      3 => 
      array (
        0 => 'Filament\\Panel',
        1 => 'discoverPages',
        2 => 'for',
        3 => 37,
      ),
      4 => 
      array (
        0 => 'Filament\\Panel',
        1 => 'discoverWidgets',
        2 => 'in',
        3 => 41,
      ),
      5 => 
      array (
        0 => 'Filament\\Panel',
        1 => 'discoverWidgets',
        2 => 'for',
        3 => 41,
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/AppLayout.php' => 
  array (
    'Larastan\\Larastan\\Collectors\\UsedViewFunctionCollector' => 
    array (
      0 => 'layouts.app',
    ),
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\View\\Components\\AppLayout',
        1 => 'render',
        2 => 'App\\View\\Components\\AppLayout',
        3 => 
        array (
          0 => 'f' . "\0" . 'view',
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/GuestLayout.php' => 
  array (
    'Larastan\\Larastan\\Collectors\\UsedViewFunctionCollector' => 
    array (
      0 => 'layouts.guest',
    ),
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'App\\View\\Components\\GuestLayout',
        1 => 'render',
        2 => 'App\\View\\Components\\GuestLayout',
        3 => 
        array (
          0 => 'f' . "\0" . 'view',
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/vendor/health/Logo.php' => 
  array (
    'Larastan\\Larastan\\Collectors\\UsedViewFunctionCollector' => 
    array (
      0 => 'health::logo',
    ),
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'Spatie\\Health\\Components\\Logo',
        1 => 'render',
        2 => 'Spatie\\Health\\Components\\Logo',
        3 => 
        array (
          0 => 'f' . "\0" . 'view',
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/vendor/health/StatusIndicator.php' => 
  array (
    'Larastan\\Larastan\\Collectors\\UsedViewFunctionCollector' => 
    array (
      0 => 'health::status-indicator',
    ),
    'PHPStan\\Rules\\DeadCode\\ConstructorWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'Spatie\\Health\\Components\\StatusIndicator',
        1 => 
        array (
        ),
      ),
    ),
    'PHPStan\\Rules\\DeadCode\\MethodWithoutImpurePointsCollector' => 
    array (
      0 => 
      array (
        0 => 'Spatie\\Health\\Components\\StatusIndicator',
        1 => 'render',
        2 => 'Spatie\\Health\\Components\\StatusIndicator',
        3 => 
        array (
          0 => 'f' . "\0" . 'view',
        ),
      ),
      1 => 
      array (
        0 => 'Spatie\\Health\\Components\\StatusIndicator',
        1 => 'getBackgroundColor',
        2 => 'Spatie\\Health\\Components\\StatusIndicator',
        3 => 
        array (
          0 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'ok',
          1 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'warning',
          2 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'skipped',
          3 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'failed',
          4 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'crashed',
        ),
      ),
      2 => 
      array (
        0 => 'Spatie\\Health\\Components\\StatusIndicator',
        1 => 'getIconColor',
        2 => 'Spatie\\Health\\Components\\StatusIndicator',
        3 => 
        array (
          0 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'ok',
          1 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'warning',
          2 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'skipped',
          3 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'failed',
          4 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'crashed',
        ),
      ),
      3 => 
      array (
        0 => 'Spatie\\Health\\Components\\StatusIndicator',
        1 => 'getIcon',
        2 => 'Spatie\\Health\\Components\\StatusIndicator',
        3 => 
        array (
          0 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'ok',
          1 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'warning',
          2 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'skipped',
          3 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'failed',
          4 => 'm' . "\0" . 'spatie\\health\\enums\\status' . "\0" . 'crashed',
        ),
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php' => 
  array (
    'PHPStan\\Rules\\DeadCode\\PossiblyPureStaticCallCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Database\\Connection',
        1 => 'transaction',
        2 => 26,
      ),
    ),
    'PHPStan\\Rules\\Traits\\TraitUseCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Database\\Console\\Seeds\\WithoutModelEvents',
      ),
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/routes/web.php' => 
  array (
    'Larastan\\Larastan\\Collectors\\UsedRouteFacadeViewCollector' => 
    array (
      0 => 'welcome',
      1 => 'dashboard',
      2 => 'profile',
    ),
    'PHPStan\\Rules\\DeadCode\\PossiblyPureStaticCallCollector' => 
    array (
      0 => 
      array (
        0 => 'Illuminate\\Support\\Facades\\Route',
        1 => 'view',
        2 => 6,
      ),
    ),
  ),
); },
	'dependencies' => array (
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/CreateCompanyAction.php' => 
  array (
    'fileHash' => 'aceeb5ff27a1954dcf25297f7725c063f87837317cf4a64d444d8b9da7bc139c',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php' => 
  array (
    'fileHash' => '26c177381f75c73816698da6ac480212db2945ab56afd775e69499b93aace508',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Settings/UpdateSettingsAction.php' => 
  array (
    'fileHash' => 'eb13e4690b35e20970e4a0c58624efd65b11a7fba229fb9496224b88daeaa9dc',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/CreateUserAction.php' => 
  array (
    'fileHash' => '44f3bce4108f018f1b45879d4ae2e69718c4e1f9847dc8da8d1a01c4b5de2222',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserAction.php' => 
  array (
    'fileHash' => '26b9e17034dc4907e2a5398ec09cd734e9f706309c0e167339346a8c5c5a1c2b',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php' => 
  array (
    'fileHash' => 'b148ca9cc540a9ad48021afb4a54ca3ad95aaa4569e3ff4b11e93e703b7a8662',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Enums/PermissionName.php' => 
  array (
    'fileHash' => 'dce50ccc25d848acc2bcddd99f7eba49252ba3f55c84fe658dd4ea5bd82e39b8',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/CreateCompanyAction.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/TenantPolicy.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/UserPolicy.php',
      5 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/RoleAndPermissionSeeder.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Enums/RoleName.php' => 
  array (
    'fileHash' => 'f5f20a58095474a28843507cca28dc24b5b1b1e0aaafeae1b5f7b64058d31c16',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/TenantPolicy.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/UserPolicy.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationAccessService.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/RoleAndPermissionSeeder.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/TenantPolicy.php' => 
  array (
    'fileHash' => 'a273de1dc0c09547e03d773e5712daeaccd0dd7d185943451ee4b742f179ae94',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/UserPolicy.php' => 
  array (
    'fileHash' => 'dbf2809a41869ddb582c61d9f9bf63ec08d35b741ac5ef607c888eb18793ea47',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationAccessService.php' => 
  array (
    'fileHash' => '859c7fbe0e6a796c86915234c8af85f4a880823b8aac757408cde2be25e5fcf1',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Settings/UpdateSettingsAction.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/CreateUserAction.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserAction.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php',
      5 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationActivityLogger.php' => 
  array (
    'fileHash' => '633935a06473590ab2f72f83c829bab12965401a35bd54afcb094baf82299124',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/CreateCompanyAction.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/CreateUserAction.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserAction.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php',
      5 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Pages/Dashboard.php' => 
  array (
    'fileHash' => '1d03d505d3459a3d3459a22f1bd73f9c0adc282f65038a10cedbf8de370e627e',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/Filament/AdminPanelProvider.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/CurrentContextWidget.php' => 
  array (
    'fileHash' => '7e73d359f3509b080d85433d3a29a0fd856c20226918dc612c66e9ac4b4bf494',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Pages/Dashboard.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/QuickNavigationWidget.php' => 
  array (
    'fileHash' => '3740f7789fbb3335c48e69ba5fc8389a72fecb8856d0a255820a2bbcc49e568c',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Pages/Dashboard.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/RecentActivityWidget.php' => 
  array (
    'fileHash' => '51232d2916bdc5ef383d7e21ada02c79bfa07b0732cf4da34c9e3f725c9da20a',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Pages/Dashboard.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/SystemStatusWidget.php' => 
  array (
    'fileHash' => 'c243fdcbcb2757b3d27436c5e34e824fdef2859e183876390f7c33c46e066f1a',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Pages/Dashboard.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php' => 
  array (
    'fileHash' => '2ab73202cf0ea73e33a2b055f3200ff749031c1bc95410fbf44d5c52277b5bee',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Actions/UpdateSettingAction.php' => 
  array (
    'fileHash' => '57dcb2c541dffeec2e60c151894cd0edf3d5a99530bb5ee9bda5557864c37b3b',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/DTOs/SettingData.php' => 
  array (
    'fileHash' => '72ac780b000892f11098d56b2c5b38d50b20a7d634ae89755656f9dc31130f9a',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Settings/UpdateSettingsAction.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Actions/UpdateSettingAction.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/EloquentSettingRepository.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/SettingRepositoryInterface.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Services/SettingService.php',
      5 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Models/Setting.php' => 
  array (
    'fileHash' => 'b6d135ab80afef1d3559d4335732479899e708bfdda9ee031587af6ee2c0cd22',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/EloquentSettingRepository.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/SettingRepositoryInterface.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Services/SettingService.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/AppServiceProvider.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/EloquentSettingRepository.php' => 
  array (
    'fileHash' => 'eea09f64c062cb04ffc880258a7f6c5506fb05481c36fb685cc2e706d87c376a',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/SettingRepositoryInterface.php' => 
  array (
    'fileHash' => '15cf60d5bbb8224490ceb1b9aa7aa7639528160464535612a4372d2163499088',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/EloquentSettingRepository.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Services/SettingService.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Services/SettingService.php' => 
  array (
    'fileHash' => '908992baf24b425a6236ba3c706adb930b7f1fda74d81082b620e21a0e6ace0b',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Settings/UpdateSettingsAction.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Actions/UpdateSettingAction.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Concerns/BelongsToTenant.php' => 
  array (
    'fileHash' => '2ebd3b483c053a25e3a665c6d37bfaf77d0ec26d77502ec131c0ffe226c5d714',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Models/Setting.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/EloquentSettingRepository.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/SettingRepositoryInterface.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Services/SettingService.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php',
      5 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/AppServiceProvider.php',
    ),
    'usedTraitDependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Models/Setting.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Concerns/HasPublicUuid.php' => 
  array (
    'fileHash' => '1864d5eaaf5f956d8a05319d7daab1259fe5751202d6234020b604c7a0f433cf',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/CreateCompanyAction.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Settings/UpdateSettingsAction.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/CreateUserAction.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserAction.php',
      5 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php',
      6 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/TenantPolicy.php',
      7 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/UserPolicy.php',
      8 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationAccessService.php',
      9 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationActivityLogger.php',
      10 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/CurrentContextWidget.php',
      11 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php',
      12 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Models/Setting.php',
      13 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/EloquentSettingRepository.php',
      14 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/SettingRepositoryInterface.php',
      15 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Services/SettingService.php',
      16 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Http/Middleware/ResolveTenant.php',
      17 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php',
      18 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Support/TenantContext.php',
      19 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Http/Controllers/Auth/VerifyEmailController.php',
      20 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Actions/Logout.php',
      21 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Forms/LoginForm.php',
      22 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Models/User.php',
      23 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/AppServiceProvider.php',
      24 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/TenantFactory.php',
      25 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/UserFactory.php',
      26 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php',
      27 => '/Users/ridwankadri/Desktop/code/atlas-erp/routes/web.php',
    ),
    'usedTraitDependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Models/Setting.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Models/User.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Enums/TenantStatus.php' => 
  array (
    'fileHash' => 'dead6ef53b7bc19866731372fd7156a873f57558f0bff773a23cde6311f1bb7c',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/TenantFactory.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Enums/UserStatus.php' => 
  array (
    'fileHash' => '03c9a99a515c97baec5f596354056ee003b0821f655cf9ca154ddb6a92994feb',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/CreateUserAction.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Models/User.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/UserFactory.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Exceptions/BusinessException.php' => 
  array (
    'fileHash' => 'ac2e52e1161266305b643e474facee83fb76a83933e2ff7b295ce49023deb653',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/CreateCompanyAction.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Settings/UpdateSettingsAction.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/CreateUserAction.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserAction.php',
      5 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Notifications/SystemNotification.php' => 
  array (
    'fileHash' => 'd6bace8037eb659dfa34ce0595d1482c9437b34833acff83a481cdb9fc32e6e3',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Http/Middleware/ResolveTenant.php' => 
  array (
    'fileHash' => '4fad094c4604419236caeaef98bab850b12baaca60a70f3955b66df81adc8290',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/Filament/AdminPanelProvider.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php' => 
  array (
    'fileHash' => 'a282a63ecedee36a6dd061f8bc1386c727f6e11c5a7e79f0e3008cab232fecb7',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/CreateCompanyAction.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/TenantPolicy.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/CurrentContextWidget.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php',
      5 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Models/Setting.php',
      6 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Http/Middleware/ResolveTenant.php',
      7 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Support/TenantContext.php',
      8 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Forms/LoginForm.php',
      9 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Models/User.php',
      10 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/AppServiceProvider.php',
      11 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/TenantFactory.php',
      12 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/UserFactory.php',
      13 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php',
      14 => '/Users/ridwankadri/Desktop/code/atlas-erp/routes/web.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Support/TenantContext.php' => 
  array (
    'fileHash' => '9e62354ba44033cb1cbd80551a1ab3eb9a7440a10708b76b5aa2284ab84244a7',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/CurrentContextWidget.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Models/Setting.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Http/Middleware/ResolveTenant.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/AppServiceProvider.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/routes/web.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Http/Controllers/Auth/VerifyEmailController.php' => 
  array (
    'fileHash' => 'e876c0f8e1784a19c7aeba5aa5ea0816256d452e64bea9952b360e3501b5a945',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/routes/auth.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Http/Controllers/Controller.php' => 
  array (
    'fileHash' => '25d1c1ef8e6cc8a376553faacfba2b07d9dfaee9bdbb84f14f77517580e9deb1',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Http/Controllers/Auth/VerifyEmailController.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/routes/auth.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Actions/Logout.php' => 
  array (
    'fileHash' => '8cdc7a6dc5b2f39bd2b49865a2bc086cb49a56692af76225315ae7b2a87ad989',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Forms/LoginForm.php' => 
  array (
    'fileHash' => '1dd2cc3e0a8ea5fa734ec46feb87af7f7d6d19fe1ca20e2af5ef190443ae774f',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Models/User.php' => 
  array (
    'fileHash' => 'f25acd495fbccfadc247e69ebd83b21221370f745cd3e6fa3ba1e75eb13ce427',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/CreateCompanyAction.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php',
      2 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Settings/UpdateSettingsAction.php',
      3 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/CreateUserAction.php',
      4 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserAction.php',
      5 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php',
      6 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/TenantPolicy.php',
      7 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/UserPolicy.php',
      8 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationAccessService.php',
      9 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationActivityLogger.php',
      10 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/CurrentContextWidget.php',
      11 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php',
      12 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Http/Middleware/ResolveTenant.php',
      13 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php',
      14 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Http/Controllers/Auth/VerifyEmailController.php',
      15 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Actions/Logout.php',
      16 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Forms/LoginForm.php',
      17 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/AppServiceProvider.php',
      18 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/UserFactory.php',
      19 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/AppServiceProvider.php' => 
  array (
    'fileHash' => 'd102eb26f4dc755fef436a2cdb2183666cd18776b272034002b3268ad7d71cad',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/Filament/AdminPanelProvider.php' => 
  array (
    'fileHash' => '9a739a6d5922c75494f35bde2eaa281ce8128719efcacaf01ef18201ca6be952',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/VoltServiceProvider.php' => 
  array (
    'fileHash' => 'c3f1fc1718e6133d3af395ab954518e0965a8ef15ba61ed35da90ae14a6fd54e',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/AppLayout.php' => 
  array (
    'fileHash' => 'c57c4ac36e5603ca09b66674a3a01b427528948ecf1f7a93d06d52818d9319b5',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/GuestLayout.php' => 
  array (
    'fileHash' => '35663740cd39dc5f726b24e402babb41d0af62e09567c0e717235586fa3d9f80',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/vendor/health/Logo.php' => 
  array (
    'fileHash' => 'c47b0916c3417ec237b553907eb56e185460a2f251eff98e838f824adfc62456',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/vendor/health/StatusIndicator.php' => 
  array (
    'fileHash' => '82a0be717e360e585c81d8d3c42f644ebd7fc3c3900cea8592f4f653919f87a9',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/TenantFactory.php' => 
  array (
    'fileHash' => '4db92306a9d846f29381da77da96e15557fc60799725c9550dab6168ad731456',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php',
      1 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/UserFactory.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/UserFactory.php' => 
  array (
    'fileHash' => '9772cf23677121bd1878cae1f1bf62b6eeccf561092cfed1dd652e09f65b6d44',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/app/Models/User.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php' => 
  array (
    'fileHash' => 'effe40fd26e93f37f06d7eec9ad15c2db09603019118987b66dc089a033a796f',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/RoleAndPermissionSeeder.php' => 
  array (
    'fileHash' => '879e571a8599129e29b4379ed15e2f1c1b91f7dcaa9f11d3914c4ea8c93acf48',
    'dependentFiles' => 
    array (
      0 => '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php',
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/routes/auth.php' => 
  array (
    'fileHash' => '37a0401799c82452badef875d4774be86d9d3629c386ab00d08203132f3a3d54',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/routes/console.php' => 
  array (
    'fileHash' => '9adccc33e7dd400683e434774077c7fdb2f299c5712cedf16a43fdf56f2850fa',
    'dependentFiles' => 
    array (
    ),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/routes/web.php' => 
  array (
    'fileHash' => 'acc675a952685ea820c626df2842288f94b9d43e96a221cc57892d51b0cf9357',
    'dependentFiles' => 
    array (
    ),
  ),
),
	'packageDependencies' => array (
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php' => 
  array (
    0 => 'spatie/laravel-medialibrary',
    1 => 'laravel/framework',
    2 => 'spatie/laravel-activitylog',
    3 => 'filament/filament',
    4 => 'spatie/laravel-permission',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationActivityLogger.php' => 
  array (
    0 => 'filament/filament',
    1 => 'spatie/laravel-medialibrary',
    2 => 'laravel/framework',
    3 => 'spatie/laravel-permission',
    4 => 'spatie/laravel-activitylog',
    5 => 'symfony/http-foundation',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/SystemStatusWidget.php' => 
  array (
    0 => 'filament/schemas',
    1 => 'filament/widgets',
    2 => 'livewire/livewire',
    3 => 'filament/support',
    4 => 'laravel/framework',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'psr/container',
    2 => 'spatie/laravel-medialibrary',
    3 => 'spatie/laravel-activitylog',
    4 => 'filament/filament',
    5 => 'spatie/laravel-permission',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Models/Setting.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'ramsey/uuid',
    2 => 'spatie/laravel-medialibrary',
    3 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Services/SettingService.php' => 
  array (
    0 => 'laravel/framework',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Exceptions/BusinessException.php' => 
  array (
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Support/TenantContext.php' => 
  array (
    0 => 'spatie/laravel-medialibrary',
    1 => 'laravel/framework',
    2 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Http/Controllers/Auth/VerifyEmailController.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'symfony/http-foundation',
    2 => 'filament/filament',
    3 => 'spatie/laravel-medialibrary',
    4 => 'spatie/laravel-permission',
    5 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Http/Controllers/Controller.php' => 
  array (
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Actions/Logout.php' => 
  array (
    0 => 'spatie/laravel-activitylog',
    1 => 'laravel/framework',
    2 => 'symfony/http-foundation',
    3 => 'filament/filament',
    4 => 'spatie/laravel-medialibrary',
    5 => 'spatie/laravel-permission',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Models/User.php' => 
  array (
    0 => 'filament/filament',
    1 => 'spatie/laravel-medialibrary',
    2 => 'laravel/framework',
    3 => 'spatie/laravel-permission',
    4 => 'spatie/laravel-activitylog',
    5 => 'ramsey/uuid',
    6 => 'filament/support',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/Filament/AdminPanelProvider.php' => 
  array (
    0 => 'filament/filament',
    1 => 'laravel/framework',
    2 => 'filament/support',
    3 => 'filament/actions',
    4 => 'filament/schemas',
    5 => 'danharrin/livewire-rate-limiting',
    6 => 'livewire/livewire',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/VoltServiceProvider.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'livewire/volt',
    2 => 'livewire/livewire',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/AppLayout.php' => 
  array (
    0 => 'laravel/framework',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/vendor/health/StatusIndicator.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'spatie/laravel-health',
    2 => 'spatie/enum',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/TenantFactory.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'fakerphp/faker',
    2 => 'ramsey/uuid',
    3 => 'spatie/laravel-medialibrary',
    4 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/routes/console.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'symfony/console',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/CreateCompanyAction.php' => 
  array (
    0 => 'filament/filament',
    1 => 'spatie/laravel-medialibrary',
    2 => 'laravel/framework',
    3 => 'spatie/laravel-permission',
    4 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserAction.php' => 
  array (
    0 => 'filament/filament',
    1 => 'spatie/laravel-medialibrary',
    2 => 'laravel/framework',
    3 => 'spatie/laravel-permission',
    4 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php' => 
  array (
    0 => 'filament/filament',
    1 => 'spatie/laravel-medialibrary',
    2 => 'laravel/framework',
    3 => 'spatie/laravel-permission',
    4 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Enums/PermissionName.php' => 
  array (
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Enums/RoleName.php' => 
  array (
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/TenantPolicy.php' => 
  array (
    0 => 'filament/filament',
    1 => 'spatie/laravel-medialibrary',
    2 => 'laravel/framework',
    3 => 'spatie/laravel-permission',
    4 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationAccessService.php' => 
  array (
    0 => 'filament/filament',
    1 => 'spatie/laravel-medialibrary',
    2 => 'laravel/framework',
    3 => 'spatie/laravel-permission',
    4 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Pages/Dashboard.php' => 
  array (
    0 => 'filament/actions',
    1 => 'filament/schemas',
    2 => 'filament/filament',
    3 => 'danharrin/livewire-rate-limiting',
    4 => 'livewire/livewire',
    5 => 'laravel/framework',
    6 => 'filament/widgets',
    7 => 'filament/support',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/QuickNavigationWidget.php' => 
  array (
    0 => 'filament/widgets',
    1 => 'filament/support',
    2 => 'laravel/framework',
    3 => 'livewire/livewire',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/RecentActivityWidget.php' => 
  array (
    0 => 'filament/widgets',
    1 => 'filament/support',
    2 => 'laravel/framework',
    3 => 'livewire/livewire',
    4 => 'spatie/laravel-activitylog',
    5 => 'nesbot/carbon',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/DTOs/SettingData.php' => 
  array (
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/SettingRepositoryInterface.php' => 
  array (
    0 => 'laravel/framework',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Enums/TenantStatus.php' => 
  array (
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Notifications/SystemNotification.php' => 
  array (
    0 => 'laravel/framework',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Forms/LoginForm.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'livewire/livewire',
    2 => 'symfony/http-foundation',
    3 => 'filament/filament',
    4 => 'spatie/laravel-medialibrary',
    5 => 'spatie/laravel-permission',
    6 => 'spatie/laravel-activitylog',
    7 => 'nesbot/carbon',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'spatie/laravel-medialibrary',
    2 => 'spatie/laravel-activitylog',
    3 => 'filament/filament',
    4 => 'spatie/laravel-permission',
    5 => 'nesbot/carbon',
    6 => 'ramsey/uuid',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/routes/auth.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'symfony/http-foundation',
    2 => 'livewire/volt',
    3 => 'livewire/livewire',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Settings/UpdateSettingsAction.php' => 
  array (
    0 => 'filament/filament',
    1 => 'spatie/laravel-medialibrary',
    2 => 'laravel/framework',
    3 => 'spatie/laravel-permission',
    4 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/CreateUserAction.php' => 
  array (
    0 => 'filament/filament',
    1 => 'spatie/laravel-medialibrary',
    2 => 'laravel/framework',
    3 => 'spatie/laravel-permission',
    4 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/UserPolicy.php' => 
  array (
    0 => 'filament/filament',
    1 => 'spatie/laravel-medialibrary',
    2 => 'laravel/framework',
    3 => 'spatie/laravel-permission',
    4 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/CurrentContextWidget.php' => 
  array (
    0 => 'filament/schemas',
    1 => 'filament/widgets',
    2 => 'livewire/livewire',
    3 => 'filament/support',
    4 => 'laravel/framework',
    5 => 'filament/filament',
    6 => 'spatie/laravel-medialibrary',
    7 => 'spatie/laravel-permission',
    8 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Actions/UpdateSettingAction.php' => 
  array (
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/EloquentSettingRepository.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'ramsey/uuid',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Concerns/BelongsToTenant.php' => 
  array (
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Concerns/HasPublicUuid.php' => 
  array (
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Enums/UserStatus.php' => 
  array (
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Http/Middleware/ResolveTenant.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'symfony/http-foundation',
    2 => 'filament/filament',
    3 => 'spatie/laravel-medialibrary',
    4 => 'spatie/laravel-permission',
    5 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php' => 
  array (
    0 => 'spatie/laravel-medialibrary',
    1 => 'laravel/framework',
    2 => 'spatie/laravel-activitylog',
    3 => 'ramsey/uuid',
    4 => 'filament/filament',
    5 => 'spatie/laravel-permission',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/AppServiceProvider.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'psr/container',
    2 => 'spatie/laravel-medialibrary',
    3 => 'spatie/laravel-activitylog',
    4 => 'filament/filament',
    5 => 'spatie/laravel-permission',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/GuestLayout.php' => 
  array (
    0 => 'laravel/framework',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/vendor/health/Logo.php' => 
  array (
    0 => 'laravel/framework',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/UserFactory.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'fakerphp/faker',
    2 => 'nesbot/carbon',
    3 => 'ramsey/uuid',
    4 => 'spatie/laravel-medialibrary',
    5 => 'spatie/laravel-activitylog',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/RoleAndPermissionSeeder.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'spatie/laravel-permission',
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/routes/web.php' => 
  array (
    0 => 'laravel/framework',
    1 => 'symfony/http-foundation',
    2 => 'spatie/laravel-medialibrary',
    3 => 'spatie/laravel-activitylog',
  ),
),
	'exportedNodesCallback' => static function (): array { return array (
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/CreateCompanyAction.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Administration\\Actions\\Companies\\CreateCompanyAction',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'logger',
               'type' => 'App\\Administration\\Services\\AdministrationActivityLogger',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'execute',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @param  array<string, mixed>  $data
     */',
             'namespace' => 'App\\Administration\\Actions\\Companies',
             'uses' => 
            array (
              'permissionname' => 'App\\Administration\\Enums\\PermissionName',
              'administrationactivitylogger' => 'App\\Administration\\Services\\AdministrationActivityLogger',
              'businessexception' => 'App\\Core\\Shared\\Exceptions\\BusinessException',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'user' => 'App\\Models\\User',
              'db' => 'Illuminate\\Support\\Facades\\DB',
              'str' => 'Illuminate\\Support\\Str',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'App\\Core\\Tenancy\\Models\\Tenant',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'data',
               'type' => 'array',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'actor',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Companies/UpdateCompanyAction.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Administration\\Actions\\Companies\\UpdateCompanyAction',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'access',
               'type' => 'App\\Administration\\Services\\AdministrationAccessService',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'logger',
               'type' => 'App\\Administration\\Services\\AdministrationActivityLogger',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'execute',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @param  array<string, mixed>  $data
     */',
             'namespace' => 'App\\Administration\\Actions\\Companies',
             'uses' => 
            array (
              'permissionname' => 'App\\Administration\\Enums\\PermissionName',
              'administrationaccessservice' => 'App\\Administration\\Services\\AdministrationAccessService',
              'administrationactivitylogger' => 'App\\Administration\\Services\\AdministrationActivityLogger',
              'businessexception' => 'App\\Core\\Shared\\Exceptions\\BusinessException',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'user' => 'App\\Models\\User',
              'db' => 'Illuminate\\Support\\Facades\\DB',
              'str' => 'Illuminate\\Support\\Str',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'App\\Core\\Tenancy\\Models\\Tenant',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'tenant',
               'type' => 'App\\Core\\Tenancy\\Models\\Tenant',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'data',
               'type' => 'array',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            2 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'actor',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Settings/UpdateSettingsAction.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Administration\\Actions\\Settings\\UpdateSettingsAction',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'access',
               'type' => 'App\\Administration\\Services\\AdministrationAccessService',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'settings',
               'type' => 'App\\Core\\Settings\\Services\\SettingService',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'execute',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'settingData',
               'type' => 'App\\Core\\Settings\\DTOs\\SettingData',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'actor',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/CreateUserAction.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Administration\\Actions\\Users\\CreateUserAction',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'access',
               'type' => 'App\\Administration\\Services\\AdministrationAccessService',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'logger',
               'type' => 'App\\Administration\\Services\\AdministrationActivityLogger',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'execute',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $roleNames
     */',
             'namespace' => 'App\\Administration\\Actions\\Users',
             'uses' => 
            array (
              'administrationaccessservice' => 'App\\Administration\\Services\\AdministrationAccessService',
              'administrationactivitylogger' => 'App\\Administration\\Services\\AdministrationActivityLogger',
              'userstatus' => 'App\\Core\\Shared\\Enums\\UserStatus',
              'businessexception' => 'App\\Core\\Shared\\Exceptions\\BusinessException',
              'user' => 'App\\Models\\User',
              'db' => 'Illuminate\\Support\\Facades\\DB',
              'hash' => 'Illuminate\\Support\\Facades\\Hash',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'App\\Models\\User',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'data',
               'type' => 'array',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'roleNames',
               'type' => 'array',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            2 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'actor',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserAction.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Administration\\Actions\\Users\\UpdateUserAction',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'access',
               'type' => 'App\\Administration\\Services\\AdministrationAccessService',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'logger',
               'type' => 'App\\Administration\\Services\\AdministrationActivityLogger',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'execute',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $roleNames
     */',
             'namespace' => 'App\\Administration\\Actions\\Users',
             'uses' => 
            array (
              'administrationaccessservice' => 'App\\Administration\\Services\\AdministrationAccessService',
              'administrationactivitylogger' => 'App\\Administration\\Services\\AdministrationActivityLogger',
              'businessexception' => 'App\\Core\\Shared\\Exceptions\\BusinessException',
              'user' => 'App\\Models\\User',
              'arr' => 'Illuminate\\Support\\Arr',
              'db' => 'Illuminate\\Support\\Facades\\DB',
              'hash' => 'Illuminate\\Support\\Facades\\Hash',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'App\\Models\\User',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'subject',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'data',
               'type' => 'array',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            2 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'roleNames',
               'type' => 'array',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            3 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'actor',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Actions/Users/UpdateUserStatusAction.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Administration\\Actions\\Users\\UpdateUserStatusAction',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'access',
               'type' => 'App\\Administration\\Services\\AdministrationAccessService',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'logger',
               'type' => 'App\\Administration\\Services\\AdministrationActivityLogger',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'execute',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'App\\Models\\User',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'subject',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'status',
               'type' => 'App\\Core\\Shared\\Enums\\UserStatus',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            2 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'actor',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Enums/PermissionName.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedEnumNode::__set_state(array(
       'name' => 'App\\Administration\\Enums\\PermissionName',
       'scalarType' => 'string',
       'phpDoc' => NULL,
       'implements' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'DashboardView',
           'value' => '\'dashboard.view\'',
           'phpDoc' => NULL,
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'CompaniesView',
           'value' => '\'companies.view\'',
           'phpDoc' => NULL,
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'CompaniesCreate',
           'value' => '\'companies.create\'',
           'phpDoc' => NULL,
        )),
        3 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'CompaniesUpdate',
           'value' => '\'companies.update\'',
           'phpDoc' => NULL,
        )),
        4 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'UsersView',
           'value' => '\'users.view\'',
           'phpDoc' => NULL,
        )),
        5 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'UsersCreate',
           'value' => '\'users.create\'',
           'phpDoc' => NULL,
        )),
        6 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'UsersUpdate',
           'value' => '\'users.update\'',
           'phpDoc' => NULL,
        )),
        7 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'UsersDelete',
           'value' => '\'users.delete\'',
           'phpDoc' => NULL,
        )),
        8 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'UsersManageStatus',
           'value' => '\'users.manage_status\'',
           'phpDoc' => NULL,
        )),
        9 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'SettingsManage',
           'value' => '\'settings.manage\'',
           'phpDoc' => NULL,
        )),
        10 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'HealthView',
           'value' => '\'health.view\'',
           'phpDoc' => NULL,
        )),
        11 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'values',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return list<string>
     */',
             'namespace' => 'App\\Administration\\Enums',
             'uses' => 
            array (
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => true,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Enums/RoleName.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedEnumNode::__set_state(array(
       'name' => 'App\\Administration\\Enums\\RoleName',
       'scalarType' => 'string',
       'phpDoc' => NULL,
       'implements' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'SuperAdministrator',
           'value' => '\'Super Administrator\'',
           'phpDoc' => NULL,
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'CompanyAdministrator',
           'value' => '\'Company Administrator\'',
           'phpDoc' => NULL,
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'OperationsManager',
           'value' => '\'Operations Manager\'',
           'phpDoc' => NULL,
        )),
        3 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'FinanceManager',
           'value' => '\'Finance Manager\'',
           'phpDoc' => NULL,
        )),
        4 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'FleetManager',
           'value' => '\'Fleet Manager\'',
           'phpDoc' => NULL,
        )),
        5 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'WarehouseManager',
           'value' => '\'Warehouse Manager\'',
           'phpDoc' => NULL,
        )),
        6 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'StandardUser',
           'value' => '\'Standard User\'',
           'phpDoc' => NULL,
        )),
        7 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'values',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return list<string>
     */',
             'namespace' => 'App\\Administration\\Enums',
             'uses' => 
            array (
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => true,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/TenantPolicy.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Administration\\Policies\\TenantPolicy',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'view',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'user',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'tenant',
               'type' => 'App\\Core\\Tenancy\\Models\\Tenant',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'update',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'user',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'tenant',
               'type' => 'App\\Core\\Tenancy\\Models\\Tenant',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Policies/UserPolicy.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Administration\\Policies\\UserPolicy',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'view',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'user',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'subject',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'update',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'user',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'subject',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationAccessService.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Administration\\Services\\AdministrationAccessService',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'isSuperAdministrator',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'user',
               'type' => '?App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'canManageTenant',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'user',
               'type' => '?App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'tenantId',
               'type' => '?int',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'canAssignRole',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'actor',
               'type' => 'App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'roleName',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Administration/Services/AdministrationActivityLogger.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Administration\\Services\\AdministrationActivityLogger',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'log',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @param  array<string, mixed>  $properties
     */',
             'namespace' => 'App\\Administration\\Services',
             'uses' => 
            array (
              'user' => 'App\\Models\\User',
              'model' => 'Illuminate\\Database\\Eloquent\\Model',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'event',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'description',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            2 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'actor',
               'type' => '?App\\Models\\User',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => true,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            3 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'subject',
               'type' => '?Illuminate\\Database\\Eloquent\\Model',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => true,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            4 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'properties',
               'type' => 'array',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => true,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Pages/Dashboard.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Administration\\Filament\\Pages\\Dashboard',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Filament\\Pages\\Dashboard',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'getWidgets',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/CurrentContextWidget.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Administration\\Filament\\Widgets\\CurrentContextWidget',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Filament\\Widgets\\StatsOverviewWidget',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'getStats',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/QuickNavigationWidget.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Administration\\Filament\\Widgets\\QuickNavigationWidget',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Filament\\Widgets\\Widget',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'view',
          ),
           'phpDoc' => NULL,
           'type' => 'string',
           'public' => false,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
          ),
           'hooks' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/RecentActivityWidget.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Administration\\Filament\\Widgets\\RecentActivityWidget',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Filament\\Widgets\\Widget',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'view',
          ),
           'phpDoc' => NULL,
           'type' => 'string',
           'public' => false,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
          ),
           'hooks' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'columnSpan',
          ),
           'phpDoc' => NULL,
           'type' => 'int|string|array',
           'public' => false,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
          ),
           'hooks' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'getViewData',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Filament/Widgets/SystemStatusWidget.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Administration\\Filament\\Widgets\\SystemStatusWidget',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Filament\\Widgets\\StatsOverviewWidget',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'getStats',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Administration/Providers/CoreServiceProvider.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Administration\\Providers\\CoreServiceProvider',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Support\\ServiceProvider',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'register',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'boot',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Actions/UpdateSettingAction.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Settings\\Actions\\UpdateSettingAction',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'settings',
               'type' => 'App\\Core\\Settings\\Services\\SettingService',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'execute',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'settingData',
               'type' => 'App\\Core\\Settings\\DTOs\\SettingData',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/DTOs/SettingData.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Settings\\DTOs\\SettingData',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'tenantId',
               'type' => '?int',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 65,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'group',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 65,
            )),
            2 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'key',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 65,
            )),
            3 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'value',
               'type' => 'mixed',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 65,
            )),
            4 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'isEncrypted',
               'type' => 'bool',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => true,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 65,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Models/Setting.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Settings\\Models\\Setting',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Database\\Eloquent\\Model',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
        0 => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'fillable',
          ),
           'phpDoc' => NULL,
           'type' => NULL,
           'public' => false,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
          ),
           'hooks' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'casts',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'tenant',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return BelongsTo<Tenant, $this>
     */',
             'namespace' => 'App\\Core\\Settings\\Models',
             'uses' => 
            array (
              'belongstotenant' => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
              'haspublicuuid' => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'model' => 'Illuminate\\Database\\Eloquent\\Model',
              'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/EloquentSettingRepository.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Settings\\Repositories\\EloquentSettingRepository',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
        0 => 'App\\Core\\Settings\\Repositories\\SettingRepositoryInterface',
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'allForTenant',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return Collection<int, Setting>
     */',
             'namespace' => 'App\\Core\\Settings\\Repositories',
             'uses' => 
            array (
              'settingdata' => 'App\\Core\\Settings\\DTOs\\SettingData',
              'setting' => 'App\\Core\\Settings\\Models\\Setting',
              'collection' => 'Illuminate\\Support\\Collection',
              'str' => 'Illuminate\\Support\\Str',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\Support\\Collection',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'tenantId',
               'type' => '?int',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'upsert',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'settingData',
               'type' => 'App\\Core\\Settings\\DTOs\\SettingData',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Repositories/SettingRepositoryInterface.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedInterfaceNode::__set_state(array(
       'name' => 'App\\Core\\Settings\\Repositories\\SettingRepositoryInterface',
       'phpDoc' => NULL,
       'extends' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'allForTenant',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return Collection<int, Setting>
     */',
             'namespace' => 'App\\Core\\Settings\\Repositories',
             'uses' => 
            array (
              'settingdata' => 'App\\Core\\Settings\\DTOs\\SettingData',
              'setting' => 'App\\Core\\Settings\\Models\\Setting',
              'collection' => 'Illuminate\\Support\\Collection',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\Support\\Collection',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'tenantId',
               'type' => '?int',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'upsert',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'settingData',
               'type' => 'App\\Core\\Settings\\DTOs\\SettingData',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Settings/Services/SettingService.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Settings\\Services\\SettingService',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'settings',
               'type' => 'App\\Core\\Settings\\Repositories\\SettingRepositoryInterface',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'allForTenant',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return Collection<int, Setting>
     */',
             'namespace' => 'App\\Core\\Settings\\Services',
             'uses' => 
            array (
              'settingdata' => 'App\\Core\\Settings\\DTOs\\SettingData',
              'setting' => 'App\\Core\\Settings\\Models\\Setting',
              'settingrepositoryinterface' => 'App\\Core\\Settings\\Repositories\\SettingRepositoryInterface',
              'collection' => 'Illuminate\\Support\\Collection',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\Support\\Collection',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'tenantId',
               'type' => '?int',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'update',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'settingData',
               'type' => 'App\\Core\\Settings\\DTOs\\SettingData',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Concerns/BelongsToTenant.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedTraitNode::__set_state(array(
       'name' => 'App\\Core\\Shared\\Concerns\\BelongsToTenant',
       'phpDoc' => NULL,
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'bootBelongsToTenant',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => true,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Concerns/HasPublicUuid.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedTraitNode::__set_state(array(
       'name' => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
       'phpDoc' => NULL,
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'bootHasPublicUuid',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => true,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Enums/TenantStatus.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedEnumNode::__set_state(array(
       'name' => 'App\\Core\\Shared\\Enums\\TenantStatus',
       'scalarType' => 'string',
       'phpDoc' => NULL,
       'implements' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'Active',
           'value' => '\'active\'',
           'phpDoc' => NULL,
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'Inactive',
           'value' => '\'inactive\'',
           'phpDoc' => NULL,
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'Suspended',
           'value' => '\'suspended\'',
           'phpDoc' => NULL,
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Enums/UserStatus.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedEnumNode::__set_state(array(
       'name' => 'App\\Core\\Shared\\Enums\\UserStatus',
       'scalarType' => 'string',
       'phpDoc' => NULL,
       'implements' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'Active',
           'value' => '\'active\'',
           'phpDoc' => NULL,
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'Inactive',
           'value' => '\'inactive\'',
           'phpDoc' => NULL,
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedEnumCaseNode::__set_state(array(
           'name' => 'Suspended',
           'value' => '\'suspended\'',
           'phpDoc' => NULL,
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Exceptions/BusinessException.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Shared\\Exceptions\\BusinessException',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'RuntimeException',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'message',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'status',
               'type' => 'int',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => true,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'status',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'int',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Shared/Notifications/SystemNotification.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Shared\\Notifications\\SystemNotification',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Notifications\\Notification',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
        0 => 'Illuminate\\Bus\\Queueable',
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @param  array<int, string>  $channels
     */',
             'namespace' => 'App\\Core\\Shared\\Notifications',
             'uses' => 
            array (
              'queueable' => 'Illuminate\\Bus\\Queueable',
              'mailmessage' => 'Illuminate\\Notifications\\Messages\\MailMessage',
              'notification' => 'Illuminate\\Notifications\\Notification',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'subject',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'message',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
            2 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'channels',
               'type' => 'array',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => true,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 68,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'via',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return array<int, string>
     */',
             'namespace' => 'App\\Core\\Shared\\Notifications',
             'uses' => 
            array (
              'queueable' => 'Illuminate\\Bus\\Queueable',
              'mailmessage' => 'Illuminate\\Notifications\\Messages\\MailMessage',
              'notification' => 'Illuminate\\Notifications\\Notification',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'notifiable',
               'type' => 'object',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'toArray',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return array<string, string>
     */',
             'namespace' => 'App\\Core\\Shared\\Notifications',
             'uses' => 
            array (
              'queueable' => 'Illuminate\\Bus\\Queueable',
              'mailmessage' => 'Illuminate\\Notifications\\Messages\\MailMessage',
              'notification' => 'Illuminate\\Notifications\\Notification',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'notifiable',
               'type' => 'object',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        3 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'toMail',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\Notifications\\Messages\\MailMessage',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'notifiable',
               'type' => 'object',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Http/Middleware/ResolveTenant.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Tenancy\\Http\\Middleware\\ResolveTenant',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'handle',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Symfony\\Component\\HttpFoundation\\Response',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'request',
               'type' => 'Illuminate\\Http\\Request',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
            1 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'next',
               'type' => 'Closure',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Models/Tenant.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Tenancy\\Models\\Tenant',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Database\\Eloquent\\Model',
       'implements' => 
      array (
        0 => 'Spatie\\MediaLibrary\\HasMedia',
      ),
       'usedTraits' => 
      array (
        0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => 'Spatie\\MediaLibrary\\InteractsWithMedia',
        3 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'fillable',
          ),
           'phpDoc' => NULL,
           'type' => NULL,
           'public' => false,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
          ),
           'hooks' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'casts',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'newFactory',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => true,
           'returnType' => 'Database\\Factories\\TenantFactory',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        3 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'isActive',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        4 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'users',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return HasMany<User, $this>
     */',
             'namespace' => 'App\\Core\\Tenancy\\Models',
             'uses' => 
            array (
              'setting' => 'App\\Core\\Settings\\Models\\Setting',
              'haspublicuuid' => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
              'tenantstatus' => 'App\\Core\\Shared\\Enums\\TenantStatus',
              'user' => 'App\\Models\\User',
              'tenantfactory' => 'Database\\Factories\\TenantFactory',
              'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
              'model' => 'Illuminate\\Database\\Eloquent\\Model',
              'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
              'logoptions' => 'Spatie\\Activitylog\\LogOptions',
              'logsactivity' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
              'hasmedia' => 'Spatie\\MediaLibrary\\HasMedia',
              'interactswithmedia' => 'Spatie\\MediaLibrary\\InteractsWithMedia',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        5 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'settings',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return HasMany<Setting, $this>
     */',
             'namespace' => 'App\\Core\\Tenancy\\Models',
             'uses' => 
            array (
              'setting' => 'App\\Core\\Settings\\Models\\Setting',
              'haspublicuuid' => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
              'tenantstatus' => 'App\\Core\\Shared\\Enums\\TenantStatus',
              'user' => 'App\\Models\\User',
              'tenantfactory' => 'Database\\Factories\\TenantFactory',
              'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
              'model' => 'Illuminate\\Database\\Eloquent\\Model',
              'hasmany' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
              'logoptions' => 'Spatie\\Activitylog\\LogOptions',
              'logsactivity' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
              'hasmedia' => 'Spatie\\MediaLibrary\\HasMedia',
              'interactswithmedia' => 'Spatie\\MediaLibrary\\InteractsWithMedia',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        6 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'getActivitylogOptions',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Spatie\\Activitylog\\LogOptions',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Core/Tenancy/Support/TenantContext.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Core\\Tenancy\\Support\\TenantContext',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'set',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'tenant',
               'type' => '?App\\Core\\Tenancy\\Models\\Tenant',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'tenant',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => '?App\\Core\\Tenancy\\Models\\Tenant',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'id',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => '?int',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Http/Controllers/Auth/VerifyEmailController.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Http\\Controllers\\Auth\\VerifyEmailController',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'App\\Http\\Controllers\\Controller',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__invoke',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Mark the authenticated user\'s email address as verified.
     */',
             'namespace' => 'App\\Http\\Controllers\\Auth',
             'uses' => 
            array (
              'controller' => 'App\\Http\\Controllers\\Controller',
              'user' => 'App\\Models\\User',
              'verified' => 'Illuminate\\Auth\\Events\\Verified',
              'emailverificationrequest' => 'Illuminate\\Foundation\\Auth\\EmailVerificationRequest',
              'redirectresponse' => 'Illuminate\\Http\\RedirectResponse',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\Http\\RedirectResponse',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'request',
               'type' => 'Illuminate\\Foundation\\Auth\\EmailVerificationRequest',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Http/Controllers/Controller.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Http\\Controllers\\Controller',
       'phpDoc' => NULL,
       'abstract' => true,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Actions/Logout.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Livewire\\Actions\\Logout',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => NULL,
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__invoke',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Log the current user out of the application.
     */',
             'namespace' => 'App\\Livewire\\Actions',
             'uses' => 
            array (
              'auth' => 'Illuminate\\Support\\Facades\\Auth',
              'session' => 'Illuminate\\Support\\Facades\\Session',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Livewire/Forms/LoginForm.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Livewire\\Forms\\LoginForm',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Livewire\\Form',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'email',
          ),
           'phpDoc' => NULL,
           'type' => 'string',
           'public' => true,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedAttributeNode::__set_state(array(
               'name' => 'Livewire\\Attributes\\Validate',
               'args' => 
              array (
                0 => '\'required|string|email\'',
              ),
            )),
          ),
           'hooks' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'password',
          ),
           'phpDoc' => NULL,
           'type' => 'string',
           'public' => true,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedAttributeNode::__set_state(array(
               'name' => 'Livewire\\Attributes\\Validate',
               'args' => 
              array (
                0 => '\'required|string\'',
              ),
            )),
          ),
           'hooks' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'remember',
          ),
           'phpDoc' => NULL,
           'type' => 'bool',
           'public' => true,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedAttributeNode::__set_state(array(
               'name' => 'Livewire\\Attributes\\Validate',
               'args' => 
              array (
                0 => '\'boolean\'',
              ),
            )),
          ),
           'hooks' => 
          array (
          ),
        )),
        3 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'authenticate',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Attempt to authenticate the request\'s credentials.
     *
     * @throws ValidationException
     */',
             'namespace' => 'App\\Livewire\\Forms',
             'uses' => 
            array (
              'lockout' => 'Illuminate\\Auth\\Events\\Lockout',
              'auth' => 'Illuminate\\Support\\Facades\\Auth',
              'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
              'str' => 'Illuminate\\Support\\Str',
              'validationexception' => 'Illuminate\\Validation\\ValidationException',
              'validate' => 'Livewire\\Attributes\\Validate',
              'form' => 'Livewire\\Form',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        4 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'ensureIsNotRateLimited',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Ensure the authentication request is not rate limited.
     */',
             'namespace' => 'App\\Livewire\\Forms',
             'uses' => 
            array (
              'lockout' => 'Illuminate\\Auth\\Events\\Lockout',
              'auth' => 'Illuminate\\Support\\Facades\\Auth',
              'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
              'str' => 'Illuminate\\Support\\Str',
              'validationexception' => 'Illuminate\\Validation\\ValidationException',
              'validate' => 'Livewire\\Attributes\\Validate',
              'form' => 'Livewire\\Form',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        5 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'throttleKey',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Get the authentication rate limiting throttle key.
     */',
             'namespace' => 'App\\Livewire\\Forms',
             'uses' => 
            array (
              'lockout' => 'Illuminate\\Auth\\Events\\Lockout',
              'auth' => 'Illuminate\\Support\\Facades\\Auth',
              'ratelimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
              'str' => 'Illuminate\\Support\\Str',
              'validationexception' => 'Illuminate\\Validation\\ValidationException',
              'validate' => 'Livewire\\Attributes\\Validate',
              'form' => 'Livewire\\Form',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'string',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Models/User.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Models\\User',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Foundation\\Auth\\User',
       'implements' => 
      array (
        0 => 'Filament\\Models\\Contracts\\FilamentUser',
        1 => 'Spatie\\MediaLibrary\\HasMedia',
        2 => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
      ),
       'usedTraits' => 
      array (
        0 => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
        1 => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
        2 => 'Spatie\\Permission\\Traits\\HasRoles',
        3 => 'Spatie\\MediaLibrary\\InteractsWithMedia',
        4 => 'Spatie\\Activitylog\\Traits\\LogsActivity',
        5 => 'Illuminate\\Notifications\\Notifiable',
        6 => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'fillable',
          ),
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */',
             'namespace' => 'App\\Models',
             'uses' => 
            array (
              'haspublicuuid' => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
              'userstatus' => 'App\\Core\\Shared\\Enums\\UserStatus',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'userfactory' => 'Database\\Factories\\UserFactory',
              'filamentuser' => 'Filament\\Models\\Contracts\\FilamentUser',
              'panel' => 'Filament\\Panel',
              'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
              'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
              'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
              'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
              'authenticatable' => 'Illuminate\\Foundation\\Auth\\User',
              'notifiable' => 'Illuminate\\Notifications\\Notifiable',
              'logoptions' => 'Spatie\\Activitylog\\LogOptions',
              'logsactivity' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
              'hasmedia' => 'Spatie\\MediaLibrary\\HasMedia',
              'interactswithmedia' => 'Spatie\\MediaLibrary\\InteractsWithMedia',
              'hasroles' => 'Spatie\\Permission\\Traits\\HasRoles',
            ),
             'constUses' => 
            array (
            ),
          )),
           'type' => NULL,
           'public' => false,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
          ),
           'hooks' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'hidden',
          ),
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */',
             'namespace' => 'App\\Models',
             'uses' => 
            array (
              'haspublicuuid' => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
              'userstatus' => 'App\\Core\\Shared\\Enums\\UserStatus',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'userfactory' => 'Database\\Factories\\UserFactory',
              'filamentuser' => 'Filament\\Models\\Contracts\\FilamentUser',
              'panel' => 'Filament\\Panel',
              'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
              'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
              'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
              'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
              'authenticatable' => 'Illuminate\\Foundation\\Auth\\User',
              'notifiable' => 'Illuminate\\Notifications\\Notifiable',
              'logoptions' => 'Spatie\\Activitylog\\LogOptions',
              'logsactivity' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
              'hasmedia' => 'Spatie\\MediaLibrary\\HasMedia',
              'interactswithmedia' => 'Spatie\\MediaLibrary\\InteractsWithMedia',
              'hasroles' => 'Spatie\\Permission\\Traits\\HasRoles',
            ),
             'constUses' => 
            array (
            ),
          )),
           'type' => NULL,
           'public' => false,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
          ),
           'hooks' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'casts',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */',
             'namespace' => 'App\\Models',
             'uses' => 
            array (
              'haspublicuuid' => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
              'userstatus' => 'App\\Core\\Shared\\Enums\\UserStatus',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'userfactory' => 'Database\\Factories\\UserFactory',
              'filamentuser' => 'Filament\\Models\\Contracts\\FilamentUser',
              'panel' => 'Filament\\Panel',
              'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
              'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
              'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
              'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
              'authenticatable' => 'Illuminate\\Foundation\\Auth\\User',
              'notifiable' => 'Illuminate\\Notifications\\Notifiable',
              'logoptions' => 'Spatie\\Activitylog\\LogOptions',
              'logsactivity' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
              'hasmedia' => 'Spatie\\MediaLibrary\\HasMedia',
              'interactswithmedia' => 'Spatie\\MediaLibrary\\InteractsWithMedia',
              'hasroles' => 'Spatie\\Permission\\Traits\\HasRoles',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        3 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'tenant',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * @return BelongsTo<Tenant, $this>
     */',
             'namespace' => 'App\\Models',
             'uses' => 
            array (
              'haspublicuuid' => 'App\\Core\\Shared\\Concerns\\HasPublicUuid',
              'userstatus' => 'App\\Core\\Shared\\Enums\\UserStatus',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'userfactory' => 'Database\\Factories\\UserFactory',
              'filamentuser' => 'Filament\\Models\\Contracts\\FilamentUser',
              'panel' => 'Filament\\Panel',
              'mustverifyemail' => 'Illuminate\\Contracts\\Auth\\MustVerifyEmail',
              'hasfactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
              'belongsto' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
              'softdeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
              'authenticatable' => 'Illuminate\\Foundation\\Auth\\User',
              'notifiable' => 'Illuminate\\Notifications\\Notifiable',
              'logoptions' => 'Spatie\\Activitylog\\LogOptions',
              'logsactivity' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
              'hasmedia' => 'Spatie\\MediaLibrary\\HasMedia',
              'interactswithmedia' => 'Spatie\\MediaLibrary\\InteractsWithMedia',
              'hasroles' => 'Spatie\\Permission\\Traits\\HasRoles',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        4 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'canAccessPanel',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'panel',
               'type' => 'Filament\\Panel',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        5 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'getFullNameAttribute',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'string',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        6 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'isActive',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        7 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'isInactive',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        8 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'isSuspended',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'bool',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        9 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'getActivitylogOptions',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Spatie\\Activitylog\\LogOptions',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/AppServiceProvider.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Providers\\AppServiceProvider',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Support\\ServiceProvider',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'register',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Register any application services.
     */',
             'namespace' => 'App\\Providers',
             'uses' => 
            array (
              'setting' => 'App\\Core\\Settings\\Models\\Setting',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'tenantcontext' => 'App\\Core\\Tenancy\\Support\\TenantContext',
              'user' => 'App\\Models\\User',
              'relation' => 'Illuminate\\Database\\Eloquent\\Relations\\Relation',
              'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'boot',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Bootstrap any application services.
     */',
             'namespace' => 'App\\Providers',
             'uses' => 
            array (
              'setting' => 'App\\Core\\Settings\\Models\\Setting',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'tenantcontext' => 'App\\Core\\Tenancy\\Support\\TenantContext',
              'user' => 'App\\Models\\User',
              'relation' => 'Illuminate\\Database\\Eloquent\\Relations\\Relation',
              'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/Filament/AdminPanelProvider.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Providers\\Filament\\AdminPanelProvider',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Filament\\PanelProvider',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'panel',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Filament\\Panel',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'panel',
               'type' => 'Filament\\Panel',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/Providers/VoltServiceProvider.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\Providers\\VoltServiceProvider',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Support\\ServiceProvider',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'register',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Register services.
     */',
             'namespace' => 'App\\Providers',
             'uses' => 
            array (
              'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
              'volt' => 'Livewire\\Volt\\Volt',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'boot',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Bootstrap services.
     */',
             'namespace' => 'App\\Providers',
             'uses' => 
            array (
              'serviceprovider' => 'Illuminate\\Support\\ServiceProvider',
              'volt' => 'Livewire\\Volt\\Volt',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/AppLayout.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\View\\Components\\AppLayout',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\View\\Component',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'render',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Get the view / contents that represents the component.
     */',
             'namespace' => 'App\\View\\Components',
             'uses' => 
            array (
              'component' => 'Illuminate\\View\\Component',
              'view' => 'Illuminate\\View\\View',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\View\\View',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/GuestLayout.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'App\\View\\Components\\GuestLayout',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\View\\Component',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'render',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Get the view / contents that represents the component.
     */',
             'namespace' => 'App\\View\\Components',
             'uses' => 
            array (
              'component' => 'Illuminate\\View\\Component',
              'view' => 'Illuminate\\View\\View',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\View\\View',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/vendor/health/Logo.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'Spatie\\Health\\Components\\Logo',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\View\\Component',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'render',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\View\\View',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/app/View/Components/vendor/health/StatusIndicator.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'Spatie\\Health\\Components\\StatusIndicator',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\View\\Component',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => '__construct',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => NULL,
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'result',
               'type' => 'Spatie\\Health\\ResultStores\\StoredCheckResults\\StoredCheckResult',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 1,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'render',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'Illuminate\\View\\View',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'getBackgroundColor',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'string',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'status',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        3 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'getIconColor',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'string',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'status',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
        4 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'getIcon',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => false,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'string',
           'parameters' => 
          array (
            0 => 
            \PHPStan\Dependency\ExportedNode\ExportedParameterNode::__set_state(array(
               'name' => 'status',
               'type' => 'string',
               'byRef' => false,
               'variadic' => false,
               'hasDefault' => false,
               'attributes' => 
              array (
              ),
               'phpDoc' => NULL,
               'flags' => 0,
            )),
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/TenantFactory.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'Database\\Factories\\TenantFactory',
       'phpDoc' => 
      \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
         'phpDocString' => '/**
 * @extends Factory<Tenant>
 */',
         'namespace' => 'Database\\Factories',
         'uses' => 
        array (
          'tenantstatus' => 'App\\Core\\Shared\\Enums\\TenantStatus',
          'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
          'factory' => 'Illuminate\\Database\\Eloquent\\Factories\\Factory',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'constUses' => 
        array (
        ),
      )),
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Database\\Eloquent\\Factories\\Factory',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'model',
          ),
           'phpDoc' => NULL,
           'type' => NULL,
           'public' => false,
           'private' => false,
           'static' => false,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
          ),
           'hooks' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'definition',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/factories/UserFactory.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'Database\\Factories\\UserFactory',
       'phpDoc' => 
      \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
         'phpDocString' => '/**
 * @extends Factory<User>
 */',
         'namespace' => 'Database\\Factories',
         'uses' => 
        array (
          'userstatus' => 'App\\Core\\Shared\\Enums\\UserStatus',
          'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
          'user' => 'App\\Models\\User',
          'factory' => 'Illuminate\\Database\\Eloquent\\Factories\\Factory',
          'hash' => 'Illuminate\\Support\\Facades\\Hash',
          'str' => 'Illuminate\\Support\\Str',
        ),
         'constUses' => 
        array (
        ),
      )),
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Database\\Eloquent\\Factories\\Factory',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedPropertiesNode::__set_state(array(
           'names' => 
          array (
            0 => 'password',
          ),
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * The current password being used by the factory.
     */',
             'namespace' => 'Database\\Factories',
             'uses' => 
            array (
              'userstatus' => 'App\\Core\\Shared\\Enums\\UserStatus',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'user' => 'App\\Models\\User',
              'factory' => 'Illuminate\\Database\\Eloquent\\Factories\\Factory',
              'hash' => 'Illuminate\\Support\\Facades\\Hash',
              'str' => 'Illuminate\\Support\\Str',
            ),
             'constUses' => 
            array (
            ),
          )),
           'type' => '?string',
           'public' => false,
           'private' => false,
           'static' => true,
           'readonly' => false,
           'abstract' => false,
           'final' => false,
           'publicSet' => false,
           'protectedSet' => false,
           'privateSet' => false,
           'virtual' => false,
           'attributes' => 
          array (
          ),
           'hooks' => 
          array (
          ),
        )),
        1 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'definition',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Define the model\'s default state.
     *
     * @return array<string, mixed>
     */',
             'namespace' => 'Database\\Factories',
             'uses' => 
            array (
              'userstatus' => 'App\\Core\\Shared\\Enums\\UserStatus',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'user' => 'App\\Models\\User',
              'factory' => 'Illuminate\\Database\\Eloquent\\Factories\\Factory',
              'hash' => 'Illuminate\\Support\\Facades\\Hash',
              'str' => 'Illuminate\\Support\\Str',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'array',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
        2 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'unverified',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Indicate that the model\'s email address should be unverified.
     */',
             'namespace' => 'Database\\Factories',
             'uses' => 
            array (
              'userstatus' => 'App\\Core\\Shared\\Enums\\UserStatus',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'user' => 'App\\Models\\User',
              'factory' => 'Illuminate\\Database\\Eloquent\\Factories\\Factory',
              'hash' => 'Illuminate\\Support\\Facades\\Hash',
              'str' => 'Illuminate\\Support\\Str',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'static',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/DatabaseSeeder.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'Database\\Seeders\\DatabaseSeeder',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Database\\Seeder',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
        0 => 'Illuminate\\Database\\Console\\Seeds\\WithoutModelEvents',
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'run',
           'phpDoc' => 
          \PHPStan\Dependency\ExportedNode\ExportedPhpDocNode::__set_state(array(
             'phpDocString' => '/**
     * Seed the application\'s database.
     */',
             'namespace' => 'Database\\Seeders',
             'uses' => 
            array (
              'rolename' => 'App\\Administration\\Enums\\RoleName',
              'settingdata' => 'App\\Core\\Settings\\DTOs\\SettingData',
              'settingservice' => 'App\\Core\\Settings\\Services\\SettingService',
              'tenant' => 'App\\Core\\Tenancy\\Models\\Tenant',
              'user' => 'App\\Models\\User',
              'withoutmodelevents' => 'Illuminate\\Database\\Console\\Seeds\\WithoutModelEvents',
              'seeder' => 'Illuminate\\Database\\Seeder',
              'db' => 'Illuminate\\Support\\Facades\\DB',
              'str' => 'Illuminate\\Support\\Str',
            ),
             'constUses' => 
            array (
            ),
          )),
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
  '/Users/ridwankadri/Desktop/code/atlas-erp/database/seeders/RoleAndPermissionSeeder.php' => 
  array (
    0 => 
    \PHPStan\Dependency\ExportedNode\ExportedClassNode::__set_state(array(
       'name' => 'Database\\Seeders\\RoleAndPermissionSeeder',
       'phpDoc' => NULL,
       'abstract' => false,
       'final' => false,
       'extends' => 'Illuminate\\Database\\Seeder',
       'implements' => 
      array (
      ),
       'usedTraits' => 
      array (
      ),
       'traitUseAdaptations' => 
      array (
      ),
       'statements' => 
      array (
        0 => 
        \PHPStan\Dependency\ExportedNode\ExportedMethodNode::__set_state(array(
           'name' => 'run',
           'phpDoc' => NULL,
           'byRef' => false,
           'public' => true,
           'private' => false,
           'abstract' => false,
           'final' => false,
           'static' => false,
           'returnType' => 'void',
           'parameters' => 
          array (
          ),
           'attributes' => 
          array (
          ),
        )),
      ),
       'attributes' => 
      array (
      ),
    )),
  ),
); },
];
