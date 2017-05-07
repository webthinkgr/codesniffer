<?php

/**
 * Overrides the tokens of Squiz_Sniffs_WhiteSpace_LanguageConstructSpacingSniff
 * in order to listen to more tokens.
 *
 * @see Squiz_Sniffs_WhiteSpace_LanguageConstructSpacingSniff
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
class Webthink_Sniffs_WhiteSpace_LanguageConstructSpacingSniff extends Squiz_Sniffs_WhiteSpace_LanguageConstructSpacingSniff
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
