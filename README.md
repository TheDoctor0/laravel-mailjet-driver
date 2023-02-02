# Laravel Mailjet Driver

[![Build Status](https://travis-ci.org/TheDoctor0/laravel-mailjet-driver.svg?branch=master)](https://travis-ci.org/TheDoctor0/laravel-mailjet-driver)
[![Packagist](https://img.shields.io/packagist/v/TheDoctor0/laravel-mailjet-driver.svg)](https://packagist.org/packages/TheDoctor0/laravel-mailjet-driver)
[![Packagist](https://img.shields.io/packagist/dt/TheDoctor0/laravel-mailjet-driver.svg)](https://packagist.org/packages/TheDoctor0/laravel-mailjet-driver)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/TheDoctor0/laravel-mailjet-driver/blob/master/LICENSE.md)

Laravel mail driver package for [Mailjet](https://www.mailjet.com/). It also serves as a wrapper for [Mailjet API v3](https://github.com/mailjet/mailjet-apiv3-php).

## Installation

For Laravel 9.x and 10.x which also requires Symfony Mailer:
```
composer require thedoctor0/laravel-mailjet-driver symfony/http-client
```

In other cases:
```
composer require thedoctor0/laravel-mailjet-driver:1.0.4
```

## Configuration

You can find your Mailjet API key / secret [here](https://app.mailjet.com/account/api_keys).

Change default mail driver and add new variables to your **.env** file:

```php
MAIL_DRIVER=mailjet

MAILJET_APIKEY=YOUR_APIKEY
MAILJET_APISECRET=YOUR_APISECRET
```

Add section to the **config/services.php** file:

```php
'mailjet' => [
    'key' => env('MAILJET_APIKEY'),
    'secret' => env('MAILJET_APISECRET'),
],
```

Make sure that in **config/mail.php** as mail sender address you are using an authorised email address configured on your Mailjet account. 

Your available Mailjet email addresses and domains can be managed [here](https://app.mailjet.com/account/sender).

For Laravel 7+ you also need to specify new available mail driver in **config/mail.php**:

```php
'mailers' => [
    ...

    'mailjet' => [
        'transport' => 'mailjet',
    ],
],
```

### Optional configuration

You can add full configuration for [MailjetClient](https://github.com/mailjet/mailjet-apiv3-php) to the **config/services.php** file.

* `transactional`: settings to sendAPI client
* `common`: setting to MailjetClient accessible through the Facade Mailjet
* `v4`: setting used for some DataProvider`s

```php
'mailjet' => [
    'key' => env('MAILJET_APIKEY'),
    'secret' => env('MAILJET_APISECRET'),
    'transactional' => [
        'call' => true,
        'options' => [
            'url' => 'api.mailjet.com',
            'version' => 'v3.1',
            'call' => true,
            'secured' => true
        ],
    ],
    'common' => [
        'call' => true,
        'options' => [
            'url' => 'api.mailjet.com',
            'version' => 'v3',
            'call' => true,
            'secured' => true
        ],
    ],
    'v4' => [
        'call' => true,
        'options' => [
            'url' => 'api.mailjet.com',
            'version' => 'v4',
            'call' => true,
            'secured' => true
        ]
    ],
],
```

## API Wrapper usage

In order to API wrapper from this package, you first need to import Mailjet Facade in your code:
```
use Mailjet\LaravelMailjet\Facades\Mailjet;
```

Then you can use one of the methods available in the **MailjetServices** class.

#### Low level API methods:

* `Mailjet::get($resource, $args, $options)`
* `Mailjet::post($resource, $args, $options)`
* `Mailjet::put($resource, $args, $options)`
* `Mailjet::delete($resource, $args, $options)`

#### High level API methods:

* `Mailjet::getAllLists($filters)`
* `Mailjet::createList($body)`
* `Mailjet::getListRecipients($filters)`
* `Mailjet::getSingleContact($id)`
* `Mailjet::createContact($body)`
* `Mailjet::createListRecipient($body)`
* `Mailjet::editListrecipient($id, $body)`

All method return `Mailjet\Response` or throw a `MailjetException` in case of any API error.

You can also get the Mailjet API client with the method `getClient()` and make your own custom request to Mailjet API.

For more information please refer to the official [Mailjet API documentation](https://dev.mailjet.com/email/reference/).
