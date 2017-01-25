# Sniffs that are included

Below I will try to mention in the best way that I can the rules that I have included and created and why I chose these
rules.

## PSR
I generally try to follow every PSR. Some of the PSRs are currently in draft state but I do not believe they will
change much in the near future. Also you might see that some of the rules that are included below are enforcing some
guidelines that the PSRs are not.

- `PSR-2` all the rules of [PSR-2][PSR-2] are included.
- `PSR-5`
    - I have a custom sniff which outputs a warning in cases a deprecated tag is used in comments.
      See the [#comments](SNIFFS.md#comments) section in this file.
    - Note that [PSR-5][PSR-5] is draft state.
- `PSR-12`
    - I have applied the rule about 1 space between concatenation.
    - I have created a custom rule copied from [CodingStandard](https://github.com/aik099/CodingStandard) which enforces
     the instantiation of an object to have parentheses.
    - Note that [PSR-12][PSR-12] is draft state.

## Custom rules.
The following rules are the ones customized inside this package.

- `Webthink.Array.LastElementComma` the last element of an multiline array must always have a comma.
- `Webthink.Classes.ClassCreateInstance` when instantiating a new object it must always have parentheses. This rule 
was based on [PSR-12][PSR-12].
- `Webthink.Commenting.DeprecatedTagsSniff` according to [PSR-5][PSR-5] there are some tags for PHPDoc that are deprecated.
In cases when a tag like this is exists then a warning is displayed.
- `Webthink.Metrics.MethodPerClassLimit` this is a custom rule that checks if the class has more than 10 methods
 for a warning and 20 methods for errors. I personally believe that tests should be **excluded** from this rule. This rule
 was inspired by Object Calisthenics.

## Whitespace and style
- `Generic.Arrays.DisallowLongArraySyntax` 
- `Generic.Formatting.NoSpaceAfterCast` there is no specific reason why I chose this rule. I needed either one way
or another and I chose this way.
- `Squiz.Arrays.ArrayBracketSpacing`
- `Squiz.Classes.ClassFileName`
- `Squiz.Classes.LowercaseClassKeywords` class keyword must be in lowercase.
- `Squiz.PHP.LowercasePHPFunctions`
- `Squiz.Strings.ConcatenationSpacing` this rule enforces 1 space for concatenation based on [PSR-12][PSR-12].
- `Squiz.WhiteSpace.SemicolonSpacing`
- `Squiz.WhiteSpace.SuperfluousWhitespace` PSR-2 mutes this sniffs. We revert this change and we enforce this rule.
- `Zend.NamingConventions.ValidVariableName` this rule is used only to ensure that all variables are CamelCase format.
  So the following rules are **excluded**:
    - `Zend.NamingConventions.ValidVariableName.ContainsNumbers`
    - `Zend.NamingConventions.ValidVariableName.StringVarContainsNumbers`
    - `Zend.NamingConventions.ValidVariableName.MemberVarContainsNumbers`
    - `Zend.NamingConventions.ValidVariableName.PrivateNoUnderscore`

## PHP
- `Generic.NamingConventions.ConstructorName` check that we do not use in our code the PHP4 constructors.
- `Zend.Files.ClosingTag`

## Code review/Quality code
- `Generic.CodeAnalysis.EmptyStatement` - You are not allowed to have empty statements that does nothing. Like an `if` without a body.  
- `Generic.CodeAnalysis.UnnecessaryFinalModifier`
- `Generic.CodeAnalysis.UnusedFunctionParameter` this rules should be relaxed for tests.
- `Generic.CodeAnalysis.UselessOverridingMethod`
- `Generic.CodeAnalysis.ForLoopShouldBeWhileLoop`
- `Generic.CodeAnalysis.ForLoopWithTestFunctionCall`
- `Generic.CodeAnalysis.JumbledIncrementer`
- `Generic.CodeAnalysis.UnconditionalIfStatement`
- `Generic.Metrics.CyclomaticComplexity`
- `Generic.Metrics.NestingLevel` this rule was inspired from object calisthenics but I am being a little bit more 
tolerant with this and I allow a nesting level up to 4. Nesting level of 3 is a warning.
- `Generic.Strings.UnnecessaryStringConcat`
- `Squiz.Classes.SelfMemberReference` 
- `Squiz.Operators.ValidLogicalOperators`
- `Squiz.PHP.Eval` according to official PHP website:

    > The eval() language construct is very dangerous because it allows execution of arbitrary PHP code. Its use 
    > thus is discouraged. If you have carefully verified that there is no other option than to use this construct,
    > pay special attention not to pass any user provided data into it without properly validating it beforehand.

- `Squiz.PHP.NonExecutableCode`
- `Squiz.Scope.StaticThisUsage` check for `$this` usage in a static function.

## Comments
- `Squiz.Commenting.DocCommentAlignment`
- `PEAR.Commenting.InlineComment`
- `PEAR.Commenting.ClassComment` All classes must have a PHPDoc. Tests are **excluded**. It is not required the classes 
   to have tags. That's why following rules are **excluded**:
    - `PEAR.Commenting.ClassComment.MissingPackageTag`
    - `PEAR.Commenting.ClassComment.MissingAuthorTag`
    - `PEAR.Commenting.ClassComment.MissingCategoryTag`
    - `PEAR.Commenting.ClassComment.MissingLinkTag`
    - `PEAR.Commenting.ClassComment.MissingLicenseTag`

- `PEAR.Commenting.FunctionComment` All classes must have a PHPDoc. Tests are **excluded**. I have removed the following rules:  
    - `PEAR.Commenting.FunctionComment.SpacingAfterParamType`
    - `PEAR.Commenting.FunctionComment.SpacingAfterParamName`
    - `PEAR.Commenting.FunctionComment.MissingParamComment`

[PSR-2]: http://www.php-fig.org/psr/psr-2/
[PSR-5]: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md
[PSR-12]: https://github.com/php-fig/fig-standards/blob/master/proposed/extended-coding-style-guide.md