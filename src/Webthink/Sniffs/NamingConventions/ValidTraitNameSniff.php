<?php

namespace WebthinkSniffer\Webthink\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Ensures trait names are correct.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class ValidTraitNameSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_TRAIT,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $name = $tokens[$stackPtr + 2]['content'];
        if (substr($name, -5) !== 'Trait') {
            $phpcsFile->addError(
                'Traits must have a "Trait" suffix.',
                $stackPtr,
                'InvalidTraitName'
            );
        }
    }
}
