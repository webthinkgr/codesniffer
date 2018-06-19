<?php

namespace WebthinkSniffer\Webthink\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * This sniff is copied from php-fig-rectified/psr2-r package.
 *
 * Although the package contains an autofix I was not feeling confident enough to include it
 * since the package is not required by many so it's not tested enough.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @see https://github.com/php-fig-rectified/psr2r-sniffer/blob/master/PSR2R/Sniffs/ControlStructures/ConditionalExpressionOrderSniff.php
 */
final class YodaSniff implements Sniff
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        return Tokens::$comparisonTokens;
    }

    /**
     * @inheritDoc
     */
    public function process(File $phpCsFile, $stackPointer)
    {
        $tokens = $phpCsFile->getTokens();
        $prevIndex = $phpCsFile->findPrevious(Tokens::$emptyTokens, $stackPointer - 1, null, true);
        if (in_array($tokens[$prevIndex]['code'], [
                T_CLOSE_SHORT_ARRAY,
                T_TRUE,
                T_FALSE,
                T_NULL,
                T_LNUMBER,
            ], true) === false) {
            return;
        }

        if ($tokens[$prevIndex]['code'] === T_CLOSE_SHORT_ARRAY) {
            $prevIndex = $tokens[$prevIndex]['bracket_opener'];
        }

        $prevIndex = $phpCsFile->findPrevious(Tokens::$emptyTokens, $prevIndex - 1, null, true);
        if ($prevIndex === false) {
            return;
        }

        if (in_array($tokens[$prevIndex]['code'], Tokens::$arithmeticTokens, true)) {
            return;
        }

        if ($tokens[$prevIndex]['code'] === T_STRING_CONCAT) {
            return;
        }

        $phpCsFile->addError(
            'Usage of Yoda conditions is not allowed. Switch the expression order.',
            $stackPointer,
            'YodaCondition'
        );
    }
}