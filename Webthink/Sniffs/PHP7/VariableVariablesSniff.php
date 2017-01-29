<?php

/**
 * The interpretation of variable variables has changed in PHP 7.0.
 * This sniff does not allow to have variable Variables if they do not have curly brackets.
 *
 * Copied most of this from `wimg/php-compatibility` package.
 * This sniff was altered in a way that I believe it's more readable.
 *
 * @see    http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.variable-handling
 * @see    https://github.com/wimg/PHPCompatibility
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_PHP7_VariableVariablesSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_VARIABLE,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Verify that the next token is a square open bracket. If not, bow out.
        $nextToken = $phpcsFile->findNext(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($stackPtr + 1),
            null,
            true,
            null,
            true
        );

        if ($nextToken === false) {
            return;
        }

        if ($tokens[$nextToken]['code'] !== T_OPEN_SQUARE_BRACKET || !isset($tokens[$nextToken]['bracket_closer'])) {
            return;
        }

        // The previous token has to be a $, -> or ::.
        $lookupTokens = [
            T_DOLLAR,
            T_OBJECT_OPERATOR,
            T_DOUBLE_COLON,
        ];

        if (!isset($tokens[($stackPtr - 1)]) || !in_array($tokens[($stackPtr - 1)]['code'], $lookupTokens, true)) {
            return;
        }

        // For static object calls, it only applies when this is a function call.
        if ($tokens[($stackPtr - 1)]['code'] === T_DOUBLE_COLON) {
            $hasBrackets = $tokens[$nextToken]['bracket_closer'];
            while (($hasBrackets = $phpcsFile->findNext(
                    PHP_CodeSniffer_Tokens::$emptyTokens,
                    ($hasBrackets + 1),
                    null,
                    true,
                    null,
                    true
                )) !== false) {
                if ($tokens[$hasBrackets]['code'] === T_OPEN_SQUARE_BRACKET) {
                    if (isset($tokens[$hasBrackets]['bracket_closer'])) {
                        $hasBrackets = $tokens[$hasBrackets]['bracket_closer'];
                        continue;
                    } else {
                        // Live coding.
                        return;
                    }
                } elseif ($tokens[$hasBrackets]['code'] === T_OPEN_PARENTHESIS) {
                    // Caught!
                    break;
                } else {
                    // Not a function call, so bow out.
                    return;
                }
            }

            // Now let's also prevent false positives when used with self and static which still work fine.
            $classToken = $phpcsFile->findPrevious(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($stackPtr - 2),
                null,
                true,
                null,
                true
            );

            if ($classToken !== false) {
                if ($tokens[$classToken]['code'] === T_STATIC || $tokens[$classToken]['code'] === T_SELF) {
                    return;
                }

                if ($tokens[$classToken]['code'] === T_STRING && $tokens[$classToken]['content'] === 'self') {
                    return;
                }
            }
        }

        $phpcsFile->addError(
            'Indirect access to variables, properties and methods will be evaluated strictly in left-to-right' .
            ' order since PHP 7.0. Use curly braces to remove ambiguity.',
            $stackPtr,
            'Found'
        );
    }
}
