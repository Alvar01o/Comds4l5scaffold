# Usage
Laravel 5 Command - Generate CRUDS site from database with l5scaffold auto-generated commands 
```sh
php artisan make:command BuildDb
```


# Requirements
[l5scaffold](https://github.com/laralib/l5scaffold) 
```sh
composer require 'laralib/l5scaffold' --dev
```

## Register the command 
[Register the Command](https://laravel.com/docs/5.4/artisan#registering-commands)
```sh
    protected $commands = [
        Commands\BuildDb::class
    ];
```
