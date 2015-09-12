<?php
namespace Eusonlito\Captcha;

use Exception;

class Font
{
    private static $fonts = array();

    public static function add($font)
    {
        self::$fonts = array_merge(self::$fonts, self::check($font));
    }

    public static function set($font)
    {
        self::$fonts = self::check($font);
    }

    public static function get()
    {
        if (empty(self::$fonts)) {
            self::$fonts = self::load();
        }

        shuffle(self::$fonts);

        return self::$fonts;
    }

    private static function check($fonts)
    {
        if (is_string($fonts)) {
            $fonts = array($fonts);
        }

        foreach ($fonts as $font) {
            if (!is_file($font)) {
                throw new Exception(sprintf('Font file %s not exists', $font));
            }

            if (!preg_match('/\.ttf/i', $font)) {
                throw new Exception('Only ".ttf" are allowed as font');
            }
        }

        return $fonts;
    }

    private static function load()
    {
        return glob(realpath(__DIR__.'/../fonts').'/*.ttf');
    }
}
