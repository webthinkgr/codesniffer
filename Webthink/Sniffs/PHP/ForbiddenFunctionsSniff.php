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
        'create_function' => null, // is not allowed.
        'die' => null, //is not allowed.
        'exit' => null, //is not allowed.
        'var_dump' => null, //is not allowed.
        'print_r' => null,

        //use Guzzle or another HttpClient library instead.
        'curl_init' => null,
        'curl_exec' => null,
        'curl_multi_exec' => null,

        'ini_alter' => null,
        'ini_restore' => null,

        'apache_response_headers' => null, // Exists only on Apache webservers
        'apache_request_headers' => null, // Exists only on Apache webservers
        'getallheaders' => null, // Is an alias of apache_request_headers()
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
