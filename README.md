## Twig Extension(s)

[![Latest Stable Version](https://poser.pugx.org/asmaster/twig-extension/v/stable)](https://packagist.org/packages/asmaster/twig-extension)
[![License](https://img.shields.io/packagist/l/asmaster/twig-extension.svg)](https://github.com/AlexMasterov/twig-extension/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/AlexMasterov/twig-extension.svg)](https://travis-ci.org/AlexMasterov/twig-extension)
[![Code Coverage](https://scrutinizer-ci.com/g/AlexMasterov/twig-extension/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/AlexMasterov/twig-extension/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AlexMasterov/twig-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AlexMasterov/twig-extension/?branch=master)

A collection of custom extensions for the [Twig template engine](http://twig.sensiolabs.org/).

## Extensions

 [Psr7UriExtension](https://github.com/AlexMasterov/twig-extension/blob/master/src/Psr7UriExtension.php) — provides useful functions for generating URLs using [PSR-7 HTTP message interface](http://www.php-fig.org/psr/psr-7/).

Below is an example of use if the current path is _http://example.com/user/mone_:
 ```twig
{{ absolute_url('images/logo.png') }}  // http://example.com/user/mone/images/logo.png
{{ relative_url('/images/logo.png') }} // ../images/logo.png
```

## Installation

The suggested installation method is via [composer](https://getcomposer.org/):
```sh
composer require asmaster/twig-extension
```

## Configuration
To activate the extension need to register it into the Twig environment:
```php
/*
* @var $twig    Twig_Environment
* @var $request PSR7\ServerRequest
*/
$twig->addExtension(new Asmaster\TwigExtension\Psr7UriExtension($request));
```
The example of registering the extension using [Auryn](https://github.com/rdlowrey/auryn) (dependency injector) and [Diactoros](https://github.com/zendframework/zend-diactoros) (PSR-7 HTTP Message implementation):
```php
$injector = new Auryn\Injector();
$injector->alias(
    'Psr\Http\Message\ServerRequestInterface',
    'Zend\Diactoros\ServerRequest'
);

/*
* @var $twig Twig_Environment
*/
$twig->addExtension(
    $injector->make(Asmaster\TwigExtension\Psr7UriExtension::class)
);
```
