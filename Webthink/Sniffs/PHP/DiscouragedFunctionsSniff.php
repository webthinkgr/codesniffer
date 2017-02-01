<?php

if (class_exists('Generic_Sniffs_PHP_ForbiddenFunctionsSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class Generic_Sniffs_PHP_ForbiddenFunctionsSniff not found');
}

/**
 * This rule is created to override the default Forbidden functions
 * in order to throw a warning and discourage the use of some functions.
 *
 * Initially we discourage the use of all alias functions of PHP.
 *
 * From the official PHP website:
 *
 * > It is usually a bad idea to use these kind of aliases,
 * > as they may be bound to obsolescence or renaming,
 * > which will lead to unportable script
 *
 * @see    http://php.net/manual/en/aliases.php
 * @author George Bonos <gbonos@xm.com>
 */
class Xm_Sniffs_PHP_DiscouragedFunctionsSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
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
        // aliases are discouraged.
        'chop' => 'rtrim',
        'delete' => 'unset',
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
        'is_null' => null,
        'is_real' => 'is_float',
        'join' => 'implode',
        'key_exists' => 'array_key_exists',
        'print' => 'echo',
        'sizeof' => 'count',

        // use guzzle instead
        'parse_url' => null,
        'parse_str' => null,
        'http_build_query' => null,

        // should only be used when dealing with legacy applications rawurlencode() should now be used instead.
        // See http://php.net/manual/en/function.rawurlencode.php and http://www.faqs.org/rfcs/rfc3986.html'
        'urlencode' => 'rawurlencode',
        'urldecode' => 'rawurldecode',

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

    /**
     * @inheritdoc
     */
    protected function addError($phpcsFile, $stackPtr, $function, $pattern = null)
    {
        $data = [$function];
        $error = 'The use of function %s() is ';
        $errorFunction = $this->camelCapsFunction($function);

        if ($this->error === true) {
            $error .= 'forbidden';
        } else {
            $error .= 'discouraged';
        }

        $type = 'Found' . $errorFunction;

        if ($this->forbiddenFunctions[$function] !== null && $this->forbiddenFunctions[$function] !== 'null') {
            $data[] = $this->forbiddenFunctions[$function];
            $error .= '; use %s() instead';
        }

        if ($this->error === true) {
            $phpcsFile->addError($error, $stackPtr, $type, $data);
        } else {
            $phpcsFile->addWarning($error, $stackPtr, $type, $data);
        }
    }

    /**
     * Returns the function name in camelCaps
     *
     * @param string $function The function name
     * @return string
     */
    private function camelCapsFunction($function)
    {
        $function = str_replace('_', ' ', $function);
        $function = ucwords($function);
        $function = str_replace(' ', '', $function);
        return $function;
    }
}
