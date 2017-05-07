<?php

namespace WebthinkSniffer\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Use statements to import classes must not begin with "\".
 *
 * @author Mark Scherer
 * @see http://php.net/manual/en/aliases.php
 * @see https://github.com/php-fig-rectified/psr2r-sniffer
 * @license MIT
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
