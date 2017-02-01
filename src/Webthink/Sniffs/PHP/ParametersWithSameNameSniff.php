<?php

namespace WebthinkSniffer\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * This sniff checks if a function has parameters with the same name.
 * This is forbidden in PHP7.
 *
 * Copied from `wimg/PHPCompatibility`
 *
 * @see    http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.func-parameters
 * @see    https://github.com/wimg/PHPCompatibility
 * @author George Mponos <gmponos@gmail.com>
 */
final class ParametersWithSameNameSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_FUNCTION,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];
        // Skip function without body.
        if (isset($token['scope_opener']) === false) {
            return;
        }

        // Get all parameters from method signature.
        $parameters = $phpcsFile->getMethodParameters($stackPtr);
        if (empty($parameters) || !is_array($parameters)) {
            return;
        }

        $paramNames = [];
        foreach ($parameters as $param) {
            $paramNames[] = strtolower($param['name']);
        }

        if (count($paramNames) !== count(array_unique($paramNames))) {
            $phpcsFile->addError(
                'Functions must not have multiple parameters with the same name',
                $stackPtr,
                'Found'
            );
        }
    }
}
