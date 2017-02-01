<?php

namespace WebthinkSniffer\Sniffs\PHP7;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Discourages the use of assigning the return value of new by reference
 *
 * Copied most of this from `wimg/php-compatibility` package.
 *
 * @see    https://github.com/wimg/PHPCompatibility
 * @see    http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.new-by-ref
 * @author George Mponos <gmponos@gmail.com>
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
