## Twig Extension(s)

[![Latest Stable Version](https://poser.pugx.org/asmaster/twig-extension/v/stable)](https://packagist.org/packages/asmaster/twig-extension)
[![License](https://img.shields.io/packagist/l/asmaster/twig-extension.svg)](https://github.com/AlexMasterov/twig-extension/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/AlexMasterov/twig-extension.svg)](https://travis-ci.org/AlexMasterov/twig-extension)
[![Code Coverage](https://scrutinizer-ci.com/g/AlexMasterov/twig-extension/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/AlexMasterov/twig-extension/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AlexMasterov/twig-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AlexMasterov/twig-extension/?branch=master)

A collection of custom extensions for the [Twig template engine](http://twig.sensiolabs.org/).

## Extensions

[`Psr7UriExtension`](https://github.com/AlexMasterov/twig-extension/blob/master/src/Psr7UriExtension.php) — provides useful functions using [PSR-7 HTTP message interface](http://www.php-fig.org/psr/psr-7/).

| Function        | Description                                                       |
|-----------------|-------------------------------------------------------------------|
| `absolute_url`  | Generate an absolute URL for the given absolute or relative path  |
| `relative_path` | Generate a relative path based on the current path of the URI     |

## Installation

The suggested installation method is via [composer](https://getcomposer.org/):
```sh
composer require asmaster/twig-extension
```

## Configuration
To activate an extension you need to register it into the Twig environment:
```php
/*
* @var $twig    Twig_Environment
* @var $request ServerRequestInterface
*/
$twig->addExtension(
    new Asmaster\TwigExtension\Psr7UriExtension(ServerRequestInterface $request)
);
```
The example of registering the extension using [Auryn](https://github.com/rdlowrey/auryn) and [Diactoros](https://github.com/zendframework/zend-diactoros):
```php
$injector = new Auryn\Injector;
$injector->alias(
    Psr\Http\Message\ServerRequestInterface::class,
    Zend\Diactoros\ServerRequest::class
);

/*
* @var $twig Twig_Environment
*/
$twig->addExtension(
    $injector->make(Asmaster\TwigExtension\Psr7UriExtension::class)
);
```
