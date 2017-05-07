<?php

/**
 * Asserts that function comments use the short form for types
 * - bool instead of boolean
 * - int instead of integer
 *
 * Copied from cakephp-codesniffer
 *
 * @see https://github.com/cakephp/cakephp-codesniffer
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_Commenting_FunctionCommentTypeSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return [T_DOC_COMMENT];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // We are only interested in function/class/interface doc block comments.
        $nextToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        $ignore = [
            T_CLASS,
            T_INTERFACE,
            T_FUNCTION,
            T_PUBLIC,
            T_PRIVATE,
            T_PROTECTED,
            T_STATIC,
            T_ABSTRACT,
        ];

        if (in_array($tokens[$nextToken]['code'], $ignore) === false) {
            // Could be a file comment.
            $prevToken = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
            if ($tokens[$prevToken]['code'] !== T_OPEN_TAG) {
                return;
            }
        }

        $types = [
            'boolean' => 'bool',
            'integer' => 'int',
        ];
        foreach ($types as $from => $to) {
            $this->_check($phpcsFile, $stackPtr, $from, $to);
        }
    }

    /**
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int $stackPtr
     * @param string $from
     * @param string $to
     * @return void
     */
    protected function _check(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $from, $to)
    {
        $tokens = $phpcsFile->getTokens();
        $content = $tokens[$stackPtr]['content'];

        $matches = [];
        if (preg_match('/\@(\w+)\s+([\w\\|\\\\]*?)' . $from . '\b/i', $content, $matches) === 0) {
            return;
        }

        $error = 'Please use "' . $to . '" instead of "' . $from . '" for types in doc blocks.';
        $phpcsFile->addWarning($error, $stackPtr, 'WrongType');
    }
}
