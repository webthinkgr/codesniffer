<?php

namespace WebthinkSniffer\Webthink\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Makes sure that deprecated annotation has a description.
 * This way we ensure that the author of the annotation wrote why it was deprecated
 * and the alternative that can be used.
 *
 * @author George Mponos <gmponos@gmail.com>
 */
class DeprecatedMissingDescriptionSniff implements Sniff
{
    /**
     * @inheritDoc
     */
    public function process(File $phpCsFile, $stackPointer)
    {
        $tokens = $phpCsFile->getTokens();
        if ($tokens[$stackPointer]['content'] !== '@deprecated') {
            return;
        }

        $next = $stackPointer + 2;
        if ($tokens[$next]['type'] !== 'T_DOC_COMMENT_STRING') {
            $phpCsFile->addError(
                'Deprecated annotation must have a description for alternative usage.',
                $stackPointer,
                'MissingDescription'
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        return [
            T_DOC_COMMENT_TAG,
        ];
    }
}