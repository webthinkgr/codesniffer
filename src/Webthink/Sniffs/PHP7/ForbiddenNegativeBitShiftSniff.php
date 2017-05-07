<?php

namespace WebthinkSniffer\Sniffs\PHP7;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Bitwise shifts by negative number will throw an ArithmeticError in PHP 7.0.
 *
 * Copied most of this from `wimg/php-compatibility` package.
 *
 * @see https://github.com/wimg/PHPCompatibility
 * @author Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
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
