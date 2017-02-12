<?php

/**
 * Checks for the order of the methods inside a class.
 *
 * For now it only checks if the `__construct` exists to be the first one in the class.
 *
 * @todo   we need to check that destruct function is the last.
 * @todo   we need to check the order in multiple classes in the same file.
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_Methods_OrderSniff implements PHP_CodeSniffer_Sniff
{
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
            T_FUNCTION,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $name = $phpcsFile->getDeclarationName($stackPtr);
        if ($name === '__construct') {
            if ($phpcsFile->findPrevious(T_FUNCTION, ($stackPtr - 1)) !== false) {
                $phpcsFile->addError(
                    '__construct function is not the first one in order inside the class.',
                    $stackPtr,
                    'ConstructorOrder'
                );
            }
            return;
        }

        if ($name === '__destruct') {
            if ($phpcsFile->findNext(T_FUNCTION, ($stackPtr + 1)) !== false) {
                $phpcsFile->addError(
                    '__destruct function must be the last one in order inside the class.',
                    $stackPtr,
                    'DestructorOrder'
                );
            }
            return;
        }
    }
}
