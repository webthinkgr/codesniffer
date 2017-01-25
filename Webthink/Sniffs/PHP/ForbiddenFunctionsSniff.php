<?php

if (class_exists('Generic_Sniffs_PHP_ForbiddenFunctionsSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class Generic_Sniffs_PHP_ForbiddenFunctionsSniff not found');
}

/**
 * This rule is created to override the default Forbidden functions
 *
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_PHP_ForbiddenFunctionsSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
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
        'var_dump' => null,  //is not allowed.
        'die' => null,  //is not allowed.
        'exit' => null,  //is not allowed.
        'create_function' => null,  // is not allowed.
        'curl_init' => null,  //use Guzzle instead or another package instead.
        'ldap_sort' => null,  //is deprecated in PHP 7.1.
        'password_hash' => null,  //is deprecated in PHP 7.1.
        'mcrypt_encrypt' => null,  //is deprecated in PHP 7.1.
        'mcrypt_create_iv' => null,  //is deprecated in PHP 7.1.
        'apache_request_headers' => null, // Exists only on Apache webservers
        'getallheaders' => null, // Is an allias of apache_request_headers() 
    ];
}
