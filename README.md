# RentShare PHP Library

A php library for interfacing with the RentShare API

## Installation

To install from GitHub using [composer](https://getcomposer.org/):

```bash
composer config repositories.rentshare-php git https://github.com/rentshare/rentshare-php.git
composer require rentshare/rentshare-php:master
```

To manually install `rentshare-php`, you can [download the source](https://github.com/rentshare/rentshare-php/zipball/master) and include with:

```php
<?php
require_once('/path/to/rentshare-php/import.php');
?>
```

## Basic usage

```php
<?
require_once('vendor/autoload.php');

# set your api key
RentShare\RentShare::$api_key = "private_key_6fsMi3GDxXg1XXSluNx1sLEd";

# create an account
$account = RentShare\Account::create(array(
  'email'=>'joe.schmoe@example.com',
  'full_name'=>'Joe Schmoe',
  'user_type'=>'payer'
));
?>
```

## Documentation
Read the [docs](https://developer.rentshare.com/?php)
