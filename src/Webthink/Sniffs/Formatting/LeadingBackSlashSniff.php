<?php

namespace WebthinkSniffer\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Use statements to import classes must not begin with "\".
 *
 * Copied from `drupal/coder`
 *
 * @author Klaus Purer <klaus.purer@gmail.com>
 * @see https://github.com/klausi/coder
 */
final class LeadingBackSlashSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_USE,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Only check use statements in the global scope.
        if (!empty($tokens[$stackPtr]['conditions'])) {
            return;
        }

        $startPtr = $phpcsFile->findNext(
            Tokens::$emptyTokens,
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
