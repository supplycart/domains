## Laravel Domains

A simple package to set up domains for your applications.

### Installation

To install through composer, run the following command from terminal:

```
composer require supplycart/domains
```

Then publish config using:

```
php artisan vendor:publish
```

### Usage

You can set up a domain by creating a domain class which extends `Supplycart\Domains\Domain` and register it inside `domains.php` config file like this:
```php
<?php

return [
    'modules' => [
        App\Domains\User\UserDomain::class
    ]   
];
```

The folder structure suggested is as below:
```
app/
   - Domains/
        - User/
            - Http/
                - Controllers/
                - routes.php # all routes in here
            - Models/
            - Policies/
            - UserDomain.php # your domain class
        - Cart
            ...
        - Order
            ...
```