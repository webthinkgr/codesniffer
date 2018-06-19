<?php

namespace WebthinkSniffer\Webthink\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Name of abstract classes must be suffixed with "Abstract"
 *
 * @author George Mponos <gmponos@gmail.com>
 */
class ValidAbstractNameSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_ABSTRACT];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $name = $phpcsFile->findNext(T_STRING, $stackPtr);
        $function = $phpcsFile->findNext(T_FUNCTION, $stackPtr);

        if ($name === false) {
            return;
        }

        // making sure we're not dealing with an abstract function
        if (($function === null || $name < $function) && substr($tokens[$name]['content'], 0, 8) !== 'Abstract') {
            $phpcsFile->addError(
                'Abstract class name is not prefixed with "Abstract"',
                $stackPtr,
                'InvalidAbstractName'
            );
        }
    }
}