<?php

/**
 * This sniff checks if a function has parameters with the same name.
 *
 * This is forbidden in PHP7.
 *
 * Copied from `wimg/PHPCompatibility`
 *
 * @see http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.func-parameters
 * @see https://github.com/wimg/PHPCompatibility
 */
class Webthink_Sniffs_PHP_ParametersWithSameNameSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_FUNCTION];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];
        // Skip function without body.
        if (isset($token['scope_opener']) === false) {
            return;
        }

        // Get all parameters from method signature.
        $parameters = $phpcsFile->getMethodParameters($stackPtr);
        if (empty($parameters) || is_array($parameters) === false) {
            return;
        }

        $paramNames = [];
        foreach ($parameters as $param) {
            $paramNames[] = strtolower($param['name']);
        }

        if (count($paramNames) != count(array_unique($paramNames))) {
            $phpcsFile->addError(
                'Functions must not have multiple parameters with the same name',
                $stackPtr,
                'Found'
            );
        }
    }
}
