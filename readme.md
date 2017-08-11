Requirements
https://github.com/laralib/l5scaffold 

composer require 'laralib/l5scaffold' --dev


Register the command 
https://laravel.com/docs/5.4/artisan#registering-commands

    protected $commands = [
        Commands\BuildDb::class
    ];


    