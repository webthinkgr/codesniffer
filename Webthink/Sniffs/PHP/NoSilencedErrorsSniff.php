<?php

if (class_exists('Generic_Sniffs_PHP_NoSilencedErrorsSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class Generic_Sniffs_PHP_NoSilencedErrorsSniff not found');
}

/**
 * Throws an error or warning when any code prefixed with an asperand is encountered.
 *
 * The rule was found on github and it was copied and altered in order to ignore the
 * `trigger_error` functions and the `fopen` functions.
 *
 * @author Alexander Obuhovich <aik.bold@gmail.com>
 * @see https://github.com/aik099/CodingStandard
 */
class Webthink_Sniffs_PHP_NoSilencedErrorsSniff extends Generic_Sniffs_PHP_NoSilencedErrorsSniff
{
    /**
     * @var array
     */
    public $allowedFunctions = [
        'trigger_error',
        'fopen',
    ];

    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    public $error = true;

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
