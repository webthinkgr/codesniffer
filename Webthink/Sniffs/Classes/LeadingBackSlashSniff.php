<?php

/**
 * Use statements to import classes must not begin with "\".
 *
 * @author Mark Scherer
 * @see http://php.net/manual/en/aliases.php
 * @see https://github.com/php-fig-rectified/psr2r-sniffer
 * @license MIT
 */
class Webthink_Sniffs_Classes_LeadingBackSlashSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_USE];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Only check use statements in the global scope.
        if (!empty($tokens[$stackPtr]['conditions'])) {
            return;
        }

        $startPtr = $phpcsFile->findNext(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($stackPtr + 1),
            null,
            true
        );

        if ($startPtr !== null && $tokens[$startPtr]['code'] === T_NS_SEPARATOR) {
            $fix = $phpcsFile->addFixableError(
                'Namespaces in use statements should not start with a namespace separator',
                $startPtr,
                'NamespaceStart'
            );

            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($startPtr, '');
            }
        }
    }
}
