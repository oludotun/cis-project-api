<?php

namespace MiniCli;

class App
{
    protected $printer;
    

    public function __construct()
    {
        $this->printer = new CliPrinter();        
    }

    public function getPrinter()
    {
        return $this->printer;
    }

    public function runCommand(array $argv)
    {
        $command = null;
        if (isset($argv[1])) {
            $command = $argv[1];
        }

        if (!$command) {
            $this->getPrinter()->out("No command given \n");
            $this->getPrinter()->display("usage: php mini-cli [ command ]");
            exit;
        }

        switch ($command) {
            case 'create-tables':
                Operations::createTables();
                break;
            case 'seed':
                $db = new DbConnection();
                if($db->dbHasData()) {
                    $response = explode(' ', readline("Only run this command once to avoid data duplication. Continue? [Yes/No]: "));
                    if (strtolower($response[0]) != "yes") {
                        exit("Database seed operation aborted ... \n");
                        break;
                    }
                    Operations::seedDatabase();
                    break;
                }
                $this->getPrinter()->display("Database not found or has no tables to seed \n");
                break;
            case 'db-init':
                $db = new DbConnection();
                if($db->dbHasData()) {
                    $response = explode(' ', readline("Only run this command once to avoid data duplication. Continue? [Yes/No]: "));
                    if (strtolower($response[0]) != "yes") {
                        exit("Database seed operation aborted ... \n");
                        break;
                    }
                }
                Operations::createTables();
                Operations::seedDatabase();
                break;
            case 'drop-db':
                Operations::dropDb();
                break;

            default:
                $this->getPrinter()->out("Invalid command given \n");
                $this->getPrinter()->display("usage: php mini-cli [ command ]");
                break;
        }
    }
}