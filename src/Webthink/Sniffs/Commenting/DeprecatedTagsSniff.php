<?php

namespace WebthinkSniffer\Webthink\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks the usage of deprecated PHPDoc tags according to PSR-5.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class DeprecatedTagsSniff implements Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_DOC_COMMENT_TAG,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];
        if (!in_array($content, ['@link', '@category', '@subpackage'], true)) {
            return;
        }

        $phpcsFile->addWarning(sprintf('PHPDoc %s tag is deprecated', $content), $stackPtr, 'Deprecated');
    }
}
