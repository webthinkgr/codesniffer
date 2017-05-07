<?php

/**
 * Ensures trait names are correct.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
class Webthink_Sniffs_NamingConventions_ValidTraitNameSniff implements PHP_CodeSniffer_Sniff
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
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
