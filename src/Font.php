<?php
namespace Eusonlito\Captcha;

use Exception;

/**
 * Class to manage fonts
 */
class Font
{
    private static $fonts = array();

    /**
     * Add a font to available fonts to generated captcha
     *
     * @param string|array $font
     *     A font or fonts array to use
     *
     * @throws Exception
     *     If font file not exists
     */
    public static function add($font)
    {
        self::$fonts = array_merge(self::$fonts, self::check($font));
    }

    /**
     * Set available fonts to generated captcha
     *
     * @param string|array $font
     *     A font or fonts array to use
     *
     * @throws Exception
     *     If font file not exists
     */
    public static function set($font)
    {
        self::$fonts = self::check($font);
    }

    /**
     * Return the current fonts collection after a shuffle
     *
     * @return array
     */
    public static function get()
    {
        if (empty(self::$fonts)) {
            self::$fonts = self::load();
        }

        shuffle(self::$fonts);

        return self::$fonts;
    }

    /**
     * Check if fonts are valid
     *
     * @param string|array $fonts
     *     A font or fonts array to check
     *
     * @throws Exception
     *     If font file not exists
     *
     * @return array
     */
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

    /**
     * Return the package default fonts collection
     *
     * @return array
     */
    private static function load()
    {
        return glob(realpath(__DIR__.'/../fonts').'/*.ttf');
    }
}
