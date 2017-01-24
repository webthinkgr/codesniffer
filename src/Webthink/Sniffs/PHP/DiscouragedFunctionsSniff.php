<?php

namespace WebthinkSniffer;

use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff as GenericForbiddenFunctionsSniff;

/**
 * This rule is created to override the default Forbidden functions
 *
 * @author George Mponos <gmponos@gmail.com>
 */
final class DiscouragedFunctionsSniff extends GenericForbiddenFunctionsSniff
{
    /**
     * @inheritDoc
     */
    public $forbiddenFunctions = [
        'sizeof' => 'count',  //aliases are not allowed.
        'delete' => 'unset',  //use unset. Who the hell uses delete?
        'is_null' => null,  // aliases are not allowed.
    ];

    /**
     * @inheritDoc
     */
    public $error = false;
}
