<?php
use Eusonlito\Captcha;

Captcha\Captcha::sessionStart();

class Tests extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $_POST[Captcha\Captcha::sessionName()] = 'AAAA';
    }

    public function checkTrue()
    {
        $session = Captcha\Captcha::sessionName();

        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertTrue(Captcha\Captcha::check());

        $this->assertNotTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));
    }

    public function checkFalse()
    {
        $session = Captcha\Captcha::sessionName();

        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));
    }

    public function testLoad()
    {
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertNotTrue(empty($_SESSION[Captcha\Captcha::sessionName()]));
    }

    public function testTrue()
    {
        Captcha\Captcha::setLetters('AAAAAA');

        $this->assertTrue(strpos(Captcha\Captcha::source(4, 385, 90), 'data:image/png;base64,') === 0);

        $this->checkTrue();
    }

    public function testFalse()
    {
        Captcha\Captcha::setLetters('000000');

        $this->assertTrue(strpos(Captcha\Captcha::source(4, 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();
    }

    public function testAll()
    {
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();
    }

    public function testImg()
    {
        $img = Captcha\Captcha::img(array(5, 6), 385, 90);

        $this->assertTrue(preg_match('#^<img src="data:image/png;base64,[^"]+" width="385" height="90" />$#', $img) === 1);

        $this->checkFalse();

        $img = Captcha\Captcha::img(array(5, 6), 385, 90, array(
            'class' => 'img-responsive'
        ));

        $this->assertTrue(preg_match('#^<img src="data:image/png;base64,[^"]+" width="385" height="90" class="img-responsive" />$#', $img) === 1);

        $this->checkFalse();
    }

    public function testInput()
    {
        $session = Captcha\Captcha::sessionName();
        $input = Captcha\Captcha::input();

        $this->assertTrue(preg_match('#^<input type="text" name="'.$session.'" required />$#', $input) === 1);

        $input = Captcha\Captcha::input(array(
            'class' => 'form-control'
        ));

        $this->assertTrue(preg_match('#^<input type="text" name="'.$session.'" required class="form-control" />$#', $input) === 1);
    }

    public function testSetFont()
    {
        Captcha\Captcha::setFont(__DIR__.'/../fonts/couture-bold.ttf');

        $this->assertTrue(count(Captcha\Font::get()) === 1);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();

        Captcha\Captcha::setFont(array(
            __DIR__.'/../fonts/couture-bold.ttf',
            __DIR__.'/../fonts/brush-lettering-one.ttf'
        ));

        $this->assertTrue(count(Captcha\Font::get()) === 2);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();

        Captcha\Captcha::addFont(__DIR__.'/../fonts/sketch-match.ttf');

        $this->assertTrue(count(Captcha\Font::get()) === 3);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();

        $this->setExpectedException('Exception');

        Captcha\Captcha::setFont(__DIR__.'/../fonts/NOT-EXISTS.ttf');
    }

    public function testSetBackground()
    {
        Captcha\Captcha::setBackground(array(255, 255, 255));

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();

        Captcha\Captcha::setBackground('white');

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();

        Captcha\Captcha::setBackground('#FF00FF');

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();

        Captcha\Captcha::setBackground('FF00FF');

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();

        Captcha\Captcha::setBackground('transparent');

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();
    }

    public function testSetColor()
    {
        Captcha\Captcha::setColor(array(10, 10, 10));

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();

        Captcha\Captcha::setColor('white');

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();

        Captcha\Captcha::setColor('#FF00FF');

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();

        Captcha\Captcha::setColor('FF00FF');

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();
    }

    public function testSetPadding()
    {
        Captcha\Captcha::setPadding(50);
        Captcha\Captcha::setPadding(0.5);

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();
    }

    public function testSetNoise()
    {
        Captcha\Captcha::setNoise(10, 10);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        Captcha\Captcha::setNoise(array(1, 10), 10);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        Captcha\Captcha::setNoise(10, array(1, 10));
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        Captcha\Captcha::setNoise(array(1, 10), array(1, 10));
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        Captcha\Captcha::setNoise(null, array(1, 10));
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        Captcha\Captcha::setNoise(array(1, 10), null);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
    }

    public function testSetLetters()
    {
        Captcha\Captcha::setLetters('AAAAAA');

        $this->assertTrue(strpos(Captcha\Captcha::source(4, 385, 90), 'data:image/png;base64,') === 0);

        $this->checkTrue();

        Captcha\Captcha::setLetters('000000');

        $this->assertTrue(strpos(Captcha\Captcha::source(4, 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();
    }

    public function testSessionName()
    {
        Captcha\Captcha::sessionName('captcha-test');

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);

        $this->checkFalse();
    }
}
