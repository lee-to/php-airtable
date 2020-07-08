# AirTable API client for PHP

[![Build Status](https://img.shields.io/travis/lee-to/php-airtabl/5.x.svg)](https://travis-ci.org/github/lee-to/php-airtable)

AirTable API client for PHP

## Installation

The AirTable Client PHP can be installed with [Composer](https://getcomposer.org/). Run this command:

```sh
composer require lee-to/php-airtable
```

## Usage

Get token and base from [AirTable Account](http://airtable.com/account) and [AirTable API](http://airtable.com/api)

### Import.
```php
use Airtable;
```

### Init.
- optional param @http_client = "curl" or "guzzle". Default guzzle if exist or curl

```php

$client = new AirTable(["token" => "", "base" => "", "http_client" => ""]);
```

#### Get records from that table
- List table records

``` php
$client->table('table_name')->list();
```

#### Get one record from table.
``` php
$client->table('table_name')->retrieve('ID');
```

#### Filter records
- First argument is the column name
- Second argument is the operator or the value if you want to use equal '=' as an operator.
- Third argument is the value of the filter
``` php
$client->table('table_name')->filterByFormula("column", "operator", "value")->list();
```

#### Sort records
- First argument is the column name
- Second argument is direction.

``` php
$client->table('table_name')->sort("column", "direction")->list();
```

#### Fields
- Only data for fields whose names are in this list will be included in the result. If you don't need every field, you can use this parameter to reduce the amount of data transferred

``` php
$client->table('table_name')->fields(["Column1", "Column2"])->list();
```

#### Max records 
- The maximum total number of records that will be returned in your requests. If this value is larger than pageSize (which is 100 by default), you may have to load multiple pages to reach this total.

``` php
$client->table('table_name')->maxRecords(15)->list();
```

#### Page size 
- The number of records returned in each request. Must be less than or equal to 100. Default is 100.

``` php
$client->table('table_name')->pageSize(15)->list();
```

#### Offset 
- Set offset ID for next page

``` php
$client->table('table_name')->offset('ID')->list();
```

#### Update 
- Update one record

``` php
$client->table('table_name')->update('ID', ["Column1" => "Value"]);
```

OR 

``` php
foreach($client->table('table_name')->list() as $record) {
    $record->update(["Column1" => "Value"]);
}
```

#### Create
- Create a new record

``` php
$client->table('table_name')->create(["Column1" => "Value"]);
```
#### Delete
- Delete one record

``` php
$client->table('table_name')->delete('ID');
```

OR 

``` php
foreach($client->table('table_name')->list() as $record) {
    $deleted = $record->delete();
    
    $deleted->isDeleted(); // Check is deleted or not
}
```

#### Get fields of record

``` php
foreach($client->table('table_name')->list() as $record) {
    $record->getId(); // ID 
    
    $record->COLUMN1; // Any fields in table 
}
```

## Tests

1. [Composer](https://getcomposer.org/) is a prerequisite for running the tests. Install composer globally, then run `composer install` to install required files.
2. Get personal API key and Base [AirTable](https://airtable.com/account), then create `tests/AirTableTestCredentials.php` from `tests/AirTableTestCredentials.php.dist` and edit it to add your credentials.
3. The tests can be executed by running this command from the root directory:

```bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Danil Shutsky](https://github.com/lee-to)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Security

If you have found a security issue, please contact the maintainers directly at [leetodev@ya.ru](mailto:leetodev@ya.ru).