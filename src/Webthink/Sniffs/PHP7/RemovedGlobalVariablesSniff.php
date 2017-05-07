<?php

namespace WebthinkSniffer\Sniffs\PHP7;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Disallows the use of removed global variables.
 *
 * Copied most of this from `wimg/php-compatibility` package
 * and changed it's codestyle.
 *
 * @see http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.http-raw-post-data
 * @see https://github.com/wimg/PHPCompatibility
 * @author Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
final class RemovedGlobalVariablesSniff implements Sniff
{
    /**
     * A list of removed global variables with their alternative, if any.
     *
     * @var array(string|null)
     */
    protected $removedGlobalVariables = [
        'HTTP_RAW_POST_DATA',
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
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
        if (!in_array($varName, $this->removedGlobalVariables, true)) {
            return;
        }
        $phpcsFile->addError('Global Variable "%s" has been removed in PHP 7', $stackPtr, 'Found', [$varName]);
    }
}
