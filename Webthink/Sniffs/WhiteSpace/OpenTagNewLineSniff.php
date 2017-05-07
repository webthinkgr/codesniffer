<?php

/**
 * Checks that there is exactly one newline after the PHP open tag.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
class Webthink_Sniffs_WhiteSpace_OpenTagNewLineSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_OPEN_TAG];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Only check the very first PHP open tag in a file, ignore any others.
        if ($stackPtr !== 0) {
            return (count($tokens) + 1);
        }

        $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);

        // If there is no furhter content in this file ignore it.
        if ($next === false) {
            return (count($tokens) + 1);
        }

        if ($tokens[$next]['line'] === 3) {
            return (count($tokens) + 1);
        }

        $fix = $phpcsFile->addFixableError(
            'The PHP open tag must be followed by exactly one blank line',
            $stackPtr,
            'BlankLine'
        );

        if ($fix === true) {
            $phpcsFile->fixer->beginChangeset();

            if ($tokens[$next]['line'] === 1) {
                $phpcsFile->fixer->addNewline($stackPtr);
                $phpcsFile->fixer->addNewline($stackPtr);
            } else {
                if ($tokens[$next]['line'] === 2) {
                    $phpcsFile->fixer->addNewline($stackPtr);
                } else {
                    for ($i = ($stackPtr + 1); $i < $next; $i++) {
                        if ($tokens[$i]['line'] > 2) {
                            $phpcsFile->fixer->replaceToken($i, '');
                        }
                    }
                }
            }

            $phpcsFile->fixer->endChangeset();
        }

        return (count($tokens) + 1);
    }
}
