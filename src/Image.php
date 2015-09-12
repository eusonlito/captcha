<?php
namespace Eusonlito\Captcha;

class Image
{
    private $image;
    private static $background = array(255, 255, 255);
    private static $padding = 0.4;
    private static $noisePoints;
    private static $noiseLines;

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

    public static function padding($padding)
    {
        self::$padding = is_int($padding) ? ($padding * 2) : (float)$padding;
    }

    public static function noise($points, $lines)
    {
        self::$noisePoints = $points;
        self::$noiseLines = $lines;
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

        if (is_int(self::$padding)) {
            $maxWidth = $width - self::$padding;
            $maxHeight = $height - self::$padding;
        } else {
            $maxWidth = $width - ($width * self::$padding);
            $maxHeight = $height - ($height * self::$padding);
        }

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

        if (self::$noisePoints) {
            $this->noisePoints($width, $height);
        }

        if (self::$noiseLines) {
            $this->noiseLines($width, $height);
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

    private function noisePoints($width, $height)
    {
        $noise = $this->randomColor();

        if (is_array(self::$noisePoints)) {
            $points = rand(self::$noisePoints[0], self::$noisePoints[1]);
        } else {
            $points = (int)self::$noisePoints;
        }

        for ($i = 0; $i < $points; ++$i) {
            $size = mt_rand(2, 10);
            imagefilledarc($this->image, mt_rand(10, $width), mt_rand(10, $height), $size, $size, 0, 360, $noise, IMG_ARC_PIE);
        }
    }

    private function noiseLines($width, $height)
    {
        $noise = $this->randomColor();

        if (is_array(self::$noiseLines)) {
            $lines = rand(self::$noiseLines[0], self::$noiseLines[1]);
        } else {
            $lines = (int)self::$noiseLines;
        }

        for ($i = 0; $i < $lines; ++$i) {
            $x = $width * (1 + $i) / ($lines + 1);
            $x += (0.5 - $this->frand()) * $width / $lines;
            $y = mt_rand($height * 0.1, $height * 0.9);

            $theta = ($this->frand() - 0.5) * M_PI * 0.7;
            $len = mt_rand($width * 0.4, $width * 0.7);
            $lwid = mt_rand(0, 2);

            $k = ($this->frand() * 0.6) + 0.2;
            $k = $k * $k * 0.5;
            $phi = $this->frand() * 6.28;
            $step = 0.5;
            $dx = $step * cos($theta);
            $dy = $step * sin($theta);
            $n = $len / $step;
            $amp = 1.5 * $this->frand() / ($k + 5.0 / $len);
            $x0 = $x - (0.5 * $len * cos($theta));
            $y0 = $y - (0.5 * $len * sin($theta));

            for ($z = 0; $z < $n; ++$z) {
                $x = $x0 + $z * $dx + $amp * $dy * sin($k * $z * $step + $phi);
                $y = $y0 + $z * $dy - $amp * $dx * sin($k * $z * $step + $phi);
                imagefilledrectangle($this->image, $x, $y, $x + $lwid, $y + $lwid, $noise);
            }
        }
    }

    private function randomColor()
    {
        return imagecolorallocate($this->image, rand(150, 200), rand(150, 200), rand(150, 200));
    }

    private function frand()
    {
        return 0.0001 * mt_rand(0, 9999);
    }
}
