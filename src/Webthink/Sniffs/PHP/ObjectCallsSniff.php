<?php

namespace WebthinkSniffer\Webthink\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks that a call was not made to an object directly from an array.
 *
 * The following is disallowed:
 *
 * `$myArray['value']->test();`
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class ObjectCallsSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_CLOSE_SQUARE_BRACKET,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $next = $stackPtr;
        do {
            $next++;
        } while ($tokens[$next]['code'] === T_WHITESPACE);

        if ($tokens[$next]['code'] === T_OBJECT_OPERATOR) {
            $phpcsFile->addError(
                'Do not call object function/properties directly from arrays',
                $stackPtr,
                'ArrayCall'
            );
        }
    }
}
