<?php

/**
 * Eliminate alias usage of basic PHP functions.
 *
 * From the official PHP website:
 *
 * > It is usually a bad idea to use these kind of aliases,
 * > as they may be bound to obsolescence or renaming,
 * > which will lead to unportable script
 *
 * @author Mark Scherer
 * @see http://php.net/manual/en/aliases.php
 * @see https://github.com/php-fig-rectified/psr2r-sniffer
 * @license MIT
 */
class Webthink_Sniffs_PHP_AliasFunctionsSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * @see http://php.net/manual/en/aliases.php
     *
     * @var array
     */
    public static $matching = [
        'is_integer' => 'is_int',
        'is_long' => 'is_int',
        'is_real' => 'is_float',
        'is_double' => 'is_float',
        'is_writeable' => 'is_writable',
        'join' => 'implode',
        'key_exists' => 'array_key_exists',
        'sizeof' => 'count',
        'strchr' => 'strstr',
        'fputs' => 'fwrite',
        'chop' => 'rtrim',
        'print' => 'echo',
        'i18n_convert' => 'mb_convert_encoding',
        'i18n_discover_encoding' => 'mb_detect_encoding',
        'i18n_http_input' => 'mb_http_input',
        'i18n_http_output' => 'mb_http_output',
        'i18n_internal_encoding' => 'mb_internal_encoding',
        'i18n_ja_jp_hantozen' => 'mb_convert_kana',
        'i18n_mime_header_decode' => 'mb_decode_mimeheader',
        'i18n_mime_header_encode' => 'mb_encode_mimeheader',
    ];

    /**
     * @inheritDoc
     */
    public function register()
    {
        return [
            T_STRING,
        ];
    }

    /**
     * @inheritDoc
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $wrongTokens = [T_FUNCTION, T_OBJECT_OPERATOR, T_NEW, T_DOUBLE_COLON];

        $tokenContent = $tokens[$stackPtr]['content'];
        $key = strtolower($tokenContent);
        if (!isset(static::$matching[$key])) {
            return;
        }

        $previous = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (!$previous || in_array($tokens[$previous]['code'], $wrongTokens, true)) {
            return;
        }

        $openingBrace = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        if (!$openingBrace || $tokens[$openingBrace]['type'] !== 'T_OPEN_PARENTHESIS') {
            return;
        }

        $error = 'Function name ' . $tokenContent . '() found, should be ' . static::$matching[$key] . '().';
        $fix = $phpcsFile->addFixableError($error, $stackPtr);
        if ($fix) {
            $phpcsFile->fixer->replaceToken($stackPtr, static::$matching[$key]);
        }
    }
}
