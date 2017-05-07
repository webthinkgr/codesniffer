<?php

/**
 * Ensures interface names are correct.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
class Webthink_Sniffs_NamingConventions_ValidInterfaceNameSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_INTERFACE,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $traitName = $tokens[$stackPtr + 2]['content'];
        if (substr($traitName, -9) !== 'Interface') {
            $phpcsFile->addError(
                'Interfaces must have an "Interface" suffix.',
                $stackPtr,
                'InvalidInterfaceName'
            );
        }
    }
}
