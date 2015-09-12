<?php
namespace Eusonlito\Captcha;

class String
{
    private static $letters = 'ABCDEFGHJKLMNPRSTUVWXYZ';

    public static function letters($letters)
    {
        self::$letters = $letters;
    }

    public static function get($count)
    {
        $count = is_array($count) ? rand($count[0], $count[1]) : $count;

        return substr(str_shuffle(str_repeat(self::$letters, $count)), 0, $count);
    }
}