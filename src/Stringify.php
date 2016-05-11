<?php
namespace Eusonlito\Captcha;

/**
 * Class to manage string generation
 */
class Stringify
{
    private static $letters = 'ABCDEFGHJKLMNPRSTUVWXYZ';

    /**
     * Set available letters to generated captcha
     *
     * @param string $letters
     *     A list of letters to use
     */
    public static function letters($letters)
    {
        self::$letters = $letters;
    }

    /**
     * Return a random string of letters
     *
     * @param integer|array $count
     *     Integer is used as fixed letters number and array as a min and max values.
     *
     * @return string
     */
    public static function get($count)
    {
        $count = is_array($count) ? rand($count[0], $count[1]) : $count;

        return substr(str_shuffle(str_repeat(self::$letters, $count)), 0, $count);
    }
}
