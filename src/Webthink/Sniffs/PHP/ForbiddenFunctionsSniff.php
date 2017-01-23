<?php

namespace WebthinkSniffer;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * This rule is created to override the default Forbidden functions
 *
 * @author George Mponos <gmponos@gmail.com>
 */
class ForbiddenFunctionsSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
{
    /**
     * A list of forbidden functions with their alternatives.
     *
     * The value is NULL if no alternative exists. IE, the
     * function should just not be used.
     *
     * @var array (string => string|null)
     */
    public $forbiddenFunctions = [
        'sizeof' => 'count',  //aliases are not allowed.
        'delete' => 'unset',  //use unset. Who the hell uses delete?
        'print' => 'echo',  // use echo.
        'var_dump' => null,  //is not allowed.
        'die' => null,  //is not allowed.
        'exit' => null,  //is not allowed.
        'is_null' => null,  // aliases are not allowed.
        'create_function' => null,  // is not allowed.
        'curl_init' => null,  //use Guzzle instead or another package instead.
        'ldap_sort' => null,  //is deprecated in PHP 7.1.
        'password_hash' => null,  //is deprecated in PHP 7.1.
        'mcrypt_encrypt' => null,  //is deprecated in PHP 7.1.
        'mcrypt_create_iv' => null,  //is deprecated in PHP 7.1.
    ];
}//end class
