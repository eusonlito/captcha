<?php
namespace Eusonlito\Captcha;

class Session
{
    private static $name = 'captcha-string';

    public static function name($name = null)
    {
        return $name ? (self::$name = $name) : self::$name;
    }

    public static function set($string)
    {
        self::start();

        $_SESSION[self::$name] = crypt(strtolower($string), self::salt());
    }

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

    public static function start()
    {
        session_id() || session_start();
    }

    private static function salt()
    {
        return md5(__FILE__.filemtime(__FILE__));
    }
}