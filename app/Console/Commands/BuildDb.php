<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\Driver\PDOMySql\Drive;

/**
 * Command to Generate l5scaffold commands from database model
 * @autor: alvar01omer@gmail.com
 *
 */

class BuildDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use laralib/l5scaffold to generate from DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->create_scaffold_command("do it!");
    }

    /**
     * Create/execute laralib/l5scaffold Comands from db information
     *
     */

    public function create_scaffold_command($ex = null){
            
        $command = "php artisan make:scaffold _tableName_ \
            --schema=\"_schema_\"";

        $schema = $this->schema();

        $commands = [];

        foreach($schema as $table => $columns)
        {
            $commands[$table] = str_replace('_tableName_',ucfirst($table) , $command);
            $commands[$table] = str_replace('_ui_',strtolower($table) , $commands[$table]);
            $schema = "";
            foreach ($columns as $name => $type) {
                if(!is_null($default_value = $this->get_default($table,$name))){
                    $schema .= "{$name}:{$type}:default('{$default_value}'),";
                }else {
                    $schema .= "{$name}:{$type},";
                }
            }

            $schema = substr($schema, 0, -1);
            $commands[$table] = str_replace('_schema_',$schema , $commands[$table]);
            echo "\n[exec]:";
            echo "\n".$commands[$table]."\n";
            if(!is_null($ex)){
                $response = shell_exec("cd ".$_SERVER['DOCUMENT_ROOT']."/../ && ".$commands[$table]);
            }
            echo "\n[response]: {$response} \n";
        }    
    }


    /**
     * Get db information tables - columns names - types.
     * @return array
     */
    public function schema(){
        $schema = DB::getDoctrineSchemaManager();
        $dbPlatform = $schema->getDatabasePlatform();
        $dbPlatform->registerDoctrineTypeMapping('enum', 'string');
        $tables = $schema->listTables();
        $total_info = [];

        $connection = DB::connection();
        foreach ($tables as $table) {
            foreach ($table->getColumns() as $column) {

                $tn = $table->getName();
                $total_info[$tn][$column->getName()] = $column->getType()->getName();
            }
        }
        return $total_info;
    }

    /**
     * Column default value.
     *
     */
    public function get_default($table_name, $column_name){
        $query = 'SELECT COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "' . $table_name . '" AND COLUMN_NAME = "' . $column_name . '"';
        return  array_pluck(DB::select($query), 'COLUMN_DEFAULT')[0];
    }
}
