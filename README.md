Laravel-FTP
===========

A simple Laravel 5 ftp service provider.

[![Build Status](https://travis-ci.org/harishanchu/Laravel-FTP.png?branch=master)](https://travis-ci.org/harishanchu/Laravel-FTP)
[![Quality](https://codeclimate.com/github/harishanchu/Laravel-FTP/badges/gpa.svg)](https://codeclimate.com/github/harishanchu/Laravel-FTP)

### For Laravel 4.x, check [v1.0.0](https://github.com/harishanchu/Laravel-FTP/tree/v1.0.0)

Installation
------------

Add the package to your `composer.json` and run `composer update`.

    {
        "require": {
            "anchu/ftp": "~2.0"
        }
    }

Add the service provider in `config/app.php`:

    'Anchu\Ftp\FtpServiceProvider',

Configuration
------------
Run `php artisan vendor:publish` and modify the config file(`config/ftp.php`) with your ftp connections.

You can add dynamic FTP connections with following syntax

```php
Config::set('ftp.connections.key', array(
           'host'   => '',
           'username' => '',
           'password'   => '',
           'passive'   => false,
));
```

Accessing connections
---------------------
You can access default FTP connection via the `FTP::connection` method:
```php
FTP::connection()->getDirListing(...);
```

When using multiple connections you can access each specific ftp connection by passing connection name:
```php
FTP::connection('foo')->getDirListing(...);
```

Sometimes you may need to reconnect to a given ftp:
```php
FTP::reconnect('foo');
```

If you need to disconnect from a given ftp use the disconnect method:
```php
FTP::disconnect('foo');
```

Basic usage examples
------------
```php
// With custom connection
$listing = FTP::connection('my-ftp-connection')->getDirListing();

// with default connection
$listing = FTP::connection()->getDirListing();
$status = FTP::connection()->makeDir('directory-name');
```

Supported methods
-----------------
**getDirListing($directory, $parameters )**

Returns a list of files in the given directory

 - `$directory`: The directory to be listed. Default value: `.`.
 - `$parameters`: Optional parameters to prefix with directory. For example: `-la`. Default: `null`.

**getDirListingDetailed($directory)**

Returns a list of files in the given directory as an associative array with the following keys:
rights, number, user, group, size, month, day and time

 - `$directory`: The directory to be listed. Default value: `.`.

**makeDir($directory)**

Creates the specified directory on the FTP server.

 - `$directory`: The name of the directory that will be created.

**changeDir($directory)**

Changes the current directory on a FTP server.

 - `$directory`: The target directory.

**uploadFile($fileFrom, $fileTo, $mode)**

Uploads a local file to the FTP server.

 - `$fileFrom`: The local file path.
 - `$fileTo`: The remote file path.
 - `$mode`: The transfer mode. Must be either `FTP_ASCII` or `FTP_BINARY`. Automatic mode resolution will be done if no mode is specified.

**downloadFile($fileFrom, $fileTo, $mode)**

Downloads a file from the FTP server

 - `$fileFrom`: The remote file path.
 - `$fileTo`: The local file path (will be overwritten if the file already exists) or an open file pointer in which we store the data.
 - .
 - `$mode`: The transfer mode. Must be either `FTP_ASCII` or `FTP_BINARY`. Automatic mode resolution will be done if no mode is specified.

**readFile($fileFrom)**

Same as the `downloadFile()` method except it downloads the remote file to the PHP output buffer and returns it.

 - `$fileFrom`: The remote file path.

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

**removeDir($directory, $recursive)**

 Removes a directory

  - `$directory`: The directory to delete. This must be either an absolute or relative path to an empty directory.
  - `$recursive`: Delete the folder recursively. Default value: false.

**truncateDir($directory)**

 Truncates a directory

  - `$directory`: The directory to truncate. This must be either an absolute or relative path to a directory.

**size($remoteFile)**

Returns the size of the given file in bytes.
`Note: Not all servers support this feature.`

 - `$remoteFile`: The remote file.

**time($remoteFile)**

Returns the last modified time of the given file
`Note: Not all servers support this feature.`

 - `$remoteFile`: The remote file.
