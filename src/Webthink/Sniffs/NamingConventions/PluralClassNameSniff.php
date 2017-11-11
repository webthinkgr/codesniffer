<?php

namespace WebthinkSniffer\Webthink\Sniffs\NamingConventions;

use Doctrine\Common\Inflector\Inflector;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * The current sniff checks if the class name is using a Plural Name otherwise it adds an error.
 *
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class PluralClassNameSniff implements Sniff
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
    public function process(File $phpcsFile, $stackPtr)
    {
        $exclusions = array_map('strtolower', $this->dictionaryExclusions);
        $className = $phpcsFile->getDeclarationName($stackPtr);
        if (in_array(strtolower($className), $exclusions, true) === true) {
            return;
        }

        $classPlural = Inflector::pluralize($className);
        if ($className !== $classPlural) {
            $phpcsFile->addError(
                sprintf(
                    'Possible singular form used for Class %s. Consider using plural %s',
                    $className,
                    $classPlural
                ),
                $stackPtr,
                'SingularClassName'
            );
        }
    }
}
