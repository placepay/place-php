# Place PHP Library

A php library for interfacing with the Place API

## Installation

To install from GitHub using [composer](https://getcomposer.org/):

```bash
composer require place/place-php
```

To manually install `place-php`, you can [download the source](https://github.com/placepay/place-php/zipball/master) and include with:

```php
<?php
require_once('/path/to/place-php/import.php');
?>
```

## Basic usage

```php
<?php
require_once('vendor/autoload.php');

# set your api key
Place\Place::$api_key = "private_key_6fsMi3GDxXg1XXSluNx1sLEd";

# create an account
$account = Place\Account::create(array(
  'email'=>'joe.schmoe@example.com',
  'full_name'=>'Joe Schmoe',
  'user_type'=>'payer'
));
?>
```

## Documentation
Read the [docs](https://developer.placepay.com/?php)
