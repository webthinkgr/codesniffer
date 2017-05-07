<?php

namespace WebthinkSniffer\Sniffs\Metrics;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * The rule was inspired by Object Calisthenics.
 *
 * @author Tomáš Votruba <info@tomasvotruba.cz>
 * @see https://github.com/object-calisthenics/phpcs-calisthenics-rules
 */
final class MethodParametersLimitSniff implements Sniff
{
    /**
     * Maximum amount of methods per class.
     *
     * @var int
     */
    public $parameters = 10;

    /**
     * Absolute maximum amount of methods per class
     *
     * @var int
     */
    public $absoluteParameters = 20;

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
    public function process(File $phpcsFile, $stackPtr)
    {
        $function = $phpcsFile->getDeclarationName($stackPtr);
        $parameters = $phpcsFile->getMethodParameters($stackPtr);
        $count = count($parameters);
        if ($count > $this->absoluteParameters) {
            $message = 'Your %s has %d parameters, must be less or equals than %d methods';
            $error = sprintf($message, $function, $count, $this->absoluteParameters);
            $phpcsFile->addError($error, $stackPtr, 'TooManyParameters');
            return;
        }

        if ($count > $this->parameters) {
            $message = 'Your %s has %d $parameters, consider refactoring (should be less or equals than %d parameters)';
            $warning = sprintf($message, $function, $count, $this->parameters);
            $phpcsFile->addWarning($warning, $stackPtr, 'ExceedParameters');
            return;
        }
    }
}
