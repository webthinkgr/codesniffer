<?php

/**
 * Avoid using `else` and `elseif` tokens.
 * This rule is an indication that your code needs refactoring.
 * As an indication and not a strict rule it adds a warning and not an error.
 *
 * The rule was inspired by `Object Calisthenics` which disapproves the use of `else` tokens.
 *
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_ControlStructures_NoElseSniff implements PHP_CodeSniffer_Sniff
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
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $phpcsFile->addWarning('"Else" or "ElseIf" should be avoided', $stackPtr);
    }
}
