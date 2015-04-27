<?php
/**
 * Created by PhpStorm.
 * User: Stefan Johner
 * Date: 22.04.2015
 * Time: 07:08
 */

include("../datamodell/Patient.php");
include("./Database.php");

//class PatientController {

getJSONResponse();

function getJSONResponse()
{
    $database = new Database();
    $result = $database->getData();
    $json_response = json_encode(utf8ize($result));
    echo $json_response;
}

// }

function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}

?>