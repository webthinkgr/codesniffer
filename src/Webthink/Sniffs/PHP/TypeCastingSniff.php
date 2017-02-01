<?php

namespace WebthinkSniffer\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Asserts that type casts are in the short form:
 *
 * - bool instead of boolean
 * - int instead of integer
 *
 * Copied from cakephp-codesniffer
 *
 * @see    https://github.com/cakephp/cakephp-codesniffer/blob/master/CakePHP/Sniffs/PHP/TypeCastingSniff.php
 * @author George Mponos <gmponos@gmail.com>
 */
final class TypeCastingSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return array_merge(Tokens::$castTokens, [T_BOOLEAN_NOT]);
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Process !! casts
        if ($tokens[$stackPtr]['code'] == T_BOOLEAN_NOT) {
            $nextToken = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
            if (!$nextToken) {
                return;
            }

            if ($tokens[$nextToken]['code'] != T_BOOLEAN_NOT) {
                return;
            }

            $error = 'Usage of !! cast is not allowed. Please use (bool) to cast.';
            $phpcsFile->addError($error, $stackPtr, 'NotAllowed');
            return;
        }

        // Only allow short forms if both short and long forms are possible
        $matching = [
            '(boolean)' => '(bool)',
            '(integer)' => '(int)',
        ];
        $content = $tokens[$stackPtr]['content'];
        $key = strtolower($content);
        if (isset($matching[$key])) {
            $error = 'Please use ' . $matching[$key] . ' instead of ' . $content . '.';
            $phpcsFile->addError($error, $stackPtr, 'NotAllowed');
            return;
        }

        if ($content !== $key) {
            $error = 'Please use ' . $key . ' instead of ' . $content . '.';
            $phpcsFile->addError($error, $stackPtr, 'NotAllowed');
            return;
        }
    }
}
