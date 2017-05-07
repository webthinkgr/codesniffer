<?php

namespace WebthinkSniffer\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;

/**
 * Checks that the variable have the correct comments
 *
 * @author George Mponos <gmponos@gmail.com>
 */
final class VariableCommentSniff extends AbstractVariableSniff
{
    /**
     * @inheritdoc
     */
    public function processMemberVar(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $ignore = [
            T_PUBLIC,
            T_PRIVATE,
            T_PROTECTED,
            T_VAR,
            T_STATIC,
            T_WHITESPACE,
        ];

        $commentEnd = $phpcsFile->findPrevious($ignore, ($stackPtr - 1), null, true);
        if ($commentEnd === false) {
            return;
        }

        if ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG && $tokens[$commentEnd]['code'] !== T_COMMENT) {
            return;
        }

        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            $phpcsFile->addError(
                'You must use "/**" style comments for a member variable comment',
                $stackPtr,
                'WrongStyle'
            );
            return;
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];
        $foundVar = null;

        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            if ($tokens[$tag]['content'] === '@var') {
                if ($foundVar !== null) {
                    $error = 'Only one @var tag is allowed in a member variable comment';
                    $phpcsFile->addError($error, $tag, 'DuplicateVar');
                } else {
                    $foundVar = $tag;
                }
            }
        }

        // The @var tag is the only one we require.
        if ($foundVar === null) {
            $phpcsFile->addError('Missing @var tag in member variable comment', $commentEnd, 'MissingVar');
            return;
        }

        // Make sure the tag isn't empty and has the correct padding.
        $string = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $foundVar, $commentEnd);
        if ($string === false || $tokens[$string]['line'] !== $tokens[$foundVar]['line']) {
            $phpcsFile->addError(
                'Content missing for @var tag in member variable comment',
                $foundVar,
                'EmptyVarContent'
            );
            return;
        }
    }

    /**
     * @inheritdoc
     */
    protected function processVariable(File $phpcsFile, $stackPtr)
    {
    }

    /**
     * @inheritdoc
     */
    protected function processVariableInString(File $phpcsFile, $stackPtr)
    {
    }
}
