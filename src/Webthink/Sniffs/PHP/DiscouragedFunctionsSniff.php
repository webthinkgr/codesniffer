<?php

namespace WebthinkSniffer\Webthink\Sniffs\PHP;

use Doctrine\Common\Inflector\Inflector;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff as GenericForbiddenFunctionsSniff;

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
 * @see http://php.net/manual/en/aliases.php
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class DiscouragedFunctionsSniff extends GenericForbiddenFunctionsSniff
{
    /**
     * @inheritDoc
     */
    public $forbiddenFunctions = [
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
     * @inheritDoc
     */
    public $error = false;

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
