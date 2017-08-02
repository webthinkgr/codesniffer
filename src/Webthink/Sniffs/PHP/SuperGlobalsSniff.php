<?php

namespace WebthinkSniffer\Webthink\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * The direct access in super globals is forbidden.
 *
 * If you are working on creating a legacy code this sniff can't be applied
 * but if you are using a framework most probably it already provides you functions
 * and classes to access the SuperGlobal variables of PHP in a safe way.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class SuperGlobalsSniff implements Sniff
{
    /**
     * The list with the SuperGlobals
     *
     * @var array
     */
    public $superGlobals = [
        '$GLOBALS',
        '$_GET',
        '$_POST',
        '$_SESSION',
        '$_REQUEST',
        '$_ENV',
        '$_FILES',
        '$_COOKIE',
        '$_SERVER',
    ];

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
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $var = $tokens[$stackPtr]['content'];

        if (in_array($var, $this->superGlobals, true)) {
            $phpcsFile->addError('Direct use of %s SuperGlobal is forbidden.', $stackPtr, 'SuperGlobalUsage', [$var]);
        }
    }
}
