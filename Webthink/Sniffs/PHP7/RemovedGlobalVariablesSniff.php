<?php

/**
 * Disallows the use of removed global variables.
 *
 * Copied most of this from `wimg/php-compatibility` package
 * and changed it's codestyle.
 *
 * @see http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.http-raw-post-data
 * @see https://github.com/wimg/PHPCompatibility
 * @author Wim Godden <wim.godden@cu.be>
 * @author George Mponos <gmponos@gmail.com>
 * @copyright 2012 Cu.be Solutions bvba
 */
class Webthink_Sniffs_PHP7_RemovedGlobalVariablesSniff implements PHP_CodeSniffer_Sniff
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
        return [T_VARIABLE];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $varName = substr($tokens[$stackPtr]['content'], 1);
        if (!in_array($varName, $this->removedGlobalVariables, true)) {
            return;
        }
        $phpcsFile->addError('Global Variable "%s" has been removed in PHP 7', $stackPtr, 'Found', [$varName]);
    }
}
