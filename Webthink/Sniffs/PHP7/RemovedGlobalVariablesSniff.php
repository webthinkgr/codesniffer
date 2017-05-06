<?php

/**
 * Disallows the use of removed global variables.
 *
 * Copied most of this from `wimg/php-compatibility` package.
 *
 * @see    http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.http-raw-post-data
 * @see    https://github.com/wimg/PHPCompatibility
 * @author Wim Godden <wim.godden@cu.be>
 * @author George Mponos <gmponos@gmail.com>
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
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int $stackPtr The position of the current token in the stack passed in $tokens.
     * @return void
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
