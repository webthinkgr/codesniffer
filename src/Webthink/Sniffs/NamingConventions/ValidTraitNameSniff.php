<?php

namespace WebthinkSniffer\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Ensures trait names are correct.
 *
 * @author George Mponos <gmponos@gmail.com>
 */
class ValidTraitNameSniff implements Sniff
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
        $traitName = $tokens[$stackPtr + 2]['content'];
        if (substr($traitName, -5) !== 'Trait') {
            $phpcsFile->addError(
                'Traits must have a "Trait" suffix.',
                $stackPtr,
                'InvalidTraitName'
            );
        }
    }
}
