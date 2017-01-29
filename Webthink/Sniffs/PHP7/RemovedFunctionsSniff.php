<?php

if (class_exists('Generic_Sniffs_PHP_ForbiddenFunctionsSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class Generic_Sniffs_PHP_ForbiddenFunctionsSniff not found');
}

/**
 * This sniff checks about functions that have been removed in PHP 7 and throws an error.
 * Also suggests an alternative if one exists.
 *
 * Copied most of this from `wimg/php-compatibility` package.
 *
 * @see    https://github.com/wimg/PHPCompatibility
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_PHP7_RemovedFunctionsSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
{
    /**
     * A list of forbidden functions with their alternatives.
     *
     * The value is NULL if no alternative exists. IE, the
     * function should just not be used.
     *
     * @var array(string => string|null)
     */
    public $forbiddenFunctions = [
        'call_user_method' => 'call_user_func',
        'call_user_method_array' => 'call_user_func_array',
        'datefmt_set_timezone_id' => 'datefmt_set_timezone',
        'ereg' => 'preg_match',
        'ereg_replace' => 'preg_replace',
        'eregi' => 'preg_match',
        'eregi_replace' => 'preg_replace',
        'mcrypt_generic_end' => 'mcrypt_generic_deinit',
        'mysql_db_query' => 'mysqli_select_db and mysqli_query',
        'mysql_escape_string' => 'mysqli_real_escape_string',
        'set_socket_blocking' => 'stream_set_blocking',
        'split' => 'preg_split',
        'spliti' => 'preg_split',

        //No alternatives
        'imagepsbbox' => null,
        'imagepsencodefont' => null,
        'imagepsextendfont' => null,
        'imagepsfreefont' => null,
        'imagepsloadfont' => null,
        'imagepsslantfont' => null,
        'imagepstext' => null,
        'magic_quotes_runtime' => null,
        'mcrypt_cbc' => null,
        'mcrypt_cfb' => null,
        'mcrypt_ecb' => null,
        'mcrypt_ofb' => null,
        'mysql_list_dbs' => null,
        'set_magic_quotes_runtime' => null,
        'sql_regcase' => null,
    ];

    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    public $error = true;

    /**
     * @inheritdoc
     */
    protected function addError($phpcsFile, $stackPtr, $function, $pattern = null)
    {
        $data = [$function];
        $error = 'Function %s() has been removed in PHP 7';
        $type = 'Removed';

        if ($this->forbiddenFunctions[$pattern] !== null && $this->forbiddenFunctions[$pattern] !== 'null') {
            $type .= 'WithAlternative';
            $data[] = $this->forbiddenFunctions[$pattern];
            $error .= '; use %s() instead';
        }

        if ($this->error === true) {
            $phpcsFile->addError($error, $stackPtr, $type, $data);
        } else {
            $phpcsFile->addWarning($error, $stackPtr, $type, $data);
        }
    }
}
