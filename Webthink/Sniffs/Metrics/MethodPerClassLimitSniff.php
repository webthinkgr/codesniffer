<?php

/**
 * Check for amount of methods per class, part of "Keep your classes small"
 * The rule was inspired by Object Calisthenics.
 *
 * It was altered in order to ignore private/protected methods and also all magic methods of PHP.
 *
 * @author George Mponos <gmponos@gmail.com>
 */
class Xm_Sniffs_Metrics_MethodPerClassLimitSniff implements PHP_CodeSniffer_Sniff
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
    public $absoluteMaxCount = 20;

    /**
     * Supported list of tokenizers supported by this sniff.
     *
     * @var array
     */
    public $supportedTokenizers = [
        'PHP',
    ];

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
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return integer[]
     */
    public function register()
    {
        return [
            T_CLASS,
            T_INTERFACE,
            T_TRAIT,
        ];
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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

        if ($methodCount > $this->maxCount) {
            $message = 'Your %s has %d methods, consider refactoring (should be less or equals than %d methods)';
            $warning = sprintf($message, $tokenType, $methodCount, $this->maxCount);
            $phpcsFile->addWarning($warning, $stackPtr, sprintf('%sTooMany', ucfirst($tokenType)));
            return;
        }
    }

    /**
     * Retrieve the list of class methods pointers.
     *
     * @param \PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                   $stackPtr  The position of the current token in the stack passed in $tokens.
     * @return array
     */
    private function getClassMethods(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
     * Gets the scope modifier of a method.
     *
     * @param PHP_CodeSniffer_File $phpcsFile
     * @param                      $stackPtr
     * @return string|null
     */
    private function getModifier(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        for ($i = ($stackPtr - 1); $i > 0; $i--) {
            if ($tokens[$i]['line'] < $tokens[$stackPtr]['line']) {
                return null;
            }

            if (isset(PHP_CodeSniffer_Tokens::$scopeModifiers[$tokens[$i]['code']])) {
                return $tokens[$i]['content'];
            }
        }

        return null;
    }

    /**
     * @param string $modifier
     * @return bool
     */
    private function isPublic($modifier)
    {
        return in_array($modifier, ['public', null]);
    }

    /**
     * Checks if a function name is a magic function.
     *
     * @param string $name
     * @return bool
     */
    private function isMagicFunction($name)
    {
        return in_array($name, $this->magicMethods, true);
    }
}
