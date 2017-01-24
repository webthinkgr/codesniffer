<?php

namespace WebthinkSniffer;

use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff as GenericForbiddenFunctionsSniff;

/**
 * This rule is created to override the default Forbidden functions
 *
 * @author George Mponos <gmponos@gmail.com>
 */
final class ForbiddenFunctionsSniff extends GenericForbiddenFunctionsSniff
{
    /**
     * @inheritDoc
     */
    public $forbiddenFunctions = [
        'var_dump' => null,  //is not allowed.
        'die' => null,  //is not allowed.
        'exit' => null,  //is not allowed.
        'create_function' => null,  // is not allowed.
        'curl_init' => null,  //use Guzzle instead or another package instead.
        'ldap_sort' => null,  //is deprecated in PHP 7.1.
        'password_hash' => null,  //is deprecated in PHP 7.1.
        'mcrypt_encrypt' => null,  //is deprecated in PHP 7.1.
        'mcrypt_create_iv' => null,  //is deprecated in PHP 7.1.
    ];
}
