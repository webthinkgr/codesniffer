<?php

/**
 * Checks that a call was not made to an object directly from an array.
 *
 * @author George Bonos <gbonos@xm.com>
 */
class Webthink_Sniffs_PHP_ObjectCallsSniff implements PHP_CodeSniffer_Sniff
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
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
