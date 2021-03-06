<?php

namespace WebthinkSniffer\Webthink\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks the declaration of the class is correct.
 *
 * The reason that this was copied is because at that time the original had a bug
 * https://github.com/aik099/CodingStandard/pull/100
 *
 * @author Peter Philipp <peter.philipp@cando-image.com>
 * @author Alexander Obuhovich <aik.bold@gmail.com>
 * @license https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @see https://github.com/aik099/CodingStandard
 */
final class ClassCreateInstanceSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_NEW];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $scopeEnd = null;
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['nested_parenthesis']) === true) {
            // New in PHP 5.4: allow to instantiate class and immediately call method on it.
            $scopeEnd = current($tokens[$stackPtr]['nested_parenthesis']);
        }

        $nextParenthesis = $phpcsFile->findNext(
            T_OPEN_PARENTHESIS,
            ($stackPtr + 1),
            $scopeEnd,
            false,
            null,
            true
        );

        if ($nextParenthesis === false || $tokens[$nextParenthesis]['line'] !== $tokens[$stackPtr]['line']) {
            $fix = $phpcsFile->addFixableError('Calling class constructors must always include parentheses', $stackPtr, 'missingParentheses');
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $classNameEnd = $phpcsFile->findNext([
                    T_WHITESPACE,
                    T_NS_SEPARATOR,
                    T_STRING,
                    T_SELF,
                    T_STATIC,
                ],
                    ($stackPtr + 1),
                    null,
                    true,
                    null,
                    true
                );

                $phpcsFile->fixer->addContentBefore($classNameEnd, '()');
                $phpcsFile->fixer->endChangeset();
            }
        } else {
            if ($tokens[($nextParenthesis - 1)]['code'] === T_WHITESPACE) {
                $fix = $phpcsFile->addFixableError(
                    'Between the class name and the opening parenthesis spaces are not welcome',
                    ($nextParenthesis - 1),
                    'spacesNotAllowed'
                );
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();
                    $phpcsFile->fixer->replaceToken(($nextParenthesis - 1), '');
                    $phpcsFile->fixer->endChangeset();
                }
            }
        }
    }
}
