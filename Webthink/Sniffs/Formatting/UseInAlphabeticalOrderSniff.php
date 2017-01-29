<?php

/**
 * Ensures all the `use` are in alphabetical order.
 * The rule was copied from `cakephp/cakephp-codesniffer`
 *
 * @see https://github.com/cakephp/cakephp-codesniffer/blob/master/CakePHP/Sniffs/Formatting/UseInAlphabeticalOrderSniff.php
 */
class Webthink_Sniffs_Formatting_UseInAlphabeticalOrderSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Processed files
     *
     * @var array
     */
    protected $_processed = [];

    /**
     * The list of use statements, their content and scope.
     *
     * @var array
     */
    protected $_uses = [];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_USE];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param integer              $stackPtr  The position of the current token in the stack passed in $tokens.
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if (isset($this->_processed[$phpcsFile->getFilename()])) {
            return;
        }
        $filename = $phpcsFile->getFilename();

        $this->_uses = [];
        $next = $stackPtr;

        while ($next !== false) {
            $this->checkUseToken($phpcsFile, $next);
            $next = $phpcsFile->findNext(T_USE, $next + 1);
        }

        // Prevent multiple uses in the same file from entering
        $this->_processed[$phpcsFile->getFilename()] = true;

        foreach ($this->_uses as $scope => $used) {
            $defined = $sorted = array_keys($used);

            natcasesort($sorted);
            $sorted = array_values($sorted);
            if ($sorted === $defined) {
                continue;
            }

            foreach ($defined as $i => $name) {
                if ($name !== $sorted[$i]) {
                    $error = 'Use classes must be in alphabetical order. Was expecting ' . $sorted[$i];
                    $phpcsFile->addError($error, $used[$name], 'UseInAlphabeticalOrder', []);
                }
            }
        }
    }

    /**
     * Check all the use tokens in a file.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file to check.
     * @param integer              $stackPtr  The index of the first use token.
     * @return void
     */
    protected function checkUseToken($phpcsFile, $stackPtr)
    {
        // If the use token is for a closure we want to ignore it.
        if ($this->isClosure($phpcsFile, $stackPtr)) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        // Only one USE declaration allowed per statement.
        $next = $phpcsFile->findNext([T_COMMA, T_SEMICOLON], ($stackPtr + 1));
        if ($tokens[$next]['code'] === T_COMMA) {
            $error = 'There must be one USE keyword per declaration';
            $phpcsFile->addError($error, $stackPtr, 'MultipleDeclarations');
        }

        $content = '';
        $end = $phpcsFile->findNext([T_SEMICOLON, T_OPEN_CURLY_BRACKET], $stackPtr);
        $useTokens = array_slice($tokens, $stackPtr, $end - $stackPtr, true);

        foreach ($useTokens as $index => $token) {
            if ($token['code'] === T_STRING || $token['code'] === T_NS_SEPARATOR) {
                $content .= $token['content'];
            }
        }

        // Check for class scoping on use. Traits should be ordered independently.
        $scope = 0;
        if (!empty($token['conditions'])) {
            $scope = key($token['conditions']);
        }
        $this->_uses[$scope][$content] = $stackPtr;
    }

    /**
     * Check if the current stackPtr is a use token that is for a closure.
     *
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param int                  $stackPtr
     * @return bool
     */
    protected function isClosure($phpcsFile, $stackPtr)
    {
        return $phpcsFile->findPrevious(
            [T_CLOSURE],
            ($stackPtr - 1),
            null,
            false,
            null,
            true
        );
    }
}
