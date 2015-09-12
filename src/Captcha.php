<?php
namespace Eusonlito\Captcha;

class Captcha
{
    public static function sessionName($name = null)
    {
        return Session::name($name);
    }

    public static function sessionStart()
    {
        return Session::start();
    }

    public static function setLetters($letters)
    {
        return String::letters($letters);
    }

    public static function setFont($font)
    {
        return Font::set($font);
    }

    public static function addFont($font)
    {
        return Font::add($font);
    }

    public static function setBackground($r, $g, $b)
    {
        return Image::background($r, $g, $b);
    }

    public static function source($count, $width, $height)
    {
        Session::set($string = String::get($count));

        $image = new Image($string, $width, $height);

        return $image->base64();
    }

    public static function img($count, $width, $height, array $attr = array())
    {
        return self::tag(array_merge(array(
            'img',
            'src' => self::source($count, $width, $height),
            'width' => $width,
            'height' => $height,
        ), $attr));
    }

    public static function input(array $attr = array())
    {
        return self::tag(array_merge(array(
            'input',
            'type' => 'text',
            'name' => Session::name(),
            'required'
        ), $attr));
    }

    public static function check()
    {
        return Session::check();
    }

    private static function tag(array $attributes = array())
    {
        $html = '<';

        foreach ($attributes as $key => $value) {
            if (is_numeric($key)) {
                $html .= $value.' ';
            } else {
                $html .= $key.'="'.$value.'" ';
            }
        }

        return trim($html).' />';
    }
}
