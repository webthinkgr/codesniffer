<?php

/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * Copied most of this from `wimg/php-compatibility` package.
 *
 * @see    https://github.com/wimg/PHPCompatibility
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_PHP7_ForbiddenNegativeBitShiftSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_SR];
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
        $nextNumber = $phpcsFile->findNext(T_LNUMBER, $stackPtr + 1, null, false, null, true);
        if ($nextNumber === false || ($stackPtr + 1) === $nextNumber) {
            return;
        }

        $hasMinusSign = $phpcsFile->findNext(T_MINUS, $stackPtr + 1, $nextNumber, false, null, true);
        if ($hasMinusSign === false) {
            return;
        }

        $phpcsFile->addError(
            'Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0',
            $hasMinusSign,
            'Found'
        );
    }
}
