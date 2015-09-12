<?php
namespace Eusonlito\Captcha;

class Image
{
    private $image;
    private static $background = array(255, 255, 255);

    public function __construct($text, $width, $height)
    {
        $this->image = imagecreatetruecolor($width, $height);

        $this->setBackground($width, $height);
        $this->setText($text, $width, $height);

        return $this;
    }

    public static function background($r, $g, $b)
    {
        self::$background = array($r, $g, $b);
    }

    public function base64()
    {
        ob_start();

        imagepng($this->image);

        $string = ob_get_contents();

        ob_end_clean();

        return 'data:image/png;base64,'.base64_encode($string);
    }

    private function getTextSize(array $data)
    {
        $rect = imagettfbbox($data['size'], $data['angle'], $data['font'], $data['text']);

        $ascent = abs($rect[7]);
        $descent = abs($rect[1]);
        $width = abs($rect[0]) + abs($rect[2]);

        return array(
            'ascent'  => $ascent,
            'descent' => $descent,
            'width' => $width,
            'height' => ($ascent + $descent)
        );
    }

    private function setBackground($width, $height)
    {
        list($r, $g, $b) = self::$background;

        imagefilledrectangle($this->image, 0, 0, $width, $height, imagecolorallocate($this->image, $r, $g, $b));
    }

    private function getLetters($text, $strlen, $fontSize, $allFonts, $width, $height, $maxWidth, $maxHeight)
    {
        $letters = array();
        $textWidth = 0;

        for ($i = 0; $i < $strlen; $i++) {
            $textSize = self::getTextSize(array(
                'text' => $text[$i],
                'size' => $fontSize,
                'angle' => ($angle = rand(-20, 20)),
                'font' => ($font = $allFonts[array_rand($allFonts)])
            ));

            $textWidth += $textSize['width'];

            if (($textWidth > $maxWidth) || ($textSize['height'] > $maxHeight)) {
                return false;
            }

            $letters[] = array(
                'letter' => $text[$i],
                'font' => $font,
                'width' => $textSize['width'],
                'y' => ((($height / 2) - ($textSize['height'] / 2)) + $textSize['ascent']),
                'angle' => $angle,
                'size' => $fontSize
            );
        }

        return $letters;
    }

    private function setText($text, $width, $height)
    {
        $strlen = strlen($text);
        $allFonts = Font::get();
        $maxWidth = $width - ($width * 0.2);
        $maxHeight = $height - ($height * 0.2);
        $letters = $previous = array();
        $fontSize = 14;

        while (true) {
            $letters = $this->getLetters($text, $strlen, $fontSize += 2, $allFonts, $width, $height, $maxWidth, $maxHeight);

            if ($letters === false) {
                $letters = $previous;
                break;
            }

            $previous = $letters;
        }

        $x = intval(($width - array_sum(array_map(function ($row) {
            return $row['width'];
        }, $letters))) / 2);

        foreach ($letters as $letter) {
            imagettftext(
                $this->image,
                $letter['size'],
                $letter['angle'],
                $x,
                $letter['y'],
                imagecolorallocate($this->image, 115, 115, 115),
                $letter['font'],
                $letter['letter']
            );

            $x += $letter['width'];
        }
    }
}
