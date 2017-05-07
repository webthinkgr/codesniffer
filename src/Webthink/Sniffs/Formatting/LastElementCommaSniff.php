<?php

namespace WebthinkSniffer\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Ensure that last element of multi-line array has a comma
 *
 * @see Squiz_Sniffs_Arrays_ArrayDeclarationSniff
 */
final class LastElementCommaSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_ARRAY,
            T_OPEN_SHORT_ARRAY,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Support long and short syntax.
        $parenthesis_opener = 'parenthesis_opener';
        $parenthesis_closer = 'parenthesis_closer';
        if ($tokens[$stackPtr]['code'] === T_OPEN_SHORT_ARRAY) {
            $parenthesis_opener = 'bracket_opener';
            $parenthesis_closer = 'bracket_closer';
        }

        // Sanity check: this can sometimes be NULL if the array was not correctly parsed.
        if ($tokens[$stackPtr][$parenthesis_closer] === null) {
            return;
        }

        $lastItem = $phpcsFile->findPrevious(
            Tokens::$emptyTokens,
            ($tokens[$stackPtr][$parenthesis_closer] - 1),
            $stackPtr,
            true
        );

        // Empty array.
        if ($lastItem === $tokens[$stackPtr][$parenthesis_opener]) {
            return;
        }

        // Inline array.
        if ($tokens[$tokens[$stackPtr][$parenthesis_opener]]['line'] === $tokens[$tokens[$stackPtr][$parenthesis_closer]]['line']) {
            return;
        }

        if (
            $tokens[$lastItem]['code'] !== T_COMMA
            && $tokens[($lastItem + 1)]['code'] !== T_CLOSE_PARENTHESIS
            && $tokens[($lastItem + 1)]['code'] !== T_CLOSE_SHORT_ARRAY
            && isset(Tokens::$heredocTokens[$tokens[$lastItem]['code']]) === false
        ) {
            $fix = $phpcsFile->addFixableError(
                'A comma should follow the last element of a multi-line array.',
                $lastItem,
                'CommaAfterLast'
            );

            if ($fix === true) {
                $phpcsFile->fixer->addContent($lastItem, ',');
            }
        }
    }
}
