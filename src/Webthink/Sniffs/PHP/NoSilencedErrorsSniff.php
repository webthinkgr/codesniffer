<?php

namespace WebthinkSniffer;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Throws an error or warning when any code prefixed with an asperand is encountered.
 * The rule was found on github and it was copied and altered in order to ignore the all
 * `trigger_error` functions and the `fopen` functions
 *
 * <code>
 *  if (@in_array($array, $needle))
 *  {
 *      doSomething();
 *  }
 * </code>
 *
 * @package Codesniffer
 * @author  George Mponos <gmponos@gmail.com>
 * @author  Alexander Obuhovich <aik.bold@gmail.com>
 * @see     https://github.com/aik099/CodingStandard
 */
class NoSilencedErrorsSniff extends Generic_Sniffs_PHP_NoSilencedErrorsSniff
{
    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    public $error = true;

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the stack passed in $tokens.
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $secondTokenData = $tokens[($stackPtr + 1)];
        $thirdTokenData = $tokens[($stackPtr + 2)];

        // This is a silenced "trigger_error" function call.
        if (
            $secondTokenData['code'] === T_STRING
            && $secondTokenData['content'] === 'trigger_error'
            && $thirdTokenData['code'] === T_OPEN_PARENTHESIS
            && isset($thirdTokenData['parenthesis_closer']) === true
        ) {
            return;
        }

        // allow silencing fopen functions.
        if (
            $secondTokenData['code'] === T_STRING
            && $secondTokenData['content'] === 'fopen'
            && $thirdTokenData['code'] === T_OPEN_PARENTHESIS
            && isset($thirdTokenData['parenthesis_closer']) === true
        ) {
            return;
        }

        parent::process($phpcsFile, $stackPtr);
    }
}
