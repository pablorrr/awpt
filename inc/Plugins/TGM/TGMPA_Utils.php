<?php

class TGMPA_Utils
{
    /**
     * Whether the PHP filter extension is enabled.
     *
     * @see http://php.net/book.filter
     *
     * @since 2.5.0
     *
     * @static
     *
     * @var bool $has_filters True is the extension is enabled.
     */
    public static $has_filters;

    /**
     * Wrap an arbitrary string in <em> tags. Meant to be used in combination with array_map().
     *
     * @param string $string Text to be wrapped.
     * @return string
     * @since 2.5.0
     *
     * @static
     *
     */
    public static function wrap_in_em($string)
    {
        return '<em>' . wp_kses_post($string) . '</em>';
    }

    /**
     * Wrap an arbitrary string in <strong> tags. Meant to be used in combination with array_map().
     *
     * @param string $string Text to be wrapped.
     * @return string
     * @since 2.5.0
     *
     * @static
     *
     */
    public static function wrap_in_strong($string)
    {
        return '<strong>' . wp_kses_post($string) . '</strong>';
    }

    /**
     * Helper function: Validate a value as boolean
     *
     * @param mixed $value Arbitrary value.
     * @return bool
     * @since 2.5.0
     *
     * @static
     *
     */
    public static function validate_bool($value)
    {
        if (!isset(self::$has_filters)) {
            self::$has_filters = extension_loaded('filter');
        }

        if (self::$has_filters) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        } else {
            return self::emulate_filter_bool($value);
        }
    }

    /**
     * Helper function: Cast a value to bool
     *
     * @param mixed $value Value to cast.
     * @return bool
     * @since 2.5.0
     *
     * @static
     *
     */
    protected static function emulate_filter_bool($value)
    {
        // @codingStandardsIgnoreStart
        static $true = array(
            '1',
            'true', 'True', 'TRUE',
            'y', 'Y',
            'yes', 'Yes', 'YES',
            'on', 'On', 'ON',
        );
        static $false = array(
            '0',
            'false', 'False', 'FALSE',
            'n', 'N',
            'no', 'No', 'NO',
            'off', 'Off', 'OFF',
        );
        // @codingStandardsIgnoreEnd

        if (is_bool($value)) {
            return $value;
        } else if (is_int($value) && (0 === $value || 1 === $value)) {
            return (bool)$value;
        } else if ((is_float($value) && !is_nan($value)) && ((float)0 === $value || (float)1 === $value)) {
            return (bool)$value;
        } else if (is_string($value)) {
            $value = trim($value);
            if (in_array($value, $true, true)) {
                return true;
            } else if (in_array($value, $false, true)) {
                return false;
            } else {
                return false;
            }
        }

        return false;
    }
} // End of class TGMPA_Utils