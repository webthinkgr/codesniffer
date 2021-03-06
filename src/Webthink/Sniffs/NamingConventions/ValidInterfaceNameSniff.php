<?php

namespace WebthinkSniffer\Webthink\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Ensures interface names are correct.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class ValidInterfaceNameSniff implements Sniff
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
        $name = $tokens[$stackPtr + 2]['content'];
        if (substr($name, -9) !== 'Interface') {
            $phpcsFile->addError(
                'Interfaces must have an "Interface" suffix.',
                $stackPtr,
                'InvalidInterfaceName'
            );
        }
    }
}
