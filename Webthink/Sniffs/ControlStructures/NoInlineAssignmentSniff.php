<?php

/**
 * Sniff to suppress the use of
 *
 * Fail: if ( $a = 0 )
 * Fail: if ( $a *= foo() )
 * Fail: if ( $a += foo() )
 * Pass: if ( $a == 0 )
 * Pass: if ( $a === 0 )
 * Pass: if ( $a === array( 1 => 0 ) )
 *
 * @see    https://github.com/wikimedia/mediawiki-tools-codesniffer
 * @author George Mponos <gmponos@gmail.com>
 */
class Webthink_Sniffs_ControlStructures_NoInlineAssignmentSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * @return array
     */
    public function register()
    {
        return [
            T_IF,
            T_ELSEIF,
        ];
    }

    /**
     * @inheritdoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];

        $next = $token['parenthesis_opener'] + 1;
        $end = $token['parenthesis_closer'];
        while ($next < $end) {
            $code = $tokens[$next]['code'];
            // Check if any assignment operator was used. Allow T_DOUBLE_ARROW as that can
            // be used in an array like `if ( $foo === array( 'foo' => 'bar' ) )`
            if (in_array($code, PHP_CodeSniffer_Tokens::$assignmentTokens, true) && $code !== T_DOUBLE_ARROW) {
                $error = 'Assignment expression not allowed within "%s".';
                $phpcsFile->addError(
                    $error,
                    $stackPtr,
                    'AssignmentInControlStructures',
                    $token['content']
                );
                break;
            }
            $next++;
        }
    }
}
