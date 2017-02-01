<?php

namespace WebthinkSniffer\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Ensures interface names are correct.
 *
 * @author George Mponos <gmponos@gmail.com>
 */
class ValidInterfaceNameSniff implements Sniff
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
    public function process(File $phpcsFile, $stackPtr)
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
