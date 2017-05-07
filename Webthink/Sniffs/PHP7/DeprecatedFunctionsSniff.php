<?php

if (class_exists('Generic_Sniffs_PHP_ForbiddenFunctionsSniff', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class Generic_Sniffs_PHP_ForbiddenFunctionsSniff not found');
}

/**
 * This sniff contains the deprecated functions from 5.x to 7 and also the ones from 7.0 to 7.1
 * It outputs a warning if any of these function has been used.
 *
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_PHP7_DeprecatedFunctionsSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
{
    /**
     * @inheritdoc
     */
    public $forbiddenFunctions = [
        'ldap_sort' => null,
        'mcrypt_create_iv' => 'OpenSSL',
        'mcrypt_decrypt' => 'OpenSSL',
        'mcrypt_enc_get_algorithms_name' => 'OpenSSL',
        'mcrypt_enc_get_block_size' => 'OpenSSL',
        'mcrypt_enc_get_iv_size' => 'OpenSSL',
        'mcrypt_enc_get_key_size' => 'OpenSSL',
        'mcrypt_enc_get_modes_name' => 'OpenSSL',
        'mcrypt_enc_get_supported_key_sizes' => 'OpenSSL',
        'mcrypt_enc_is_block_algorithm_mode' => 'OpenSSL',
        'mcrypt_enc_is_block_algorithm' => 'OpenSSL',
        'mcrypt_enc_is_block_mode' => 'OpenSSL',
        'mcrypt_enc_self_test' => 'OpenSSL',
        'mcrypt_encrypt' => 'OpenSSL',
        'mcrypt_generic_deinit' => 'OpenSSL',
        'mcrypt_generic_init' => 'OpenSSL',
        'mcrypt_generic' => 'OpenSSL',
        'mcrypt_get_block_size' => 'OpenSSL',
        'mcrypt_get_cipher_name' => 'OpenSSL',
        'mcrypt_get_iv_size' => 'OpenSSL',
        'mcrypt_get_key_size' => 'OpenSSL',
        'mcrypt_list_algorithms' => 'OpenSSL',
        'mcrypt_list_modes' => 'OpenSSL',
        'mcrypt_module_close' => 'OpenSSL',
        'mcrypt_module_get_algo_block_size' => 'OpenSSL',
        'mcrypt_module_get_algo_key_size' => 'OpenSSL',
        'mcrypt_module_get_supported_key_sizes' => 'OpenSSL',
        'mcrypt_module_is_block_algorithm_mode' => 'OpenSSL',
        'mcrypt_module_is_block_algorithm' => 'OpenSSL',
        'mcrypt_module_is_block_mode' => 'OpenSSL',
        'mcrypt_module_open' => 'OpenSSL',
        'mcrypt_module_self_test' => 'OpenSSL',
        'mdecrypt_generic' => 'OpenSSL',
    ];

    /**
     * @inheritdoc
     */
    public $error = false;

    /**
     * @inheritdoc
     */
    protected function addError($phpcsFile, $stackPtr, $function, $pattern = null)
    {
        $data = [$function];
        $error = 'Function %s() has been deprecated in PHP 7';
        $type = 'Deprecated';

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
