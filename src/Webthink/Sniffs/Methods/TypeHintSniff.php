<?php

namespace WebthinkSniffer\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Disallows the usage of FQCN typehints in method parameters.
 *
 * Exceptions in this rule are parameters that have only one backslash in the start of FQCN like
 * \ArrayIterator or like \MyClassName. This exception cases are allowed in order not to
 * enforce importing PHP core FQCN.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
class TypeHintSniff implements Sniff
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
    public function process(File $phpcsFile, $stackPtr)
    {
        $function = $phpcsFile->getDeclarationName($stackPtr);
        $parameters = $phpcsFile->getMethodParameters($stackPtr);
        if (empty($parameters)) {
            return;
        }

        foreach ($parameters as $parameter) {
            $typeHint = $parameter['type_hint'];
            if (empty($typeHint) || in_array($typeHint, ['array', 'int', 'bool', 'self', 'static'], true)) {
                continue;
            }

            $firstPos = strpos($typeHint, "\\");
            if ($firstPos === false) {
                continue;
            }

            // If the backslash was not found in the first char then it means it's a FQCN
            if ($firstPos > 0) {
                $phpcsFile->addError(
                    'FQCN TypeHint %s is not allowed in function %s. Use `use` statements instead.',
                    $stackPtr,
                    'IncorrectTypeHint',
                    [$typeHint, $function]
                );
            }

            // If the backslash was found in the first char but it exists more than once
            // then it means it's a FQCN.
            if (substr_count($typeHint, '\\') > 1) {
                $phpcsFile->addError(
                    'FQCN TypeHint %s is not allowed in function %s. Use `use` statements instead.',
                    $stackPtr,
                    'IncorrectTypeHint',
                    [$typeHint, $function]
                );
            }
        }
    }
}
