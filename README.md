Laravel-FTP
===========

A simple Laravel 4 ftp service provider.

[![Build Status](https://travis-ci.org/harishanchu/Laravel-FTP.png?branch=master)](https://travis-ci.org/harishanchu/Laravel-FTP)

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
Run `php artisan config:publish anchu/ftp` and modify the config file(`/app/config/packages/anchu/ftp/config.php`) with your ftp connections.

You can add dynamic FTP connections with following syntax

```php
Config::set('ftp::connections.key', array(
           'host'   => '',
           'username' => '',
           'password'   => '',
           'passive'   => false,
));
```

Accessing connections
---------------------
You can access default FTP connection via the `Ftp::connection` method:
```php
Ftp::connection()->getDirListing(...);
```

When using multiple connections you can access each specific ftp connection by passing connection name:
```php
Ftp::connection('foo')->getDirListing(...);
```

Sometimes you may need to reconnect to a given ftp:
```php
Ftp::reconnect('foo');
```

If you need to disconnect from a given ftp use the disconnect method:
```php
Ftp::disconnect('foo');
```

Basic usage examples
------------
```php
// With custom connection
$listing = Ftp::connection('my-ftp-connection')->getDirListing();

// with default connection
$listing = Ftp::connection()->getDirListing();
$status = Ftp::connection()->makeDir('directory-name');
```

Supported methods
-----------------
**getDirListing($directory, $parameters )**

Returns a list of files in the given directory

 - `$directory`: The directory to be listed. Default value: `.`.
 - `$parameters`: Optional parameters to prefix with directory. Default: `-la`.

**makeDir($directory)**

Creates the specified directory on the FTP server.

 - `$directory`: The name of the directory that will be created.

**changeDir($directory)**

Changes the current directory on a FTP server.

 - `$directory`: The target directory.

**uploadFileuploadFile($fileFrom, $fileTo)**

Uploads the data from a file pointer to a remote file on the FTP server.

 - `$fileFrom`: An open file pointer on the local file. Reading stops at end of file.
 - `$fileTo`: The remote file path.

**downloadFile($fileFrom, $fileTo)**

Downloads a file from the FTP server

 - `$fileFrom`: The remote file path.
 - `$fileTo`: The local file path (will be overwritten if the file already exists).

**moveUp()**

 Changes to the parent directory.

**permission($mode, $filename)**

Set permissions on a file.

 - `$mode`: The new permissions, given as an octal value.
 - `$filename`: The remote file.

**delete($path)**

Deletes the file specified by path from the FTP server.

 - `$path`: The file to delete.

**currentDir()**

Returns the current directory name

**rename($oldName, $newName)**

Renames a file or a directory on the FTP server.

 - `$oldName`: The old file/directory name.
 - `$newName`: The new name.

**removeDir($directory)**

 Removes a directory

  - `$directory`: The directory to delete. This must be either an absolute or relative path to an empty directory.

**size($remoteFile)**

Returns the size of the given file in bytes.
`Note: Not all servers support this feature.`

 - `$remoteFile`: The remote file.
