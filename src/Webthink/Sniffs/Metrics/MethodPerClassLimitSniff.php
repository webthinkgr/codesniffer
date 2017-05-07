<?php

namespace WebthinkSniffer\Sniffs\Metrics;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Check for amount of methods per class, part of "Keep your classes small"
 * The rule was inspired by Object Calisthenics.
 *
 * It was altered in order to ignore private/protected methods and also all magic methods of PHP.
 *
 * @author Tomáš Votruba <info@tomasvotruba.cz>
 * @see https://github.com/object-calisthenics/phpcs-calisthenics-rules
 */
final class MethodPerClassLimitSniff implements Sniff
{
    /**
     * Maximum amount of methods per class.
     *
     * @var int
     */
    public $maxCount = 12;

    /**
     * Absolute maximum amount of methods per class.
     * You can set this value to 0 in order to have only a warning message.
     *
     * @var int
     */
    public $absoluteMaxCount = 22;

    /**
     * Supported list of tokenizers supported by this sniff.
     *
     * @var array
     */
    public $supportedTokenizers = [
        'PHP',
    ];

    /**
     * Contains the magic methods of PHP
     *
     * @var array
     */
    protected $magicMethods = [
        '__construct',
        '__destruct',
        '__call',
        '__callStatic',
        '__get',
        '__set',
        '__isset',
        '__unset',
        '__sleep',
        '__wakeup',
        '__toString',
        '__invoke',
        '__set_state',
        '__clone',
        '__debugInfo',
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

        if ($methodCount <= $this->maxCount) {
            return;
        }

        if ($this->absoluteMaxCount != 0 && $methodCount > $this->absoluteMaxCount) {
            $message = 'Your %s has %d methods, must be less or equals than %d methods';
            $error = sprintf($message, $tokenType, $methodCount, $this->absoluteMaxCount);
            $phpcsFile->addError($error, $stackPtr, sprintf('%sMaxExceeded', ucfirst($tokenType)));
            return;
        }

        if ($this->absoluteMaxCount != 0 && $methodCount > $this->maxCount) {
            $message = 'Your %s has %d methods, consider refactoring (should be less or equals than %d methods)';
            $warning = sprintf($message, $tokenType, $methodCount, $this->maxCount);
            $phpcsFile->addWarning($warning, $stackPtr, sprintf('%sTooMany', ucfirst($tokenType)));
            return;
        }
    }

    /**
     * Retrieve the list of class methods' pointers.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned
     * @param int $stackPtr The position of the current token in the stack passed in $tokens.
     * @return array
     */
    private function getClassMethods(File $phpcsFile, $stackPtr)
    {
        $pointer = $stackPtr;
        $methods = [];

        while (($next = $phpcsFile->findNext(T_FUNCTION, $pointer + 1)) !== false) {
            $modifier = $this->getModifier($phpcsFile, $next);
            if ($this->isPublic($modifier) && !$this->isMagicFunction($phpcsFile->getDeclarationName($next))) {
                $methods[] = $next;
            }

            $pointer = $next;
        }

        return $methods;
    }

    /**
     * @param string $modifier The functions scope modifier
     * @return bool
     */
    private function isPublic($modifier)
    {
        return $modifier === 'public';
    }

    /**
     * @param string $name
     * @return bool
     */
    private function isMagicFunction($name)
    {
        return in_array($name, $this->magicMethods, true);
    }

    /**
     * Gets the scope modifier of a method.
     *
     * @param File $phpcsFile
     * @param int $stackPtr
     * @return string
     */
    public function getModifier(File $phpcsFile, $stackPtr)
    {
        $properties = $phpcsFile->getMethodProperties($stackPtr);
        return $properties['scope'];
    }
}
