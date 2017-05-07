<?php

namespace WebthinkSniffer\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\LanguageConstructSpacingSniff as SquizLanguageConstructSpacingSniff;

/**
 * Overrides the tokens of Squiz_Sniffs_WhiteSpace_LanguageConstructSpacingSniff
 * in order to listen to more tokens.
 *
 * @see Squiz_Sniffs_WhiteSpace_LanguageConstructSpacingSniff
 * @author George Mponos <gmponos@gmail.com>
 */
class LanguageConstructSpacingSniff extends SquizLanguageConstructSpacingSniff
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        return array_merge(parent::register(), [
            T_YIELD,
            T_THROW,
            T_USE,
            T_NAMESPACE,
        ]);
    }
}
