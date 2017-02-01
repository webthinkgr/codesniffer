<?php

namespace WebthinkSniffer\Sniffs\PHP7;

use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff;

/**
 * This sniff checks about functions that have been removed in PHP 7 and throws an error.
 * Also suggests an alternative if one exists.
 *
 * Copied most of this from `wimg/php-compatibility` package.
 *
 * @see    https://github.com/wimg/PHPCompatibility
 * @author George Mponos <gmponos@gmail.com>
 */
class RemovedFunctionsSniff extends ForbiddenFunctionsSniff
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
        'ereg' => 'preg_match',
        'ereg_replace' => 'preg_replace',
        'eregi' => 'preg_match',
        'eregi_replace' => 'preg_replace',
        'mcrypt_generic_end' => 'mcrypt_generic_deinit',
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
        'magic_quotes_runtime' => null,
        'set_magic_quotes_runtime' => null,
        'sql_regcase' => null,
        'mcrypt_ecb' => null,
        'mcrypt_cbc' => null,
        'mcrypt_cfb' => null,
        'mcrypt_ofb' => null,

        // MySQL removed.
        'mysql_affected_rows' => 'PDO',
        'mysql_client_encoding' => 'PDO',
        'mysql_close' => 'PDO',
        'mysql_connect' => 'PDO',
        'mysql_create_db' => 'PDO',
        'mysql_data_seek' => 'PDO',
        'mysql_db_name' => 'PDO',
        'mysql_db_query' => 'PDO',
        'mysql_drop_db' => 'PDO',
        'mysql_errno' => 'PDO',
        'mysql_error' => 'PDO',
        'mysql_escape_string' => 'PDO',
        'mysql_fetch_array' => 'PDO',
        'mysql_fetch_assoc' => 'PDO',
        'mysql_fetch_field' => 'PDO',
        'mysql_fetch_lengths' => 'PDO',
        'mysql_fetch_object' => 'PDO',
        'mysql_fetch_row' => 'PDO',
        'mysql_field_flags' => 'PDO',
        'mysql_field_len' => 'PDO',
        'mysql_field_name' => 'PDO',
        'mysql_field_seek' => 'PDO',
        'mysql_field_table' => 'PDO',
        'mysql_field_type' => 'PDO',
        'mysql_free_result' => 'PDO',
        'mysql_get_client_info' => 'PDO',
        'mysql_get_host_info' => 'PDO',
        'mysql_get_proto_info' => 'PDO',
        'mysql_get_server_info' => 'PDO',
        'mysql_info' => 'PDO',
        'mysql_insert_id' => 'PDO',
        'mysql_list_dbs' => 'PDO',
        'mysql_list_fields' => 'PDO',
        'mysql_list_processes' => 'PDO',
        'mysql_list_tables' => 'PDO',
        'mysql_num_fields' => 'PDO',
        'mysql_num_rows' => 'PDO',
        'mysql_pconnect' => 'PDO',
        'mysql_ping' => 'PDO',
        'mysql_query' => 'PDO',
        'mysql_real_escape_string' => 'PDO',
        'mysql_result' => 'PDO',
        'mysql_select_db' => 'PDO',
        'mysql_set_charset' => 'PDO',
        'mysql_stat' => 'PDO',
        'mysql_tablename' => 'PDO',
        'mysql_thread_id' => 'PDO',
        'mysql_unbuffered_query' => 'PDO',

        // MSSQL removed.
        'mssql_bind' => 'PDO',
        'mssql_close' => 'PDO',
        'mssql_connect' => 'PDO',
        'mssql_data_seek' => 'PDO',
        'mssql_execute' => 'PDO',
        'mssql_fetch_array' => 'PDO',
        'mssql_fetch_assoc' => 'PDO',
        'mssql_fetch_batch' => 'PDO',
        'mssql_fetch_field' => 'PDO',
        'mssql_fetch_object' => 'PDO',
        'mssql_fetch_row' => 'PDO',
        'mssql_field_length' => 'PDO',
        'mssql_field_name' => 'PDO',
        'mssql_field_seek' => 'PDO',
        'mssql_field_type' => 'PDO',
        'mssql_free_result' => 'PDO',
        'mssql_free_statement' => 'PDO',
        'mssql_get_last_message' => 'PDO',
        'mssql_guid_string' => 'PDO',
        'mssql_init' => 'PDO',
        'mssql_min_error_severity' => 'PDO',
        'mssql_min_message_severity' => 'PDO',
        'mssql_next_result' => 'PDO',
        'mssql_num_fields' => 'PDO',
        'mssql_num_rows' => 'PDO',
        'mssql_pconnect' => 'PDO',
        'mssql_query' => 'PDO',
        'mssql_result' => 'PDO',
        'mssql_rows_affected' => 'PDO',
        'mssql_select_db' => 'PDO',
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
