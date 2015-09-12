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

        return (new Image($string, $width, $height))->base64();
    }

    public static function img($count, $width, $height, array $attr = array())
    {
        return '<img src="'.self::source($count, $width, $height).'"'
            .' width="'.$width.'"'
            .' height="'.$height.'"'
            .' '.implode(' ', array_map(function($key, $value) {
                return $key.'="'.$value.'"';
            }, array_keys($attr), $attr))
            .'/>';
    }

    public static function input(array $attr = array())
    {
        return '<input type="text" name="'.Session::name().'"'
            .' '.implode(' ', array_map(function($key, $value) {
                return $key.'="'.$value.'"';
            }, array_keys($attr), $attr))
            .'/>';
    }

    public static function check()
    {
        return Session::check();
    }
}
