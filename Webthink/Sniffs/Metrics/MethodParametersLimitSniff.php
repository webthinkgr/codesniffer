<?php

/**
 * The rule was inspired by Object Calisthenics.
 *
 * @author George Mponos <gmponos@xm.com>
 */
class Webthink_Sniffs_Metrics_MethodParametersLimitSniff implements PHP_CodeSniffer_Sniff
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
     * Registers the tokens that this sniff wants to listen for.
     *
     * @return integer[]
     */
    public function register()
    {
        return [
            T_FUNCTION,
        ];
    }

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
