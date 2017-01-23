<?php

/**
 * Checks the usage of deprecated PHPDoc tags according to PSR-5.
 *
 * @package Codesniffer
 * @see     http://pear.php.net/package/PHP_CodeSniffer
 */
class Webthink_Sniffs_Commenting_DeprecatedTagsSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_DOC_COMMENT_TAG];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the stack passed in $tokens.
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];
        if (!in_array($content, ['@link', '@category', '@subpackage'])) {
            return;
        }

        $message = sprintf('PHPDoc %s tag is deprecated', $content);
        $phpcsFile->addWarning($message, $stackPtr, 'Deprecated');
    }
}
