<?php

/**
 * This sniff checks if a function has parameters with the same name.
 *
 * This is forbidden in PHP7.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @see http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.func-parameters
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

        $names = [];
        foreach ($parameters as $param) {
            $names[] = strtolower($param['name']);
        }

        if (count($names) != count(array_unique($names))) {
            $phpcsFile->addError('Parameters of a function must not have the same name', $stackPtr, 'Found');
        }
    }
}
