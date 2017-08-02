<?php

namespace WebthinkSniffer\Webthink\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Ensures that class names are correct.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class ValidClassNameSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_CLASS,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $className = $tokens[$stackPtr + 2]['content'];
        if (substr($className, -5) === 'Trait') {
            $phpcsFile->addError(
                'Classes must not have a "Trait" suffix.',
                $stackPtr,
                'InvalidClassName'
            );
        }

        if (substr($className, -9) === 'Interface') {
            $phpcsFile->addError(
                'Classes must not have an "Interface" suffix.',
                $stackPtr,
                'InvalidClassName'
            );
        }
    }
}
