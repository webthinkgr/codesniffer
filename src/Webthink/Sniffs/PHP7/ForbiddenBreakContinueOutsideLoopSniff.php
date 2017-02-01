<?php

namespace WebthinkSniffer\Sniffs\PHP7;

use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Forbids use of break or continue statements outside of looping structures.
 *
 * Copied most of this from `wimg/php-compatibility` package.
 *
 * @see    https://github.com/wimg/PHPCompatibility
 * @author George Mponos <gmponos@gmail.com>
 */
class ForbiddenBreakContinueOutsideLoopSniff implements Sniff
{
    /**
     * Token codes of control structure in which usage of break/continue is valid.
     *
     * @var array
     */
    protected $validLoopStructures = [
        T_FOR => true,
        T_FOREACH => true,
        T_WHILE => true,
        T_DO => true,
        T_SWITCH => true,
    ];

    /**
     * Token codes which did not correctly get a condition assigned in older PHPCS versions.
     *
     * @var array
     */
    protected $backCompat = [
        T_CASE => true,
        T_DEFAULT => true,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_BREAK,
            T_CONTINUE,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];

        // Check if the break/continue is within a valid loop structure.
        if (empty($token['conditions']) === false) {
            foreach ($token['conditions'] as $tokenCode) {
                if (isset($this->validLoopStructures[$tokenCode])) {
                    return;
                }
            }
        } elseif (isset($token['scope_condition']) && isset($this->backCompat[$tokens[$token['scope_condition']]['code']])) {
            return;
        }

        $phpcsFile->addError(
            "Using '%s' outside of a loop or switch structure is invalid and will throw a fatal error in PHP 7",
            $stackPtr,
            'FatalError',
            [$token['content']]
        );
    }
}
