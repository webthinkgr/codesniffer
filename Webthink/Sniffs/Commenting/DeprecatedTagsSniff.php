<?php

/**
 * Checks the usage of deprecated PHPDoc tags according to PSR-5.
 *
 * @see http://pear.php.net/package/PHP_CodeSniffer
 */
class Webthink_Sniffs_Commenting_DeprecatedTagsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_DOC_COMMENT_TAG];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];
        if (!in_array($content, ['@link', '@category', '@subpackage'], true)) {
            return;
        }

        $phpcsFile->addWarning(sprintf('PHPDoc %s tag is deprecated', $content), $stackPtr, 'Deprecated');
    }
}
