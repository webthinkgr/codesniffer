<?php

/**
 * Ensure that last element of multiline array has a comma
 * The rule was copied from `drupal/coder` and it was altered in order to include only the last comma rule
 *
 * @package Codesniffer
 * @author  George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_Array_LastElementCommaSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_ARRAY,
            T_OPEN_SHORT_ARRAY,
        ];
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
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($tokens[$stackPtr][$parenthesis_closer] - 1),
            $stackPtr,
            true
        );

        // Empty array.
        if ($lastItem === $tokens[$stackPtr][$parenthesis_opener]) {
            return;
        }

        // Inline array.
        $isInlineArray = $tokens[$tokens[$stackPtr][$parenthesis_opener]]['line'] === $tokens[$tokens[$stackPtr][$parenthesis_closer]]['line'];
        if ($isInlineArray === true) {
            // Check if this array contains
            return;
        }

        // Check if the last item in a multiline array has a "closing" comma.
        if (
            $tokens[$lastItem]['code'] !== T_COMMA
            && $tokens[($lastItem + 1)]['code'] !== T_CLOSE_PARENTHESIS
            && $tokens[($lastItem + 1)]['code'] !== T_CLOSE_SHORT_ARRAY
            && isset(PHP_CodeSniffer_Tokens::$heredocTokens[$tokens[$lastItem]['code']]) === false
        ) {
            $data = [$tokens[$lastItem]['content']];
            $fix = $phpcsFile->addFixableError(
                'A comma should follow the last multiline array item. Found: %s',
                $lastItem,
                'CommaLastItem',
                $data
            );

            if ($fix === true) {
                $phpcsFile->fixer->addContent($lastItem, ',');
            }
        }
    }
}
