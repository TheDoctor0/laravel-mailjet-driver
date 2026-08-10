# Laravel Mailjet Driver

[![Tests](https://github.com/TheDoctor0/laravel-mailjet-driver/actions/workflows/tests.yml/badge.svg)](https://github.com/TheDoctor0/laravel-mailjet-driver/actions/workflows/tests.yml)
[![Packagist](https://img.shields.io/packagist/v/TheDoctor0/laravel-mailjet-driver.svg)](https://packagist.org/packages/TheDoctor0/laravel-mailjet-driver)
[![Packagist](https://img.shields.io/packagist/dt/TheDoctor0/laravel-mailjet-driver.svg)](https://packagist.org/packages/TheDoctor0/laravel-mailjet-driver)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/TheDoctor0/laravel-mailjet-driver/blob/master/LICENSE.md)

A Laravel mail driver for [Mailjet](https://www.mailjet.com/) that also wraps the
[Mailjet API v3 PHP SDK](https://github.com/mailjet/mailjet-apiv3-php) — so you can
send mail through Laravel's `Mail` facade and reach Mailjet's REST, Send and SMS
APIs from the same package.

## Version support

| Package | Laravel   | PHP  |
|---------|-----------|------|
| 2.x     | 11.x – 13.x | 8.2+ |
| 1.x     | 9.x – 10.x  | 8.0+ |

## Installation

```bash
composer require thedoctor0/laravel-mailjet-driver symfony/http-client
```

The package registers itself automatically via Laravel's package discovery.

## Configuration

Grab your API key and secret from the [Mailjet API keys page](https://app.mailjet.com/account/api_keys),
then add them to your **.env** file and switch the mailer:

```dotenv
MAIL_MAILER=mailjet

MAILJET_APIKEY=your-api-key
MAILJET_APISECRET=your-api-secret
```

Add the credentials to **config/services.php**:

```php
'mailjet' => [
    'key' => env('MAILJET_APIKEY'),
    'secret' => env('MAILJET_APISECRET'),
],
```

Register the transport in **config/mail.php**:

```php
'mailers' => [
    // ...

    'mailjet' => [
        'transport' => 'mailjet',
    ],
],
```

> **Note:** the `from` address in **config/mail.php** must be an authorised
> sender configured on your Mailjet account. Manage your senders and domains
> [here](https://app.mailjet.com/account/sender).

### Optional client configuration

You can pass full [MailjetClient](https://github.com/mailjet/mailjet-apiv3-php)
options through **config/services.php**:

| Key             | Used for                                                        |
|-----------------|-----------------------------------------------------------------|
| `transactional` | The Send API client                                             |
| `common`        | The client resolved behind the `Mailjet` facade                 |
| `v4`            | The v4 client used by some data providers (e.g. the SMS API)    |

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
            'secured' => true,
        ],
    ],
    'common' => [
        'call' => true,
        'options' => [
            'url' => 'api.mailjet.com',
            'version' => 'v3',
            'call' => true,
            'secured' => true,
        ],
    ],
    'v4' => [
        'call' => true,
        'options' => [
            'url' => 'api.mailjet.com',
            'version' => 'v4',
            'call' => true,
            'secured' => true,
        ],
    ],
],
```

## Sending mail

Once the transport is configured, send mail the usual Laravel way — no
Mailjet-specific code required:

```php
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;

Mail::to($user)->send(new OrderShipped($order));
```

## API wrapper usage

Import the facade to talk to the Mailjet API directly:

```php
use Mailjet\LaravelMailjet\Facades\Mailjet;
```

### Send API

Send transactional email through the [Send API v3.1](https://dev.mailjet.com/email/guides/send-api-v31/)
(the default version):

```php
Mailjet::sendEmail([
    'Messages' => [
        [
            'From' => ['Email' => 'you@example.com', 'Name' => 'You'],
            'To' => [['Email' => 'passenger@example.com', 'Name' => 'Passenger']],
            'Subject' => 'Your booking is confirmed',
            'TextPart' => 'See you soon!',
            'HTMLPart' => '<h3>See you soon!</h3>',
        ],
    ],
]);

// Override the API version if you need the legacy v3 payload shape:
Mailjet::sendEmail($body, ['version' => 'v3']);
```

### SMS API

Send an SMS through the [SMS API v4](https://dev.mailjet.com/sms/guides/send-sms-api/).
The SMS API authenticates with a bearer token rather than the key/secret pair —
see the Mailjet docs for issuing one:

```php
Mailjet::sendSms([
    'From' => 'MyCompany',
    'To' => '+33600000000',
    'Text' => 'Your verification code is 123456',
]);
```

### Contacts and lists

High-level helpers for the most common contact-management calls:

```php
Mailjet::getAllLists($filters);
Mailjet::createList($body);
Mailjet::getListRecipients($filters);
Mailjet::getSingleContact($id);
Mailjet::createContact($body);
Mailjet::createListRecipient($body);
Mailjet::editListRecipient($id, $body);
```

### Low-level API

For any endpoint without a dedicated helper, call the raw verbs with a
[Mailjet resource](https://github.com/mailjet/mailjet-apiv3-php):

```php
use Mailjet\Resources;

Mailjet::get(Resources::$Contact, $args, $options);
Mailjet::post(Resources::$Contact, $args, $options);
Mailjet::put(Resources::$Contact, $args, $options);
Mailjet::delete(Resources::$Contact, $args, $options);
```

Every wrapper method returns a `Mailjet\Response`, or throws a
`Mailjet\LaravelMailjet\Exception\MailjetException` on an API error. You can also
reach the underlying SDK client with `Mailjet::getClient()` to build fully custom
requests.

For the complete endpoint reference, see the official
[Mailjet API documentation](https://dev.mailjet.com/email/reference/).

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see the [license file](LICENSE.md) for more information.
