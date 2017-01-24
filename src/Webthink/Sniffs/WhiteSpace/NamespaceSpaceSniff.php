<?php

namespace WebthinkSniffer;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks that there is exactly one space after the namespace keyword and the namespace.
 *
 * @package Codesniffer
 */
final class NamespaceSpaceSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_NAMESPACE];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if ($tokens[($stackPtr + 1)]['content'] !== ' ') {
            $fix = $phpcsFile->addFixableError(
                'There must be exactly one space after the namepace keyword',
                ($stackPtr + 1),
                'OneSpace'
            );

            if ($fix === true) {
                $phpcsFile->fixer->replaceToken(($stackPtr + 1), ' ');
            }
        }
    }
}
