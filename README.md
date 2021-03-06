# Timber

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]

A Laravel 5+ wrapper for the [Timber Logger](https://timber.io/) service. Use it to log HTTP requests or custom events to Timber.

## Installation

**1.** Require the package via Composer
``` bash
$ composer require rebing/timber-laravel
```

**2.** Laravel 5.5+ will autodiscover the package, for older versions add the
following service provider
```php
Rebing\Timber\TimberServiceProvider::class,
```

and alias
```php
'Timber' => 'Rebing\Timber\Support\Facades\Timber',
```

in your `config/app.php` file.

**3.** Publish the configuration file
```bash
$ php artisan vendor:publish --provider="Rebing\Timber\TimberServiceProvider"
```

**4.** Review the configuration file
```
config/timber.php
```
and add your Timber API key to `.env`

**5.** (Optional) Log incoming requests

Check [HTTP Requests](#http-requests)

**6.** (Optional) Log all messages

Check [Log all messages](#log-all-messages)

## Usage

### HTTP Requests

To log HTTP requests use the `Rebing\Timber\Middleware\LogRequest::class` middleware. 
This will log all incoming requests and responses, including context and Auth data.

For example, you can add it to `Kernel.php`:

```php
class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     */
    protected $middleware = [
        Rebing\Timber\Middleware\LogRequest::class,
    ];
}
```

### Log all messages

This requires Laravel 5.6+

You can leverage Laravel's `Logger` facade to log all messages to Timber.

Add a new channel to `config/logging.php`

```php
'channels' => [
    'timber' => [
        'driver' => 'monolog',
        'handler' => Rebing\Timber\Handlers\TimberHandler::class,
    ],
];
```

And update your .env with `LOG_CHANNEL=timber`

You can then easily log custom data by providing a message, type and data. For example:
```php
$data = [
    'key' => 'value',
];
\Log::info('Some message', ['type' => $data]);
```

### Custom Events

You can also log custom data. Context will be added automatically.
```php
use Rebing\Timber\Requests\Events\CustomEvent;

$data = [
    'some' => 'data',
];

$customEvent = new CustomEvent('Log message', 'custom', $data);
dispatch($customEvent);
// Or $customEvent->send();
```

### Disable logging

You can disable sending logs to Timber by updating your .env file with 

```
TIMBER_ENABLED=false
```

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email mikk.nurges@rebing.ee instead of using the issue tracker.

## Credits

- [Mikk Mihkel Nurges][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rebing/timber-laravel.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rebing/timber-laravel.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rebing/timber-laravel/master.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rebing/timber-laravel
[link-downloads]: https://packagist.org/packages/rebing/timber-laravel
[link-travis]: https://travis-ci.org/rebing/timber-laravel
[link-author]: https://github.com/rebing
[link-contributors]: ../../contributors]
