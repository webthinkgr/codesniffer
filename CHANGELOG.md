# Changelog

All Notable changes of `webthink/codesniffer` will be documented in this file. See this [url](http://keepachangelog.com/)

## v2.1.0 - DATE

### Added
- Added a new sniff `DeprecatedMissingDescriptionSniff`. The rule checks if the `@deprecated` annotation has any description
after the annotation in order to make sure that the developer wrote the reason why the function was deprecated and 
to use an alternative.
- Created a new sniff `ValidAbstractNameSniff` in order to enforce all abstract classes to have the prefix `Abstract`.
- Created a new sniff `ValidExceptionNameSniff` in order to enforce all exceptions to have the suffix `Exception`.
- Created a new sniff `OperatorSpacingSniff` in order to override some settings of the `OperatorSpacingSniff` from squizlabs.
- Created a new sniff `YodaSniff` that disallows Yoda comparisons.

### Changed
- Deprecated the `LanguageConstructSpacingSniff` since codesniff fixed their bug. See:  https://github.com/squizlabs/PHP_CodeSniffer/pull/1337
- Removed `is_null` from discouraged functions and moved it to ForbiddenFunctions along with `dd`.
- Function `rand` should not be used there for it was added in `AliasFunctionsSniff` and `mt_rand` should be used instead. 
- Since PSR-5 is dropped `DeprecatedTagsSniff` sniff does not make any sense any more and it is deprecated and it 
will be removed in later version.

## v2.0.0 - 2018-03-25

### Changed
- Support PHP > 7.1 hence the sniffs for checking the code if it is compatible against PHP 7 were removed.
Since Codesniffer v3.x has started using namespaces package `wimg/php-compatibility` has also migrated to that version
of codesniffer and made it's install procedure easier through composer. If you still want to check your code against
PHP 7. So I suggest using that package.

## v1.1.0 - 2017-11-11

### Added
- Added a new sniff for enforcing Plural Class naming.
- Added a new sniff for enforcing Singular Class naming.

## v1.0.1 - 2017-08-02

## Fix
- Sniff `Formatting.ClassCreateInstanceSniff` was using the function `each` that is deprecated in PHP 7.2

## v1.0.0 - 2017-08-02

### Added
- Created a custom rule for silenced errors which excludes the `fopen` and `trigger_error` functions.
    - We already apply this rule but without the excluded functions.
- Created a custom rule which does not allow more than one space between the `namespace` keyword and the actual namespace.
    - The rule will be autofixable.
- Created a custom rule that disallows the instantiation of a class without parenthesis.
    - This rule is applied according to [PSR-12][PSR-12] draft version.
    - The rule will be autofixable.
- Created a custom rule for disallowing the type cast using long form types.
    - This rule is applied according to [PSR-12][PSR-12] draft version and according to our team guidelines.
    - Long forms are `(integer)` and `(boolean)` and Short forms are `(int)` and `(bool)`.
- Created a custom rule for disallowing deprecated PHPDoc tags according to [PSR-5][PSR-5] draft version.
    - It will be a warning.
- Created a new custom sniff that adds a comma to the last element of a multiline array.
    - If in the future you add more elements to the array you will change only one line not two.
- Created a new custom sniff that ensures that params in PHPDoc use short forms.
    - This rule is applied according to [PSR-12][PSR-12] draft version.
    - Long forms are `(integer)` and `(boolean)` and Short forms are `(int)` and `(bool)`.
- Created a new custom sniff that ensures that no `ElseIf` is used.
    - It will be muted in ruleset.
- Created a new custom sniff that ensures the number of methods in a Class.
    - Maximum methods before warning are 10.
    - Maximum methods before error are 20.
    - Developers can customize this per project.
    - Constructor method and magic methods are excluded.
- Created a new custom sniff to add an empty line after the `<?php` tag.
- Created a new custom sniff that ensures that all uses are in Alphabetical order.
    - It will be muted in ruleset.
- Created a new custom sniff for forbidden function which extends the `Generic.PHP.ForbiddenFunctions` rule.
    - It was created in order to override the forbidden functions of the `Generic` suite.
    - Functions forbidden:
        - `sizeof` use count instead. Size of is an alias in PHP
        - `delete` use unset. Who the hell uses delete?
        - `print` use echo.
        - `var_dump` is not allowed.
        - `die` is not allowed.
        - `exit` is not allowed.
        - `is_null` is not allowed.
        - `create_function` is not allowed.
        - `curl_init` use Guzzle instead.
        - `ldap_sort` is deprecated in PHP.
        - `password_hash` is deprecated in PHP.
        - `mcrypt_encrypt` is deprecated in PHP.
        - `mcrypt_create_iv` is deprecated in PHP.
- Created a [ruleset.xml](src/Webthink/ruleset.xml) that has the basic staff of rules.
    - Developers can override everything on the projects that the ruleset is included.

[PSR-5]: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md
[PSR-12]: https://github.com/php-fig/fig-standards/blob/master/proposed/extended-coding-style-guide.md
