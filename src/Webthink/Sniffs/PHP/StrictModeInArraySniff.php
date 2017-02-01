<?php

namespace WebthinkSniffer\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Flag calling in_array(), array_search() and array_keys() without true as the third parameter.
 * Copied and altered from WordPress Coding Standard.
 *
 * @see    https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards
 * @author George Mponos <gmponos@gmail.com>
 */
final class StrictModeInArraySniff implements Sniff
{
    /**
     * The array functions that the third parameters needs to be true
     * in order to be set in strict mode.
     *
     * @var array
     */
    public $functions = [
        'in_array',
        'array_search',
    ];

    public function register()
    {
        return [
            T_STRING,
        ];
    }

    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];

        if (!in_array($content, $this->functions, true)) {
            return;
        }

        $parameters = $this->getFunctionCallParameters($phpcsFile, $stackPtr);

        // We're only interested in the third parameter.
        if (!isset($parameters[3]) || ('true' !== $parameters[3]['raw'])) {
            $phpcsFile->addWarning(
                'Not using strict comparison for %s; supply true for third argument.',
                $stackPtr,
                'StrictComparison',
                [$content]
            );
            return;
        }
    }

    public function getFunctionCallParameters(File $phpcsFile, $stackPtr)
    {
        if (false === $this->doesFunctionHaveParameters($phpcsFile, $stackPtr)) {
            return [];
        }

        $tokens = $phpcsFile->getTokens();
        /*
         * Ok, we know we have a T_STRING, T_ARRAY or T_OPEN_SHORT_ARRAY with parameters
         * and valid open & close brackets/parenthesis.
         */
        // Mark the beginning and end tokens.
        if ('T_OPEN_SHORT_ARRAY' === $tokens[$stackPtr]['type']) {
            $opener = $stackPtr;
            $closer = $tokens[$stackPtr]['bracket_closer'];
            $nestedParenthesisCount = 0;
        } else {
            $opener = $phpcsFile->findNext(
                Tokens::$emptyTokens,
                ($stackPtr + 1),
                null,
                true,
                null,
                true
            );
            $closer = $tokens[$opener]['parenthesis_closer'];
            $nestedParenthesisCount = 1;
        }
        // Which nesting level is the one we are interested in ?
        if (isset($tokens[$opener]['nested_parenthesis'])) {
            $nestedParenthesisCount += count($tokens[$opener]['nested_parenthesis']);
        }

        $parameters = [];
        $nextComma = $opener;
        $paramStart = ($opener + 1);
        $cnt = 1;
        while (
        $nextComma =
            $phpcsFile->findNext(
                [
                    T_COMMA,
                    $tokens[$closer]['code'],
                    T_OPEN_SHORT_ARRAY,
                ],
                ($nextComma + 1),
                ($closer + 1)
            )
        ) {
            // Ignore anything within short array definition brackets.
            if (
                'T_OPEN_SHORT_ARRAY' === $tokens[$nextComma]['type']
                && (isset($tokens[$nextComma]['bracket_opener']) && $tokens[$nextComma]['bracket_opener'] === $nextComma)
                && isset($tokens[$nextComma]['bracket_closer'])
            ) {
                // Skip forward to the end of the short array definition.
                $nextComma = $tokens[$nextComma]['bracket_closer'];
                continue;
            }

            // Ignore comma's at a lower nesting level.
            if (T_COMMA === $tokens[$nextComma]['code']
                && isset($tokens[$nextComma]['nested_parenthesis'])
                && count($tokens[$nextComma]['nested_parenthesis']) !== $nestedParenthesisCount
            ) {
                continue;
            }

            // Ignore closing parenthesis/bracket if not 'ours'.
            if ($tokens[$nextComma]['type'] === $tokens[$closer]['type'] && $nextComma !== $closer) {
                continue;
            }

            // Ok, we've reached the end of the parameter.
            $parameters[$cnt]['start'] = $paramStart;
            $parameters[$cnt]['end'] = ($nextComma - 1);
            $parameters[$cnt]['raw'] = trim($phpcsFile->getTokensAsString($paramStart, ($nextComma - $paramStart)));

            /*
             * Check if there are more tokens before the closing parenthesis.
             * Prevents code like the following from setting a third parameter:
             * functionCall( $param1, $param2, );
             */
            $hasNextParam = $phpcsFile->findNext(
                Tokens::$emptyTokens,
                ($nextComma + 1),
                $closer,
                true,
                null,
                true
            );

            if (false === $hasNextParam) {
                break;
            }
            // Prepare for the next parameter.
            $paramStart = ($nextComma + 1);
            $cnt++;
        }

        return $parameters;
    }

    public function doesFunctionHaveParameters(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        // Check for the existence of the token.
        if (!isset($tokens[$stackPtr])) {
            return false;
        }

        // Is this one of the tokens this function handles ?
        if (!in_array($tokens[$stackPtr]['code'], [T_STRING, T_ARRAY, T_OPEN_SHORT_ARRAY], true)) {
            return false;
        }

        $next_non_empty = $phpcsFile->findNext(
            Tokens::$emptyTokens,
            ($stackPtr + 1),
            null,
            true,
            null,
            true
        );

        // Deal with short array syntax.
        if ('T_OPEN_SHORT_ARRAY' === $tokens[$stackPtr]['type']) {
            if (!isset($tokens[$stackPtr]['bracket_closer'])) {
                return false;
            }

            if ($next_non_empty === $tokens[$stackPtr]['bracket_closer']) {
                // No parameters.
                return false;
            }

            return true;
        }

        // Deal with function calls & long arrays.
        // Next non-empty token should be the open parenthesis.
        if (!$next_non_empty && T_OPEN_PARENTHESIS !== $tokens[$next_non_empty]['code']) {
            return false;
        }

        if (!isset($tokens[$next_non_empty]['parenthesis_closer'])) {
            return false;
        }

        $close_parenthesis = $tokens[$next_non_empty]['parenthesis_closer'];
        $next_next_non_empty = $phpcsFile->findNext(
            Tokens::$emptyTokens,
            ($next_non_empty + 1),
            ($close_parenthesis + 1),
            true
        );

        if ($next_next_non_empty === $close_parenthesis) {
            // No parameters.
            return false;
        }

        return true;
    }
}
