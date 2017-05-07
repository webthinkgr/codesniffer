<?php

/**
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
class Webthink_Sniffs_PHP7_ForbiddenNegativeBitShiftSniff implements PHP_CodeSniffer_Sniff
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
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
