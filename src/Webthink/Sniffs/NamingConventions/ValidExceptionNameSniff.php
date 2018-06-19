<?php

namespace WebthinkSniffer\Webthink\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * @author George Mponos <gmponos@gmail.com>
 */
class ValidExceptionNameSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_EXTENDS];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Suffix exceptions with Exception
        $extend = $phpcsFile->findNext(T_STRING, $stackPtr);
        if ($extend && substr($tokens[$extend]['content'], -9) === 'Exception') {
            $class = $phpcsFile->findPrevious(T_CLASS, $stackPtr);
            $name = $phpcsFile->findNext(T_STRING, $class);
            if ($name && substr($tokens[$name]['content'], -9) !== 'Exception') {
                $phpcsFile->addError(
                    'Exception name is not suffixed with "Exception"',
                    $stackPtr,
                    'InvalidExceptionName'
                );
            }
        }
    }
}