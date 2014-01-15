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

Accessing connections
---------------------
You can access default FTP connection via the `Ftp::connection` method:
```php
$listing = Ftp::connection()->getDirListing(...);
```

When using multiple connections you can access each specific ftp connection by passing connection name:
```php
$listing = Ftp::connection('foo')->getDirListing(...);
```

Sometimes you may need to reconnect to a given ftp:
```php
$listing = Ftp::reconnect('foo');
```

If you need to disconnect from a given ftp use the disconnect method:
```php
$listing = Ftp::disconnect('foo');
```

Basic usage examples
------------
```php
// With custom connection
$listing = Ftp::connection('my-ftp-connection')->getDirListing();

// Using default connection
$listing = FTP::connection()->getDirListing();
$status = Ftp::connection()->makeDir('directory-name');
```