<?php

/**
 * Use statements to import classes must not begin with "\".
 *
 * Copied from `drupal/coder`
 *
 * @author Klaus Purer <@klausi>
 * @author George Mponos <gmponos@gmail.com>
 * @see https://github.com/klausi/coder
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
                'When importing a class with "use", do not include a leading \\',
                $startPtr,
                'SeparatorStart'
            );

            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($startPtr, '');
            }
        }
    }
}
