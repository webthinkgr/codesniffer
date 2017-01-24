<?php

namespace WebthinkSniffer;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Check for amount of methods per class, part of "Keep your classes small"
 * The rule was inspired by Object Calisthenics.
 *
 * @author George Mponos <gmponos@gmail.com>
 */
final class MethodPerClassLimitSniff implements Sniff
{
    /**
     * Maximum amount of methods per class.
     *
     * @var int
     */
    public $maxCount = 10;

    /**
     * Absolute maximum amount of methods per class
     *
     * @var int
     */
    public $absoluteMaxCount = 20;

    /**
     * Supported list of tokenizers supported by this sniff.
     *
     * @var array
     */
    public $supportedTokenizers = [
        'PHP',
    ];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_CLASS,
            T_INTERFACE,
            T_TRAIT,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];
        $tokenType = strtolower(substr($token['type'], 2));
        $methods = $this->getClassMethods($phpcsFile, $stackPtr);
        $methodCount = count($methods);

        if ($methodCount > $this->absoluteMaxCount) {
            $message = 'Your %s has %d methods, must be less or equals than %d methods';
            $error = sprintf($message, $tokenType, $methodCount, $this->absoluteMaxCount);
            $phpcsFile->addError($error, $stackPtr, sprintf('%sTooManyMethods', ucfirst($tokenType)));
            return;
        }

        if ($methodCount > $this->maxCount) {
            $message = 'Your %s has %d methods, consider refactoring (should be less or equals than %d methods)';
            $warning = sprintf($message, $tokenType, $methodCount, $this->maxCount);
            $phpcsFile->addWarning($warning, $stackPtr, sprintf('%sTooManyMethods', ucfirst($tokenType)));
            return;
        }
    }

    /**
     * Retrieve the list of class methods' pointers.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned
     * @param int                         $stackPtr  The position of the current token in the stack passed in $tokens.
     * @return array
     */
    private function getClassMethods(File $phpcsFile, $stackPtr)
    {
        $pointer = $stackPtr;
        $methods = [];

        while (($next = $phpcsFile->findNext(T_FUNCTION, $pointer + 1)) !== false) {
            $methods[] = $next;
            $pointer = $next;
        }

        return $methods;
    }
}
