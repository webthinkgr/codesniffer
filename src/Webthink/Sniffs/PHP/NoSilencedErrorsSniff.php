<?php

namespace WebthinkSniffer\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\NoSilencedErrorsSniff as GenericNoSilencedErrorsSniff;

/**
 * Throws an error or warning when any code prefixed with an asperand is encountered.
 * The rule was found on github and it was copied and altered in order to ignore the
 * `trigger_error` functions and the `fopen` functions.
 *
 * @package Codesniffer
 * @author  George Mponos <gmponos@gmail.com>
 * @author  Alexander Obuhovich <aik.bold@gmail.com>
 * @see     https://github.com/aik099/CodingStandard
 */
final class NoSilencedErrorsSniff extends GenericNoSilencedErrorsSniff
{
    /**
     * @var array
     */
    public $allowedFunctions = [
        'trigger_error',
        'fopen',
    ];

    /**
     * @inheritDoc
     */
    public $error = true;

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $secondTokenData = $tokens[($stackPtr + 1)];
        $thirdTokenData = $tokens[($stackPtr + 2)];

        if (
            $secondTokenData['code'] === T_STRING
            && in_array($secondTokenData['content'], $this->allowedFunctions)
            && $thirdTokenData['code'] === T_OPEN_PARENTHESIS
            && isset($thirdTokenData['parenthesis_closer'])
        ) {
            return;
        }

        parent::process($phpcsFile, $stackPtr);
    }
}
