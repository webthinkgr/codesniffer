<?php

namespace WebthinkSniffer\Sniffs\PHP7;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Discourages the use of assigning the return value of new by reference
 *
 * Inspired most from `wimg/php-compatibility` package.
 *
 * @see https://github.com/wimg/PHPCompatibility
 * @author Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
final class DeprecatedNewReferenceSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_NEW,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if ($tokens[$stackPtr - 1]['type'] == 'T_BITWISE_AND' || $tokens[$stackPtr - 2]['type'] == 'T_BITWISE_AND') {
            $phpcsFile->addError(
                'Assigning the return value of new by reference is forbidden in PHP 7.x',
                $stackPtr,
                'Forbidden'
            );
        }
    }
}
