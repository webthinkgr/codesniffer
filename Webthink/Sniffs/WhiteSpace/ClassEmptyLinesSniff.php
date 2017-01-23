<?php

/**
 * Checks that there are not more than 2 empty lines following each other.
 *
 * This rule was mostly copied from Squiz_Sniffs_WhiteSpace_SuperfluousWhitespaceSniff and altered
 * in order not to allow multiple empty lines inside classes.
 *
 * @package Codesniffer
 * @author  George Mponos <gmponos@gmail.com>
 * @see     Squiz_Sniffs_WhiteSpace_SuperfluousWhitespaceSniff
 */
class Webthink_Sniffs_WhiteSpace_ClassEmptyLinesSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = [
        'PHP',
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_WHITESPACE];
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

        if (($phpcsFile->hasCondition($stackPtr, T_CLASS) === true || $phpcsFile->hasCondition($stackPtr, T_CLOSURE) === true)
            && $tokens[($stackPtr - 1)]['line'] < $tokens[$stackPtr]['line']
            && $tokens[($stackPtr - 2)]['line'] === $tokens[($stackPtr - 1)]['line']
        ) {
            // This is an empty line and the line before this one is not
            // empty, so this could be the start of a multiple empty
            // line block.
            $next = $phpcsFile->findNext(T_WHITESPACE, $stackPtr, null, true);
            $lines = ($tokens[$next]['line'] - $tokens[$stackPtr]['line']);
            if ($lines > 1) {
                $error = 'Classes must not contain multiple empty lines in a row; found %s empty lines';
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'EmptyLines', [$lines]);
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();
                    $i = $stackPtr;
                    while ($tokens[$i]['line'] !== $tokens[$next]['line']) {
                        $phpcsFile->fixer->replaceToken($i, '');
                        $i++;
                    }

                    $phpcsFile->fixer->addNewlineBefore($i);
                    $phpcsFile->fixer->endChangeset();
                }
            }
        }
    }
}
