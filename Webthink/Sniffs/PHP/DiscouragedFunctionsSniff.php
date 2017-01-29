<?php

if (class_exists('Generic_Sniffs_PHP_ForbiddenFunctionsSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class Generic_Sniffs_PHP_ForbiddenFunctionsSniff not found');
}

/**
 * This rule is created to override the default Forbidden functions
 *
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_PHP_DiscouragedFunctionsSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
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
        'chop' => 'rtrim',
        'delete' => 'unset', //use unset. Who the hell uses delete?
        'fputs' => 'fwrite',
        'i18n_convert' => 'mb_convert_encoding',
        'i18n_discover_encoding' => 'mb_detect_encoding',
        'i18n_http_input' => 'mb_http_input',
        'i18n_http_output' => 'mb_http_output',
        'i18n_internal_encoding' => 'mb_internal_encoding',
        'i18n_ja_jp_hantozen' => 'mb_convert_kana',
        'i18n_mime_header_decode' => 'mb_decode_mimeheader',
        'i18n_mime_header_encode' => 'mb_encode_mimeheader',
        'is_double' => 'is_float',
        'is_integer' => 'is_int',
        'is_long' => 'is_int',
        'is_null' => null,  // aliases are not allowed.
        'is_real' => 'is_float',
        'join' => 'implode', // aliases are not allowed.
        'key_exists' => 'array_key_exists',
        'print' => 'echo', // use echo.
        'sizeof' => 'count', //aliases are not allowed.

        // Serialized data has known vulnerability problems with Object Injection.
        // JSON is generally a better approach for serializing data.
        // See https://www.owasp.org/index.php/PHP_Object_Injection
        'serialize' => null,
        'unserialize' => null,
    ];

    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    public $error = false;
}
