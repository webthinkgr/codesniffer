<?php

namespace WebthinkSniffer\Webthink\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\LanguageConstructSpacingSniff as SquizLanguageConstructSpacingSniff;

/**
 * Overrides the tokens of PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\LanguageConstructSpacingSniffs
 * in order to listen to more tokens.
 *
 * At the time that this is written there is a merge request open at official codesniffer
 * that fixes this.
 *
 * https://github.com/squizlabs/PHP_CodeSniffer/pull/1337
 *
 * @see \PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\LanguageConstructSpacingSniff
 * @deprecated Codesniffer fixed the extended sniff
 * @author George Mponos <gmponos@gmail.com>
 * @license MIT
 */
final class LanguageConstructSpacingSniff extends SquizLanguageConstructSpacingSniff
{
}
