<?php

namespace WebthinkSniffer\Webthink\Sniffs\NamingConventions;

use Doctrine\Common\Inflector\Inflector;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * The current sniff checks if the class name is using a Singular Name otherwise it adds an error.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class SingularClassNameSniff implements Sniff
{
    /**
     * @var array
     */
    public $dictionaryExclusions = [];

    /**
     * @inheritdoc
     */
    public function register()
    {
        return [
            T_CLASS,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(File $phpcsFile, $startPtr)
    {
        $exclusions = array_map('strtolower', $this->dictionaryExclusions);
        $className = $phpcsFile->getDeclarationName($startPtr);
        if (in_array(strtolower($className), $exclusions, true) === true) {
            return;
        }

        $classSingular = Inflector::singularize($className);
        if ($className !== $classSingular) {
            $phpcsFile->addError(
                sprintf(
                    'Possible plural form used for Class %s. Consider using singular %s',
                    $className,
                    $classSingular
                ),
                $startPtr,
                'PluralClassName'
            );
        }
    }
}
