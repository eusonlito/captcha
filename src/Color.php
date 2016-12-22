<?php
namespace Eusonlito\Captcha;

use Exception;

/**
 * Class to manage colors
 */
class Color
{
    private static $names = array(
        'maroon' => '800000',
        'darkred' => '8b0000',
        'firebrick' => 'b22222',
        'red' => 'ff0000',
        'salmon' => 'fa8072',
        'tomato' => 'ff6347',
        'coral' => 'ff7f50',
        'orangered' => 'ff4500',
        'chocolate' => 'd2691e',
        'sandybrown' => 'f4a460',
        'darkorange' => 'ff8c00',
        'orange' => 'ffa500',
        'darkgoldenrod' => 'b8860b',
        'goldenrod' => 'daa520',
        'gold' => 'ffd700',
        'olive' => '808000',
        'yellow' => 'ffff00',
        'yellowgreen' => '9acd32',
        'greenyellow' => 'adff2f',
        'chartreuse' => '7fff00',
        'lawngreen' => '7cfc00',
        'green' => '008000',
        'lime' => '00ff00',
        'limegreen' => '32cd32',
        'springgreen' => '00ff7f',
        'mediumspringgreen' => '00fa9a',
        'turquoise' => '40e0d0',
        'lightseagreen' => '20b2aa',
        'mediumturquoise' => '48d1cc',
        'teal' => '008080',
        'darkcyan' => '008b8b',
        'aqua' => '00ffff',
        'cyan' => '00ffff',
        'darkturquoise' => '00ced1',
        'deepskyblue' => '00bfff',
        'dodgerblue' => '1e90ff',
        'royalblue' => '4169e1',
        'navy' => '000080',
        'darkblue' => '00008b',
        'mediumblue' => '0000cd',
        'blue' => '0000ff',
        'blueviolet' => '8a2be2',
        'darkorchid' => '9932cc',
        'darkviolet' => '9400d3',
        'purple' => '800080',
        'darkmagenta' => '8b008b',
        'fuchsia' => 'ff00ff',
        'magenta' => 'ff00ff',
        'mediumvioletred' => 'c71585',
        'deeppink' => 'ff1493',
        'hotpink' => 'ff69b4',
        'crimson' => 'dc143c',
        'brown' => 'a52a2a',
        'indianred' => 'cd5c5c',
        'rosybrown' => 'bc8f8f',
        'lightcoral' => 'f08080',
        'snow' => 'fffafa',
        'mistyrose' => 'ffe4e1',
        'darksalmon' => 'e9967a',
        'lightsalmon' => 'ffa07a',
        'sienna' => 'a0522d',
        'seashell' => 'fff5ee',
        'saddlebrown' => '8b4513',
        'peachpuff' => 'ffdab9',
        'peru' => 'cd853f',
        'linen' => 'faf0e6',
        'bisque' => 'ffe4c4',
        'burlywood' => 'deb887',
        'tan' => 'd2b48c',
        'antiquewhite' => 'faebd7',
        'navajowhite' => 'ffdead',
        'blanchedalmond' => 'ffebcd',
        'papayawhip' => 'ffefd5',
        'moccasin' => 'ffe4b5',
        'wheat' => 'f5deb3',
        'oldlace' => 'fdf5e6',
        'floralwhite' => 'fffaf0',
        'cornsilk' => 'fff8dc',
        'khaki' => 'f0e68c',
        'lemonchiffon' => 'fffacd',
        'palegoldenrod' => 'eee8aa',
        'darkkhaki' => 'bdb76b',
        'beige' => 'f5f5dc',
        'lightgoldenrodyellow' => 'fafad2',
        'lightyellow' => 'ffffe0',
        'ivory' => 'fffff0',
        'olivedrab' => '6b8e23',
        'darkolivegreen' => '556b2f',
        'darkseagreen' => '8fbc8f',
        'darkgreen' => '006400',
        'forestgreen' => '228b22',
        'lightgreen' => '90ee90',
        'palegreen' => '98fb98',
        'honeydew' => 'f0fff0',
        'seagreen' => '2e8b57',
        'mediumseagreen' => '3cb371',
        'mintcream' => 'f5fffa',
        'mediumaquamarine' => '66cdaa',
        'aquamarine' => '7fffd4',
        'darkslategray' => '2f4f4f',
        'paleturquoise' => 'afeeee',
        'lightcyan' => 'e0ffff',
        'azure' => 'f0ffff',
        'cadetblue' => '5f9ea0',
        'powderblue' => 'b0e0e6',
        'lightblue' => 'add8e6',
        'skyblue' => '87ceeb',
        'lightskyblue' => '87cefa',
        'steelblue' => '4682b4',
        'aliceblue' => 'f0f8ff',
        'slategray' => '708090',
        'lightslategray' => '778899',
        'lightsteelblue' => 'b0c4de',
        'cornflowerblue' => '6495ed',
        'lavender' => 'e6e6fa',
        'ghostwhite' => 'f8f8ff',
        'midnightblue' => '191970',
        'slateblue' => '6a5acd',
        'darkslateblue' => '483d8b',
        'mediumslateblue' => '7b68ee',
        'mediumpurple' => '9370db',
        'indigo' => '4b0082',
        'mediumorchid' => 'ba55d3',
        'plum' => 'dda0dd',
        'violet' => 'ee82ee',
        'thistle' => 'd8bfd8',
        'orchid' => 'da70d6',
        'lavenderblush' => 'fff0f5',
        'palevioletred' => 'db7093',
        'pink' => 'ffc0cb',
        'lightpink' => 'ffb6c1',
        'black' => '000000',
        'dimgray' => '696969',
        'gray' => '808080',
        'darkgray' => 'a9a9a9',
        'silver' => 'c0c0c0',
        'lightgrey' => 'd3d3d3',
        'gainsboro' => 'dcdcdc',
        'whitesmoke' => 'f5f5f5',
        'white' => 'ffffff'
    );

    public static function toRGBA($color)
    {
        if (is_array($color)) {
            return self::fromRGB($color);
        }

        $color = strtolower(trim($color));

        if ($color === 'transparent') {
            return array(0, 0, 0, 0);
        }

        if (isset(self::$names[$color])) {
            $color = self::$names[$color];
        }

        return self::fromHEX($color);
    }

    private static function fromRGB($color)
    {
        $len = count($color);

        if (($len > 4) || ($len < 3)) {
            throw new Exception('RGB color must have 3 or 4 values');
        }

        if ($len === 3) {
            $color[] = 100;
        }

        return array_map('intval', $color);
    }

    private static function fromHEX($color)
    {
        $color = str_split(str_replace('#', '', $color));
        $len = count($color);

        if (($len !== 6) && ($len !== 3))  {
            throw new Exception('HEX color must have 3 or 6 chars');
        }

        if ($len === 3) {
            $color = array($color[0], $color[0], $color[1], $color[1], $color[2], $color[2]);
        }

        $color = array_map('hexdec', array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]));
        $color[] = 100;

        return array_map('intval', $color);
    }
}
