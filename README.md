# AWS Serverless Image Handler Laravel Package

[![Latest Stable Version](https://img.shields.io/packagist/v/rkcreative/aws-image-handler.svg?style=flat-square)](https://packagist.org/packages/rkcreative/aws-image-handler)
[![Total Downloads](https://img.shields.io/packagist/dt/rkcreative/aws-image-handler.svg?style=flat-square)](https://packagist.org/packages/rkcreative/aws-image-handler)
[![GitHub stars](https://img.shields.io/github/stars/rkcreative/aws-image-handler.svg?style=flat-square)](https://github.com/rkcreative/aws-image-handler/stargazers)
[![Last Commit](https://img.shields.io/github/last-commit/rkcreative/aws-image-handler?style=flat-square)](https://github.com/rkcreative/aws-image-handler/commits)
[![CI](https://github.com/rkcreative/aws-image-handler/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/rkcreative/aws-image-handler/actions/workflows/ci.yml)
[![License](https://img.shields.io/github/license/rkcreative/aws-image-handler?style=flat-square)](LICENSE.md)

This package provides a Laravel wrapper for the [AWS Serverless Image Handler](https://aws.amazon.com/solutions/implementations/serverless-image-handler/) service.

## Requirements

- PHP >= 7.3
- Laravel >= 5.0
- An AWS account with Image Handler service installed

## Installation

You can install the package via composer:

```bash
composer require rkcreative/aws-image-handler
```

### For Laravel 5.5+

After installing the package, the `AwsImageHandlerServiceProvider` is automatically registered thanks to the package discovery feature in Laravel.

### For Laravel 5

If for some reason you need to register the service provider manually, you can add it to the `providers` array in your `config/app.php` file:

```php
'providers' => [
    // Other service providers...

    Rkcreative\AwsImageHandler\AwsImageHandlerServiceProvider::class,
],
```

## Configuration

After installing the package, you should publish the configuration file:

```bash
php artisan vendor:publish --provider="Rkcreative\AwsImageHandler\AwsImageHandlerServiceProvider" --tag="config"
```

This command will create a `aws-image-handler.php` configuration file in your `config` directory. In this file, you can set your distribution URL and the default bucket:

```php
return [
    'distributionUrl' => env('AWS_IMAGE_HANDLER_URL', 'default-url'),
    'defaultBucket'   => env('AWS_IMAGE_HANDLER_BUCKET', 'default-bucket'),
];
```

You should also add the `AWS_IMAGE_HANDLER_URL` and `AWS_IMAGE_HANDLER_BUCKET` variables to your `.env` file:

```
AWS_IMAGE_HANDLER_URL=your-distribution-url
AWS_IMAGE_HANDLER_BUCKET=your-default-bucket
```

## Usage

Here's a detailed example of how to use the `ImageHandler` class:

```php
use Rkcreative\AwsImageHandler\Services\ImageHandler;

$imageHandler = new ImageHandler();
$imageHandler->resize(200, 200);

// Generate the URL for the transformed image
$url = $imageHandler->url('path/to/image.jpg');
```

In this example, the `resize` macro is used to set the desired image dimensions, and then the `url` method is used to generate the URL for the transformed image. The resulting URL will look something like this:

```
https://your-cloudfront-url.com/eyJidWNrZXQiOiJidWNrZXQiLCJrZXkiOiJwYXRoL3RvL2ltYWdlLmpwZyIsImVkaXRzIjp7InJlc2l6ZSI6eyJ3aWR0aCI6MjAwLCJoZWlnaHQiOjIwMH19fQ==
```

In this URL, `your-cloudfront-url.com` is your CloudFront distribution URL, and the long base64 string is the encoded edit options.

## Using the Facade

The package also provides a facade, which you can use for even easier access. Here's an example:

```php
use Rkcreative\AwsImageHandler\Facades\ImageHandler;

$url = ImageHandler::resize(200, 200)->url('path/to/image.jpg');
```

In this example, `ImageHandler::resize(200, 200)` is equivalent to `(new ImageHandler())->resize(200, 200)`. You can use this facade anywhere in your Laravel application.

## Available Macros

The `ImageHandler` class comes with the following macros:

- `smartCrop`
- `roundCrop`
- `contentModeration`
- `crop`
- `quality`
- `resize`
- `setRgba`
- `rotate`

You can use these macros like any other method on the `ImageHandler` class:

```php
$imageHandler = new ImageHandler();
$imageHandler->smartCrop($options);
$imageHandler->roundCrop($options);
// etc.
```

## Extending with Custom Macros

You can extend the `ImageHandler` class with your own macros. The options are from the [Sharp Node.js image processing library](https://sharp.pixelplumbing.com/api-operation). Here's how to do it:

1. Create a new service provider:

```bash
php artisan make:provider ImageHandlerMacroServiceProvider
```

2. In the `boot` method of your new service provider, add your macro:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Rkcreative\AwsImageHandler\Services\ImageHandler;

class ImageHandlerMacroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Sample blur image operation from the Sharp node.js image library to show how you could add it as a custom option.
        ImageHandler::macro('blur', function ($imageHandler, $blur) {
            if ($blur < 0.3 || $blur > 1000) {
                throw new \InvalidArgumentException('Invalid blur value. It must be = a sigma value between 0.3 and 1000.');
            }

            $imageHandler->options['blur'] = $blur;
        });
    }
}
```

In this example, the `blur` macro receives the `ImageHandler` instance and a `blur` value as arguments. The `blur` value is then added to the image handler options.

3. Register your new service provider in the `providers` array in `config/app.php`:

```php
'providers' => [
    // Other service providers...

    App\Providers\ImageHandlerMacroServiceProvider::class,
],
```

Now, you can use your macro like any other method on the `ImageHandler` class:

```php
$imageHandler = new ImageHandler();
$imageHandler->blur(3)->url('path/to/image.jpg');
```

In this example, the `blur` macro is used to add a blur effect to the image. The `blur` value can be any number between 0.3 (low blur) and 1000 (maximum blur).

## Contributing

Contributions are welcome and will be fully credited. We accept contributions via Pull Requests on Github.

### Bug Reports

If you've found a bug, please create an issue on Github describing the problem and include as much relevant information as you can. Screenshots, error messages, and sample code that reproduces the problem are all helpful.

### Pull Requests

- **Document any change in behavior** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.
- **Create topic branches** - Don't ask us to pull from your master branch.
- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.
- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please squash them before submitting.
- **Write tests for your feature** - This helps us ensure that everything is working as expected and protects against regressions when new changes are made.

To run the tests, use the following command:

```bash
vendor/bin/phpunit
```

**Happy coding**!

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

### Authors

- [Robert Pettique](https://github.com/r0biabl0)
