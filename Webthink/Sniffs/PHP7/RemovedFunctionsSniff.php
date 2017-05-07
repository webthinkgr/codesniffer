<?php

if (class_exists('Generic_Sniffs_PHP_ForbiddenFunctionsSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class Generic_Sniffs_PHP_ForbiddenFunctionsSniff not found');
}

/**
 * This sniff checks about functions that have been removed in PHP 7 and throws an error.
 * Also suggests an alternative if one exists.
 *
 * Inspired most from `wimg/php-compatibility` package.
 *
 * @see https://github.com/wimg/PHPCompatibility
 * @author Wim Godden <wim.godden@cu.be>
 * @copyright 2012 Cu.be Solutions bvba
 */
class Webthink_Sniffs_PHP7_RemovedFunctionsSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
{
    /**
     * @inheritdoc
     */
    public $forbiddenFunctions = [
        'call_user_method' => 'call_user_func',
        'call_user_method_array' => 'call_user_func_array',
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
        'datefmt_set_timezone_id' => 'datefmt_set_timezone',

        //No alternatives
        'imagepsbbox' => null,
        'imagepsencodefont' => null,
        'imagepsextendfont' => null,
        'imagepsfreefont' => null,
        'imagepsloadfont' => null,
        'imagepsslantfont' => null,
        'imagepstext' => null,
        'mysql_list_dbs' => null,
        'magic_quotes_runtime' => null,
        'set_magic_quotes_runtime' => null,
        'sql_regcase' => null,
        'mcrypt_ecb' => null,
        'mcrypt_cbc' => null,
        'mcrypt_cfb' => null,
        'mcrypt_ofb' => null,

        // MySQL removed.
        'mysql_​affected_​rows' => 'PDO',
        'mysql_​client_​encoding' => 'PDO',
        'mysql_​close' => 'PDO',
        'mysql_​connect' => 'PDO',
        'mysql_​create_​db' => 'PDO',
        'mysql_​data_​seek' => 'PDO',
        'mysql_​db_​name' => 'PDO',
        'mysql_​db_​query' => 'PDO',
        'mysql_​drop_​db' => 'PDO',
        'mysql_​errno' => 'PDO',
        'mysql_​error' => 'PDO',
        'mysql_​escape_​string' => 'PDO',
        'mysql_​fetch_​array' => 'PDO',
        'mysql_​fetch_​assoc' => 'PDO',
        'mysql_​fetch_​field' => 'PDO',
        'mysql_​fetch_​lengths' => 'PDO',
        'mysql_​fetch_​object' => 'PDO',
        'mysql_​fetch_​row' => 'PDO',
        'mysql_​field_​flags' => 'PDO',
        'mysql_​field_​len' => 'PDO',
        'mysql_​field_​name' => 'PDO',
        'mysql_​field_​seek' => 'PDO',
        'mysql_​field_​table' => 'PDO',
        'mysql_​field_​type' => 'PDO',
        'mysql_​free_​result' => 'PDO',
        'mysql_​get_​client_​info' => 'PDO',
        'mysql_​get_​host_​info' => 'PDO',
        'mysql_​get_​proto_​info' => 'PDO',
        'mysql_​get_​server_​info' => 'PDO',
        'mysql_​info' => 'PDO',
        'mysql_​insert_​id' => 'PDO',
        'mysql_​list_​dbs' => 'PDO',
        'mysql_​list_​fields' => 'PDO',
        'mysql_​list_​processes' => 'PDO',
        'mysql_​list_​tables' => 'PDO',
        'mysql_​num_​fields' => 'PDO',
        'mysql_​num_​rows' => 'PDO',
        'mysql_​pconnect' => 'PDO',
        'mysql_​ping' => 'PDO',
        'mysql_​query' => 'PDO',
        'mysql_​real_​escape_​string' => 'PDO',
        'mysql_​result' => 'PDO',
        'mysql_​select_​db' => 'PDO',
        'mysql_​set_​charset' => 'PDO',
        'mysql_​stat' => 'PDO',
        'mysql_​tablename' => 'PDO',
        'mysql_​thread_​id' => 'PDO',
        'mysql_​unbuffered_​query' => 'PDO',

        // MSSQL removed.
        'mssql_​bind' => 'PDO',
        'mssql_​close' => 'PDO',
        'mssql_​connect' => 'PDO',
        'mssql_​data_​seek' => 'PDO',
        'mssql_​execute' => 'PDO',
        'mssql_​fetch_​array' => 'PDO',
        'mssql_​fetch_​assoc' => 'PDO',
        'mssql_​fetch_​batch' => 'PDO',
        'mssql_​fetch_​field' => 'PDO',
        'mssql_​fetch_​object' => 'PDO',
        'mssql_​fetch_​row' => 'PDO',
        'mssql_​field_​length' => 'PDO',
        'mssql_​field_​name' => 'PDO',
        'mssql_​field_​seek' => 'PDO',
        'mssql_​field_​type' => 'PDO',
        'mssql_​free_​result' => 'PDO',
        'mssql_​free_​statement' => 'PDO',
        'mssql_​get_​last_​message' => 'PDO',
        'mssql_​guid_​string' => 'PDO',
        'mssql_​init' => 'PDO',
        'mssql_​min_​error_​severity' => 'PDO',
        'mssql_​min_​message_​severity' => 'PDO',
        'mssql_​next_​result' => 'PDO',
        'mssql_​num_​fields' => 'PDO',
        'mssql_​num_​rows' => 'PDO',
        'mssql_​pconnect' => 'PDO',
        'mssql_​query' => 'PDO',
        'mssql_​result' => 'PDO',
        'mssql_​rows_​affected' => 'PDO',
        'mssql_​select_​db' => 'PDO',
    ];

    /**
     * @inheritdoc
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

        if ($this->forbiddenFunctions[$function] !== null && $this->forbiddenFunctions[$function] !== 'null') {
            $type .= 'WithAlternative';
            $data[] = $this->forbiddenFunctions[$function];
            $error .= '; use %s() instead';
        }

        if ($this->error === true) {
            $phpcsFile->addError($error, $stackPtr, $type, $data);
        } else {
            $phpcsFile->addWarning($error, $stackPtr, $type, $data);
        }
    }
}
