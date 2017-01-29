<?php

/**
 * Ensures trait names are correct depending on the folder of the file.
 *
 * @author George Mponos <gmponos@gmail.com>
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
            $phpcsFile->addError('Interfaces must have an "Interface" suffix.', $stackPtr, 'InvalidInterfaceName');
        }
    }
}
