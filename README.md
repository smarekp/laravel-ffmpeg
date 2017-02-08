# Laravel FFMpeg

This is a fork of [pascalbaljetmedia/laravel-ffmpeg](https://github.com/pascalbaljetmedia/laravel-ffmpeg), modified to be compatible with PHP 5.6+ instead of just PHP 7.

The following changes were made to make the package compatible with PHP 5.6:
* Changed namespace to "Smarekp\LaravelFFMpeg".
* Removed return type declarations.
* Removed and replaced spaceship operators with PHP 5.6 equivalent.
* Removed type-hinting of "string", "int", and "float" types.
* Removedand and replaced null-coalescing operator with php 5.6 equivalent.

This package provides an integration with FFmpeg for Laravel 5.1 and higher. The storage of the files is handled by [Laravel's Filesystem](http://laravel.com/docs/5.1/filesystem).

## Features
* Super easy wrapper around [PHP-FFMpeg](https://github.com/PHP-FFMpeg/PHP-FFMpeg), including support for filters and other advanced features.
* Integration with [Laravel's Filesystem](http://laravel.com/docs/5.1/filesystem), [configuration system](https://laravel.com/docs/5.1#configuration) and [logging handling](https://laravel.com/docs/5.1/errors).
* Compatible with Laravel 5.1 and up.
* PHP 5.6 and up. Older versions of PHP completely untested.

## Installation

To install this package, you must add the package as well as this repository to your ```composer.json``` file:

``` json
{
	...
	"require": {
        ...
		"smarekp/laravel-ffmpeg": "dev-master",
		...
    },
	"repositories": [
		...
        {
            "type": "vcs",
            "url":  "https://github.com/smarekp/laravel-ffmpeg"
        }
		...
    ],
	...
}
```

Add the service provider and facade to your ```app.php``` config file:

``` php

// Laravel 5: config/app.php

'providers' => [
    ...
    Smarekp\LaravelFFMpeg\FFMpegServiceProvider::class,
    ...
];

'aliases' => [
    ...
    'FFMpeg' => Smarekp\LaravelFFMpeg\FFMpegFacade::class
    ...
];
```

Publish the config file using the artisan CLI tool:

``` bash
php artisan vendor:publish --provider="Smarekp\LaravelFFMpeg\FFMpegServiceProvider"
```

## Usage

Please see the [Usage section](https://github.com/pascalbaljetmedia/laravel-ffmpeg#usage) of [pascalbaljetmedia/laravel-ffmpeg's readme file](https://github.com/pascalbaljetmedia/laravel-ffmpeg#laravel-ffmpeg).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently. Currently out of date.

## Testing

This package has not been fully tested.

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details on contributing to the original [pascalbaljetmedia/laravel-ffmpeg](https://github.com/pascalbaljetmedia/laravel-ffmpeg) project.

For any contributions relating specifically to [smarekp/laravel-ffmpeg](https://github.com/smarekp/laravel-ffmpeg), you can email me at marekphilibert@gmail.com.

## Security

If you discover any security related issues, please email pascal@pascalbaljetmedia.com or marekphilibert@gmail.com instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/pascalbaljet)
- [Marek Philibert](https://github.com/smarekp)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
