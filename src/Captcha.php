<?php
namespace Eusonlito\Captcha;

/**
 * Main Captcha Class.
 *
 * All calls must be done from this class.
 */
class Captcha
{
    /**
     * Set/Get the session variable name
     *
     * @param string $name
     *     If set, will set the session variable name.
     *     If not, returns the current session variable name.
     *
     * @return string
     */
    public static function sessionName($name = null)
    {
        return Session::name($name);
    }

    /**
     * Start a session manually
     */
    public static function sessionStart()
    {
        Session::start();
    }

    /**
     * Define available letters set to generated captcha
     *
     * @param string $letters
     *     A list of letters to use
     */
    public static function setLetters($letters)
    {
        Stringify::letters($letters);
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
    public static function setFont($font)
    {
        Font::set($font);
    }

    /**
     * Add a font to available fonts to generated captcha
     *
     * @param string|array $font
     *     A font or fonts array to use
     *
     * @throws Exception
     *     If font file not exists
     */
    public static function addFont($font)
    {
        Font::add($font);
    }

    /**
     * Set the background color
     *
     * @param integer $r
     *     Color red value
     *
     * @param integer $g
     *     Color green value
     *
     * @param integer $b
     *     Color blue value
     */
    public static function setBackground($r, $g, $b)
    {
        Image::background($r, $g, $b);
    }

    /**
     * Set image padding
     *
     * @param integer|float $padding
     *     Image padding value.
     *     Integer is used as fixed pixels and float will be used as percent.
     */
    public static function setPadding($padding)
    {
        Image::padding($padding);
    }

    /**
     * Set image points and lines noise
     *
     * @param integer|array $points
     *     Integer is used as fixed points number and array as a min and max values.
     *
     * @param integer|array $lines
     *     Integer is used as fixed lines number and array as a min and max values.
     */
    public static function setNoise($points, $lines)
    {
        Image::noise($points, $lines);
    }

    /**
     * Returns the base64 image source to use as "<img src" value
     *
     * @param integer|array $count
     *     Integer is used as fixed letters number and array as a min and max values.
     *
     * @param integer $width
     *     Image width
     *
     * @param integer $height
     *     Image height
     *
     * @return string
     */
    public static function source($count, $width, $height)
    {
        Session::set($string = Stringify::get($count));

        $image = new Image($string, $width, $height);

        return $image->base64();
    }

    /**
     * Returns the "<img src" tag with image in base64 format
     *
     * @param integer|array $count
     *     Integer is used as fixed letters number and array as a min and max values.
     *
     * @param integer $width
     *     Image width
     *
     * @param integer $height
     *     Image height
     *
     * @param array $attr
     *     Optionals attributes to add to img tag
     *
     * @return string
     */
    public static function img($count, $width, $height, array $attr = array())
    {
        return self::tag(array_merge(array(
            'img',
            'src' => self::source($count, $width, $height),
            'width' => $width,
            'height' => $height,
        ), $attr));
    }

    /**
     * Returns the <input type="text" tag
     *
     * @param array $attr
     *     Optionals attributes to add to input tag
     *
     * @return string
     */
    public static function input(array $attr = array())
    {
        return self::tag(array_merge(array(
            'input',
            'type' => 'text',
            'name' => Session::name(),
            'required'
        ), $attr));
    }

    /**
     * Returns the validation result
     *
     * @return boolean
     */
    public static function check()
    {
        return Session::check();
    }

    /**
     * Generate a html tag from array attributes
     *
     * @param array $attr
     *     Attributes to add to tag
     *
     * @return string
     */
    private static function tag(array $attr)
    {
        $html = '<';

        foreach ($attr as $key => $value) {
            if (is_numeric($key)) {
                $html .= $value.' ';
            } else {
                $html .= $key.'="'.$value.'" ';
            }
        }

        return trim($html).' />';
    }
}
