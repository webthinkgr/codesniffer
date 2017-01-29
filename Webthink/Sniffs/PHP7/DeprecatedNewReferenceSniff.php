<?php

/**
 * Discourages the use of assigning the return value of new by reference
 *
 * Copied most of this from `wimg/php-compatibility` package.
 *
 * @see    https://github.com/wimg/PHPCompatibility
 * @see    http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.new-by-ref
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_PHP7_DeprecatedNewReferenceSniff implements PHP_CodeSniffer_Sniff
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
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
