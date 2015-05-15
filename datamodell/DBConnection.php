<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 20.04.2015
 * Time: 15:57
 */

class DBConnection {

    const Hostname = "localhost";

    const DB_NAME = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521)))(CONNECT_DATA=(SID = XE)))";

    const DB_USERNAME = "radscreen";

    const DB_PASSWORD = 'Pa$$w0rd';

    private $db_kind;

    private $pdo;

    function __construct($db_kind)
    {
        $this->db_kind = $db_kind;
    }


    private function open_db_connection(){

        $db = $this->db_kind;
        $hostname = self::Hostname;
        $dbname = self::DB_NAME;
        $username = self::DB_USERNAME;
        $password = self::DB_PASSWORD;

        try{
            $this->pdo = new PDO($db.":host=$hostname;dbname=$dbname", $username, $password);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    private function close_db_connection(){
        $this->connection = null;
    }

    public function execute_statement($sql_statement){

        self::open_db_connection();

        return $this->pdo->query($sql_statement);

        self::close_db_connection();

    }
}