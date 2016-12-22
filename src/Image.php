<?php
namespace Eusonlito\Captcha;

/**
 * Class to manage image
 */
class Image
{
    private $image;
    private static $background = array(255, 255, 255, 1);
    private static $color = array(115, 115, 115, 1);
    private static $padding = 0.4;
    private static $noisePoints;
    private static $noiseLines;

    /**
     * Load the class and process the captcha
     *
     * @param string $text
     *     Text string to catpcha image
     *
     * @param integer $width
     *     Image width
     *
     * @param integer $height
     *     Image height
     *
     * @return object
     */
    public function __construct($text, $width, $height)
    {
        $this->image = imagecreatetruecolor($width, $height);

        $this->setBackground($width, $height);
        $this->setText($text, $width, $height);

        return $this;
    }

    /**
     * Set the background color
     *
     * @param mixed $value
     *     RGB[array] / HEX[string] / Name[string] color value
     */
    public static function background($value)
    {
        self::$background = Color::toRGBA($value);
    }

    /**
     * Set the font color
     *
     * @param mixed $value
     *     RGB[array] / HEX[string] / Name[string] color value
     */
    public static function color($value)
    {
        self::$color = Color::toRGBA($value);
    }

    /**
     * Set image padding
     *
     * @param integer|float $padding
     *     Image padding value.
     *     Integer is used as fixed pixels and float will be used as percent.
     */
    public static function padding($padding)
    {
        self::$padding = is_int($padding) ? ($padding * 2) : (float)$padding;
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
    public static function noise($points, $lines)
    {
        self::$noisePoints = $points;
        self::$noiseLines = $lines;
    }

    /**
     * Returns the base64 image source
     *
     * @return string
     */
    public function base64()
    {
        ob_start();

        imagepng($this->image);

        $string = ob_get_contents();

        ob_end_clean();

        return 'data:image/png;base64,'.base64_encode($string);
    }

    /**
     * Calculate text size using font, angle, size and text values
     *
     * @param array $data
     *     The data set (font, angle, size and text) to calculate the size
     *
     * @return array
     */
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

    /**
     * Set the background color
     *
     * @param integer $width
     *     Image width
     *
     * @param integer $height
     *     Image height
     */
    private function setBackground($width, $height)
    {
        list($r, $g, $b, $a) = self::$background;

        $color = imagecolorallocate($this->image, $r, $g, $b);

        if ($a === 0) {
            imagecolortransparent($this->image, $color);
        }

        imagefilledrectangle($this->image, 0, 0, $width, $height, $color);
    }

    /**
     * Check if letters fit into the image limits
     *
     * @param string $text
     *     Text to use
     *
     * @param integer $strlen
     *     Lenght of the text
     *
     * @param integer $fontSize
     *     Font size to check if fit
     *
     * @param array $allFonts
     *     The fonts collection
     *
     * @param integer $width
     *     Image width
     *
     * @param integer $height
     *     Image height
     *
     * @param integer $maxWidth
     *     Max image width to use
     *
     * @param integer $maxHeight
     *     Max image hieght to use
     *
     * @return array
     */
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

    /**
     * Set the text into the image
     *
     * @param string $text
     *     The captcha text
     *
     * @param integer $width
     *     Image width
     *
     * @param integer $height
     *     Image height
     */
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
                imagecolorallocate($this->image, self::$color[0], self::$color[1], self::$color[2]),
                $letter['font'],
                $letter['letter']
            );

            $x += $letter['width'];
        }
    }

    /**
     * Generate noise points
     *
     * @param integer $width
     *     Image width
     *
     * @param integer $height
     *     Image height
     */
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

    /**
     * Generate noise lines
     *
     * @param integer $width
     *     Image width
     *
     * @param integer $height
     *     Image height
     */
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

    /**
     * Generate a random color to use with image functions
     *
     * @return object
     */
    private function randomColor()
    {
        return imagecolorallocate($this->image, rand(150, 200), rand(150, 200), rand(150, 200));
    }

    /**
     * Generate a random float number
     *
     * @return float
     */
    private function frand()
    {
        return 0.0001 * mt_rand(0, 9999);
    }
}
