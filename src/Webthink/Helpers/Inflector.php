<?php

namespace WebthinkSniffer\Helpers;

/**
 * Class Inflector
 *
 * @internal
 */
class Inflector
{
    /**
     * Returns the function name in camelCaps
     *
     * @param string $function The function name
     * @return string
     */
    public static function camelCaps($function)
    {
        return str_replace(' ', '', ucwords(self::humanize($function)));
    }

    /**
     * @param $string
     * @return string|false
     */
    public static function humanize($string)
    {
        return str_replace('_', ' ', $string);
    }
}