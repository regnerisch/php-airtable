# PHP-Airtable
This library is an unofficial wrapper for the Airtable API.
## Installation
You can simple install PHP-Airtable through composer:
```bash
composer require regnerisch/php-airtable
```
## Usage
At first you need to create a new Client and pass your Airtable API-Key: 
```php
$client = new Client($apiKey);
```
After tell the client which base you want to use (if you do not use a Base, a Exception will be thrown):
```php
$client->useBase($baseId);
```
You can call `useBase` when every you need to change the base. All calls to the API afterwards will be made against that base.

You can get multiple records: 
```php
$records = $client->records('Table', $options);
```
Get a single record: 
```php
$record = $client->record('Table', $record);
```
Create records:
```php
$records = $client->create('Table', $records);
```
Update records:
```php
$records = $client->update('Table', $records);
```
Or delete records:
```php
$records = $client->delete('Table', $records);
```
## API
### Client
#### __construct()
##### Call
```php
$client = new Client($apiKey);
```
##### Parameters
Name|Type|Default
---|---|---
$apiKey|string|
#### useBase()
##### Call
```php
$client->useBase($baseId);
```
##### Parameters
Name|Type|Default
---|---|---
$base|string|
#### records()
##### Call
```php
$records = $client->record($table, $options);
```
##### Parameters
Name|Type|Default|Info
---|---|---|---
$table|string|
$options|array|[]|See Airtable API 'List Records'
