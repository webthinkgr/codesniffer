<?php

namespace WebthinkSniffer\Sniffs\PHP7;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 */
final class ForbiddenNegativeBitShiftSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_SR,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $next = $phpcsFile->findNext(T_LNUMBER, $stackPtr + 1, null, false, null, true);
        if ($next === false || ($stackPtr + 1) === $next) {
            return;
        }

        $minusSign = $phpcsFile->findNext(T_MINUS, $stackPtr + 1, $next, false, null, true);
        if ($minusSign === false) {
            return;
        }

        $phpcsFile->addError('Bitwise shifts by negative number are forbidden in PHP 7.0', $minusSign, 'Found');
    }
}
