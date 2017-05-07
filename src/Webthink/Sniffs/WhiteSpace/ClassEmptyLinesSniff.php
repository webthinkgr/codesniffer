<?php

namespace WebthinkSniffer\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks that there are not more than 2 empty lines following each other.
 *
 * This rule was mostly copied from Squiz.WhiteSpace.SuperfluousWhitespaceSniff and altered
 * in order not to allow multiple empty lines inside classes.
 *
 * @author  George Mponos <gmponos@gmail.com>
 * @see \PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\SuperfluousWhitespaceSniff
 * @license MIT
 */
final class ClassEmptyLinesSniff implements Sniff
{
    /**
     * The number of maximum allowed lines inside a class
     *
     * @var int
     */
    public $allowedLines = 1;

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = [
        'PHP',
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_WHITESPACE];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (
            ($phpcsFile->hasCondition($stackPtr, T_CLASS) || $phpcsFile->hasCondition($stackPtr, T_CLOSURE))
            && $tokens[($stackPtr - 1)]['line'] < $tokens[$stackPtr]['line']
            && $tokens[($stackPtr - 2)]['line'] === $tokens[($stackPtr - 1)]['line']
        ) {
            // This is an empty line and the line before this one is not empty, so this could be
            // the start of a multiple empty line block.
            $next = $phpcsFile->findNext(T_WHITESPACE, $stackPtr, null, true);
            $lines = ($tokens[$next]['line'] - $tokens[$stackPtr]['line']);
            if ($lines <= $this->allowedLines) {
                return;
            }

            $fix = $phpcsFile->addFixableError(
                'Classes must not contain multiple empty lines in a row; found %s empty lines',
                $stackPtr,
                'EmptyLines',
                [$lines]
            );

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $i = $stackPtr;
                while ($tokens[$i]['line'] !== $tokens[$next]['line']) {
                    $phpcsFile->fixer->replaceToken($i, '');
                    $i++;
                }

                $phpcsFile->fixer->addNewlineBefore($i);
                $phpcsFile->fixer->endChangeset();
            }
        }
    }
}
