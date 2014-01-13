Laravel-FTP
===========

A simple Laravel 4 ftp service provider.

Installation
------------

Add the package to your `composer.json` and run `composer update`.

    {
        "require": {
            "anchu/ftp": "dev-master"
        }
    }

Add the service provider in `app/config/app.php`:

    'Anchu\Ftp\FtpServiceProvider',

Configuration
------------
Run `php artisan config:publish anchu/ftp` and modify the config file with your ftp connections.

You can add dynamic FTP connections with following syntax

```php
Config::set('ftp::connections.key', array(
           'host'   => '',
           'username' => '',
           'password'   => '',
           'password'   => '',
           'passive'   => false,
));
```

Basic usage
------------
```php
// With custom connection
$listing = Ftp::connection('my-ftp-connection')->getDirListing();

// Using default connection
$listing = FTP::connection()->getDirListing();
$status = Ftp::connection()->makeDir('directory-name');
```
