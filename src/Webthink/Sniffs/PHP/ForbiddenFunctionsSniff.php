<?php

namespace WebthinkSniffer\Webthink\Sniffs\PHP;

use Doctrine\Common\Inflector\Inflector;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff as GenericForbiddenFunctionsSniff;

/**
 * This rule is created to override the default Forbidden functions
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class ForbiddenFunctionsSniff extends GenericForbiddenFunctionsSniff
{
    /**
     * @inheritDoc
     */
    public $forbiddenFunctions = [
        'create_function' => null, // is not allowed.
        'die' => null, //is not allowed.
        'exit' => null, //is not allowed.
        'var_dump' => null, //is not allowed.
        'print_r' => null,

        'dd' => null, // Most of the frameworks have a function dump and die.
        'is_null' => null, // This is an alias function. It should exist in AliasFunctionsSniff but it does not have an alternative method.

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
        $errorFunction = Inflector::camelize($function);

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
}
