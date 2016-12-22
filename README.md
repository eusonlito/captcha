# Easy Captcha :)

[![Build Status](https://travis-ci.org/eusonlito/captcha.svg?branch=master)](https://travis-ci.org/eusonlito/captcha)
[![Latest Stable Version](https://poser.pugx.org/eusonlito/captcha/v/stable.png)](https://packagist.org/packages/eusonlito/captcha)
[![Total Downloads](https://poser.pugx.org/eusonlito/captcha/downloads.png)](https://packagist.org/packages/eusonlito/captcha)
[![License](https://poser.pugx.org/eusonlito/captcha/license.png)](https://packagist.org/packages/eusonlito/captcha)

A new simple and easy-to-implement captcha package.

## Installation with Composer

```json
{
    "require": {
        "eusonlito/captcha": "1.0.*"
    }
}
```

## Demos

![Default Captcha](https://eusonlito.github.io/captcha/assets/images/multiple-fonts.png)
![Only one font](https://eusonlito.github.io/captcha/assets/images/one-font.png)
![Large Captcha](https://eusonlito.github.io/captcha/assets/images/large.png)
![Short Captcha](https://eusonlito.github.io/captcha/assets/images/short.png)
![With Background](https://eusonlito.github.io/captcha/assets/images/background.png)
![Custom Letters](https://eusonlito.github.io/captcha/assets/images/letters.png)
![With Noise](https://eusonlito.github.io/captcha/assets/images/noise.png)
![Only Noise Points](https://eusonlito.github.io/captcha/assets/images/noise-points.png)
![Only Noise Lines](https://eusonlito.github.io/captcha/assets/images/noise-lines.png)

## Usage

### Template

```php
<?php use Eusonlito\Captcha\Captcha; ?>

<div class="form-group">
    <img src="<?= Captcha::source($LETTERS_COUNT, $WIDTH, $HEIGHT); ?>" class="img-responsive" />
    <input type="text" name="<?= Captcha::sessionName(); ?>" value="" class="form-control" />

    ... or ...

    <?= Captcha::img($LETTERS_COUNT, $WIDTH, $HEIGHT); ?>
    <input type="text" name="<?= Captcha::sessionName(); ?>" value="" class="form-control" />

    ... or ...

    <?= Captcha::img($LETTERS_COUNT, $WIDTH, $HEIGHT, array('class' => 'img-responsive')); ?>
    <input type="text" name="<?= Captcha::sessionName(); ?>" value="" class="form-control" />

    ... or ...

    <?= Captcha::img($LETTERS_COUNT, $WIDTH, $HEIGHT); ?>
    <?= Captcha::input(array('class' => 'form-control')); ?>

    ... or ...

    <?= Captcha::img(array($LETTERS_MIN, $LETTERS_MAX) $WIDTH, $HEIGHT); ?>
    <?= Captcha::input(array('class' => 'form-control')); ?>
</div>
```

If you are using an environment without sessions, you must add `Captcha::sessionStart()` before any html output (Controller).

### Checking

```php
<?php
use Eusonlito\Captcha\Captcha;

function validate()
{
    if (!Captcha::check()) {
        throw new Exception('Captcha text is not correct');
    }
}
```

That's all!

### Laravel Usage

```php
<?php
# config/app.php

return [
    ...

    'aliases' => [
        ...

        'Captcha' => 'Eusonlito\Captcha\Captcha',

        ...
    ]
];
```

Now you will have a `Captcha` class available on your controllers and views.

## Print Options

```php
<?php
use Eusonlito\Captcha\Captcha;

# Simple usage with fixed word length
Captcha::source($LETTERS_COUNT, $WIDTH, $HEIGHT); # Print base64 source image code

# Define min and max word length
Captcha::source(array($LETTERS_MIN, $LETTERS_MAX), $WIDTH, $HEIGHT); # Print base64 source image code

# Same using img tag
Captcha::img($LETTERS_COUNT, $WIDTH, $HEIGHT); # Print img tag
Captcha::img(array($LETTERS_MIN, $LETTERS_MAX), $WIDTH, $HEIGHT); # Print img tag

# Img tag with parameters
Captcha::img($LETTERS_COUNT, $WIDTH, $HEIGHT, array('class' => 'img-responsive')); # Print img tag with class attribute

# Simple input tag print
Captcha::input(); # Print input tag

# Input tag with parameters
Captcha::input(array('class' => 'form-control')); # Print input tag with class attribute
```

## Custom Setup

All custom settings will be defined before `img`, `source` or `check` methods calls.

```php
<?php
use Eusonlito\Captcha\Captcha;

# Define a unique font to use (only .ttf)
Captcha::setFont(__DIR__.'/../fonts/couture-bold.ttf'); # string or array

# Add fonts to repository (only .ttf)
Captcha::addFont(array(
    __DIR__.'/../fonts/couture-bold.ttf',
    __DIR__.'/../fonts/brush-lettering-one.ttf'
));

# Set custom rgb background. Default is 255, 255, 255
Captcha::setBackground([120, 120, 120]);

# Set custom hex background.
Captcha::setBackground('#FFF000');

# Set transparent background.
Captcha::setBackground('transparent');

# Set custom rgb font color. Default is 115, 115, 115
Captcha::setColor([50, 50, 50]);

# Set custom hex color.
Captcha::setColor('#000FFF');

# Set custom padding to captcha image (approximate). Default is 0.4
Captcha::setPadding(20); // Fixed value in pixels
Captcha::setPadding(0.4); // Percent value

# Set image noise. Default is without noise
Captcha::setNoise($POINTS, $LINES); // Fixed points and lines noise
Captcha::setNoise(array($POINTS_MIN, $POINTS_MAX), array($LINES_MIN, $LINES_MAX)); // Variable points and lines noise
Captcha::setNoise(null, array($LINES_MIN, $LINES_MAX)); // Avoid points noise
Captcha::setNoise(array($POINTS_MIN, $POINTS_MAX), null); // Avoid lines noise

# Set custom available letters. Default are 'ABCDEFGHJKLMNPRSTUVWXYZ'
Captcha::setLetters('ABCDE3456');

# Set custom session name captcha storage (captcha string is stored crypted). Default is 'captcha-string'
Captcha::sessionName('my-captcha');

# Enable session before use on non session environments
Captcha::sessionStart();
```

Enjoy!
