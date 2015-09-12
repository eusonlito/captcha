<?php
namespace Eusonlito\Captcha;

/**
 * Class to manage session
 */
class Session
{
    private static $name = 'captcha-string';

    /**
     * Set/Get the session variable name
     *
     * @param string $name
     *     If set, will set the session variable name.
     *     If not, returns the current session variable name.
     *
     * @return string
     */
    public static function name($name = null)
    {
        return $name ? (self::$name = $name) : self::$name;
    }

    /**
     * Save the captcha string into session
     *
     * @param string $string
     *     Captcha word string
     */
    public static function set($string)
    {
        self::start();

        $_SESSION[self::$name] = crypt(strtolower($string), self::salt());
    }

    /**
     * Check if session string and post strings are the same
     *
     * @return boolean
     */
    public static function check()
    {
        self::start();

        $n = self::$name;

        $valid = isset($_SESSION[$n])
            && isset($_POST[$n])
            && strlen($_POST[$n])
            && ($_SESSION[$n] === crypt(strtolower($_POST[$n]), self::salt()));

        if (isset($_SESSION[$n])) {
            unset($_SESSION[$n]);
        }

        if (isset($_POST[$n])) {
            unset($_POST[$n]);
        }

        return $valid;
    }

    /**
     * Activate the session if not active
     */
    public static function start()
    {
        session_id() || session_start();
    }

    /**
     * Generate a crypt salt
     *
     * @return string
     */
    private static function salt()
    {
        return md5(__FILE__.filemtime(__FILE__));
    }
}
