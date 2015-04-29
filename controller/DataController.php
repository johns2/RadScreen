<?php
/**
 * Created by PhpStorm.
 * User: Stefan Johner
 * Date: 22.04.2015
 * Time: 07:08
 */

include("../datamodell/Patient.php");
include("./Database.php");

class DataController
{
    function __construct(){
        $this->getJSONResponse();
    }

    function getJSONResponse()
    {
        $database = new Database();
        $result = $database->getData();

        $json_response = json_encode($this->utf8ize($result));
        echo $json_response;
    }

    function generateTicketNumber(){

    }

    function utf8ize($d)
    {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }
}

?>