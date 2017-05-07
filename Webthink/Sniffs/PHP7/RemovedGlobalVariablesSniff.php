<?php

/**
 * Disallows the use of removed global variables.
 *
 * @see http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.other.http-raw-post-data
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
class Webthink_Sniffs_PHP7_RemovedGlobalVariablesSniff implements PHP_CodeSniffer_Sniff
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
        return [T_VARIABLE];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $varName = substr($tokens[$stackPtr]['content'], 1);
        if (!in_array($varName, $this->removedGlobalVar, true)) {
            return;
        }
        $phpcsFile->addError('Global Variable "%s" has been removed in PHP 7', $stackPtr, 'GlobalVar', [$varName]);
    }
}
