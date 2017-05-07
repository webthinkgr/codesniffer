<?php

namespace WebthinkSniffer\Sniffs\PHP7;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Disallows the use of removed global variables.
 *
 * @see http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.http-raw-post-data
 * @author George Mponos <gmponos@gmail.com>
 */
final class RemovedGlobalVariablesSniff implements Sniff
{
    /**
     * A list of removed global variables with their alternative, if any.
     *
     * @var array(string|null)
     */
    protected $removedGlobalVar = [
        'HTTP_RAW_POST_DATA',
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_VARIABLE,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $varName = substr($tokens[$stackPtr]['content'], 1);
        if (!in_array($varName, $this->removedGlobalVar, true)) {
            return;
        }
        $phpcsFile->addError('Global Variable "%s" has been removed in PHP 7', $stackPtr, 'GlobalVar', [$varName]);
    }
}
