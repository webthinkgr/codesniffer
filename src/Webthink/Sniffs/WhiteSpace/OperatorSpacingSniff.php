<?php

namespace WebthinkSniffer\Webthink\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\OperatorSpacingSniff as SquizOperatorSpacingSniff;
use PHP_CodeSniffer\Util\Tokens;

class OperatorSpacingSniff extends SquizOperatorSpacingSniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = [
        'PHP',
    ];

    /**
     * Allow newlines instead of spaces.
     *
     * @var bool
     */
    public $ignoreNewlines = true;

    /**
     * @inheritdoc
     */
    public function register()
    {
        $operators = Tokens::$operators;
        $assignment = [
            T_DOUBLE_ARROW => T_DOUBLE_ARROW,
        ];
        $inlineIf = [
            T_INLINE_THEN,
            T_INLINE_ELSE,
        ];

        return array_unique(
            array_merge($operators, $inlineIf, $assignment)
        );
    }
}