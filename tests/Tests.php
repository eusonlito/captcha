<?php
use Eusonlito\Captcha;

Captcha\Captcha::sessionStart();

class Tests extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $_POST[Captcha\Captcha::sessionName()] = 'AAAA';
    }

    public function testLoad()
    {
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertNotTrue(empty($_SESSION[Captcha\Captcha::sessionName()]));
    }

    public function testTrue()
    {
        $session = Captcha\Captcha::sessionName();

        Captcha\Captcha::setLetters('AAAAAA');

        $this->assertTrue(strpos(Captcha\Captcha::source(4, 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertTrue(Captcha\Captcha::check());

        $this->assertNotTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));
    }

    public function testFalse()
    {
        Captcha\Captcha::setLetters('000000');

        $session = Captcha\Captcha::sessionName();

        $this->assertTrue(strpos(Captcha\Captcha::source(4, 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));
    }

    public function testAll()
    {
        $session = Captcha\Captcha::sessionName();

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));
    }

    public function testImg()
    {
        $session = Captcha\Captcha::sessionName();

        $img = Captcha\Captcha::img(array(5, 6), 385, 90);

        $this->assertTrue(preg_match('#^<img src="data:image/png;base64,[^"]+" width="385" height="90" />$#', $img) === 1);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));

        $img = Captcha\Captcha::img(array(5, 6), 385, 90, array(
            'class' => 'img-responsive'
        ));

        $this->assertTrue(preg_match('#^<img src="data:image/png;base64,[^"]+" width="385" height="90" class="img-responsive" />$#', $img) === 1);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));
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
        $session = Captcha\Captcha::sessionName();

        Captcha\Captcha::setFont(__DIR__.'/../fonts/couture-bold.ttf');

        $this->assertTrue(count(Captcha\Font::get()) === 1);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));

        Captcha\Captcha::setFont(array(
            __DIR__.'/../fonts/couture-bold.ttf',
            __DIR__.'/../fonts/brush-lettering-one.ttf'
        ));

        $this->assertTrue(count(Captcha\Font::get()) === 2);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));

        Captcha\Captcha::addFont(__DIR__.'/../fonts/sketch-match.ttf');

        $this->assertTrue(count(Captcha\Font::get()) === 3);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));

        $this->setExpectedException('Exception');

        Captcha\Captcha::setFont(__DIR__.'/../fonts/NOT-EXISTS.ttf');
    }

    public function testSetBackground()
    {
        $session = Captcha\Captcha::sessionName();

        Captcha\Captcha::setBackground(255, 255, 255);

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));
    }

    public function testSetColor()
    {
        $session = Captcha\Captcha::sessionName();

        Captcha\Captcha::setColor(10, 10, 10);

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));
    }

    public function testSetPadding()
    {
        $session = Captcha\Captcha::sessionName();

        Captcha\Captcha::setPadding(50);
        Captcha\Captcha::setPadding(0.5);

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));
    }

    public function testSetNoise()
    {
        $session = Captcha\Captcha::sessionName();

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
        $session = Captcha\Captcha::sessionName();

        Captcha\Captcha::setLetters('AAAAAA');

        $this->assertTrue(strpos(Captcha\Captcha::source(4, 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertTrue(Captcha\Captcha::check());

        $this->assertNotTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));

        Captcha\Captcha::setLetters('000000');

        $this->assertTrue(strpos(Captcha\Captcha::source(4, 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[$session]));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION[$session]));
        $this->assertNotTrue(isset($_POST[$session]));
    }

    public function testSessionName()
    {
        $session = Captcha\Captcha::sessionName();

        Captcha\Captcha::sessionName('captcha-test');

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION['captcha-test']));

        $this->assertFalse(Captcha\Captcha::check());

        $this->assertTrue(isset($_SESSION['captcha-test']));
        $this->assertNotTrue(isset($_POST['captcha-test']));
    }
}
