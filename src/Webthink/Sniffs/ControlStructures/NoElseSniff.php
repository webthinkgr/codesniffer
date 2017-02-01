<?php

namespace WebthinkSniffer;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Avoid using `else` and `elseif` tokens.
 * This rule is an indication that your code needs refactoring.
 * As an indication and not a strict rule it adds a warning and not an error.
 *
 * The rule was inspired by `Object Calisthenics` which disapproves the use of `else` tokens.
 *
 * @author George Mponos <gmponos@gmail.com>
 */
final class NoElseSniff implements Sniff
{
    /**
     * Supported list of tokenizers supported by this sniff.
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
        return [
            T_ELSE,
            T_ELSEIF,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addWarning('"Else" or "ElseIf" should be avoided', $stackPtr);
    }
}
