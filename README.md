# Easy Captcha :)

[![Build Status](https://travis-ci.org/eusonlito/captcha.svg?branch=master)](https://travis-ci.org/eusonlito/captcha)
[![Latest Stable Version](https://poser.pugx.org/eusonlito/captcha/v/stable.png)](https://packagist.org/packages/eusonlito/captcha)
[![Total Downloads](https://poser.pugx.org/eusonlito/captcha/downloads.png)](https://packagist.org/packages/eusonlito/captcha)
[![License](https://poser.pugx.org/eusonlito/captcha/license.png)](https://packagist.org/packages/eusonlito/captcha)

This is a configurable and easy-to-implement captcha package.

## Installation

Begin by installing this package through Composer.

```js
{
    "require": {
        "eusonlito/captcha": "master-dev"
    }
}
```

## Usage

### Template

```php
use Eusonlito\Captcha\Captcha

<div class="form-group">
    <img src="<?= Captcha::source($LETTERS_COUNT, $WIDTH, $HEIGHT) ?>" />
    <input type="text" name="<?= Captcha::sessionName() ?>" value="" />

    ... or ...

    <?= Captcha::img($LETTERS_COUNT, $WIDTH, $HEIGHT) ?>
    <input type="text" name="<?= Captcha::sessionName() ?>" value="" />

    ... or ...

    <?= Captcha::img($LETTERS_COUNT, $WIDTH, $HEIGHT) ?>
    <?= Captcha::input(array('class' => 'form-control')) ?>

    ... or ...

    <?= Captcha::img(array($LETTERS_MIN, $LETTERS_MAX) $WIDTH, $HEIGHT) ?>
    <?= Captcha::input(array('class' => 'form-control')) ?>
</div>
```

If you are using a non session environment, you must add `Captcha::sessionStart()` to your controller.

### Checking

```php
use Eusonlito\Captcha\Captcha

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