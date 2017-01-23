<?php

/**
 * Checks that there is exactly one space after the namespace keyword and the namespace.
 *
 * @package Codesniffer
 */
class Webthink_Sniffs_WhiteSpace_NamespaceSpaceSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_NAMESPACE];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the stack passed in $tokens.
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
