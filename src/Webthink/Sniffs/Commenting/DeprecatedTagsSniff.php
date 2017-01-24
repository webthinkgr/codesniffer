<?php

namespace WebthinkSniffer;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks the usage of deprecated PHPDoc tags according to PSR-5.
 *
 * @package Codesniffer
 * @see     http://pear.php.net/package/PHP_CodeSniffer
 */
final class DeprecatedTagsSniff implements Sniff
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
    public function process(File $phpcsFile, $stackPtr)
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
