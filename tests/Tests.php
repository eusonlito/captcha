<?php
use Eusonlito\Captcha;

Captcha\Captcha::sessionStart();

class Tests extends PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertNotTrue(empty($_SESSION[Captcha\Captcha::sessionName()]));
    }

    public function testCheck()
    {
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertNotTrue(isset($_POST[Captcha\Captcha::sessionName()]));
    }

    public function testAll()
    {
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertNotTrue(isset($_POST[Captcha\Captcha::sessionName()]));
    }

    public function testImg()
    {
        $img = Captcha\Captcha::img(array(5, 6), 385, 90);

        $this->assertTrue(strpos($img, '<img src=') === 0);
        $this->assertTrue(strstr($img, 'data:image/png;base64,') ? true : false);
        $this->assertTrue(!empty($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertNotTrue(isset($_POST[Captcha\Captcha::sessionName()]));

        $img = Captcha\Captcha::img(array(5, 6), 385, 90, array(
            'class' => 'img-responsive'
        ));

        $this->assertTrue(strpos($img, '<img src=') === 0);
        $this->assertTrue(strstr($img, 'class="img-responsive"') ? true : false);
        $this->assertTrue(strstr($img, 'data:image/png;base64,') ? true : false);
        $this->assertTrue(!empty($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertNotTrue(isset($_POST[Captcha\Captcha::sessionName()]));
    }

    public function testInput()
    {
        $input = Captcha\Captcha::input();

        $this->assertTrue(strpos($input, '<input type="text"') === 0);

        $input = Captcha\Captcha::input(array(
            'class' => 'form-control'
        ));

        $this->assertTrue(strpos($input, '<input type="text"') === 0);
        $this->assertTrue(strstr($input, 'class="form-control"') ? true : false);
    }

    public function testSetFont()
    {
        Captcha\Captcha::setFont(__DIR__.'/../fonts/couture-bold.ttf');

        $this->assertTrue(count(Captcha\Font::get()) === 1);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertNotTrue(isset($_POST[Captcha\Captcha::sessionName()]));

        Captcha\Captcha::setFont(array(
            __DIR__.'/../fonts/couture-bold.ttf',
            __DIR__.'/../fonts/brush-lettering-one.ttf'
        ));

        $this->assertTrue(count(Captcha\Font::get()) === 2);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertNotTrue(isset($_POST[Captcha\Captcha::sessionName()]));

        Captcha\Captcha::addFont(__DIR__.'/../fonts/sketch-match.ttf');

        $this->assertTrue(count(Captcha\Font::get()) === 3);
        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertNotTrue(isset($_POST[Captcha\Captcha::sessionName()]));

        $this->setExpectedException('Exception');

        Captcha\Captcha::setFont(__DIR__.'/../fonts/NOT-EXISTS.ttf');
    }

    public function testSetBackground()
    {
        Captcha\Captcha::setBackground(255, 255, 255);

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertNotTrue(isset($_POST[Captcha\Captcha::sessionName()]));
    }

    public function testSetLetters()
    {
        $_POST[Captcha\Captcha::sessionName()] = 'AAAA';

        Captcha\Captcha::setLetters('AAAAAA');

        $this->assertTrue(strpos(Captcha\Captcha::source(4, 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertTrue(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertNotTrue(isset($_POST[Captcha\Captcha::sessionName()]));

        Captcha\Captcha::setLetters('000000');

        $this->assertTrue(strpos(Captcha\Captcha::source(4, 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION[Captcha\Captcha::sessionName()]));
        $this->assertNotTrue(isset($_POST[Captcha\Captcha::sessionName()]));
    }

    public function testSessionName()
    {
        Captcha\Captcha::sessionName('captcha-test');

        $this->assertTrue(strpos(Captcha\Captcha::source(array(5, 6), 385, 90), 'data:image/png;base64,') === 0);
        $this->assertTrue(!empty($_SESSION['captcha-test']));
        $this->assertFalse(Captcha\Captcha::check());
        $this->assertNotTrue(isset($_SESSION['captcha-test']));
        $this->assertNotTrue(isset($_POST['captcha-test']));
    }
}
