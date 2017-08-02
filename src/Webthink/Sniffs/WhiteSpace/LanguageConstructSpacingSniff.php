<?php

namespace WebthinkSniffer\Webthink\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\LanguageConstructSpacingSniff as SquizLanguageConstructSpacingSniff;

/**
 * Overrides the tokens of Squiz_Sniffs_WhiteSpace_LanguageConstructSpacingSniff
 * in order to listen to more tokens.
 *
 * At the time that this is written there is a merge request open at official codesniffer
 * that fixes this.
 * 
 * https://github.com/squizlabs/PHP_CodeSniffer/pull/1337
 * 
 * @see Squiz_Sniffs_WhiteSpace_LanguageConstructSpacingSniff
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class LanguageConstructSpacingSniff extends SquizLanguageConstructSpacingSniff
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
