<?php
/**
 * Created by PhpStorm.
 * User: Stefan Johner
 * Date: 22.04.2015
 * Time: 07:08
 */

include("../datamodell/PatientCase.php");
include("./Database.php");

class DataController
{
    private $surgeries = array();

    function __construct(){
        $this->createSurgeries();
        $this->getJSONResponse();
    }

    function createSurgeries(){
        $database = new Database();
        $result = $database->getData();
        while($row = $result->fetch()) {
            $arr[] = $row;
        }
        return $arr;

/*        foreach ($result as $row) {
            $surgery = new PatientCase($row['TICKET_NUMBER'], $row['UNTERS_NAME'], $row['ARBEITSPLATZ'], $row['PAT_NAME'], $row['PAT_VORNAME'], $row['PAT_GEBURTSDATUM']);
            $this->setSurgeries($surgery);
        }*/
    }

    function setSurgeries($surgery){
        $this->surgeries[] = $surgery;
    }

    function getSurgeries(){
        return $this->surgeries;

    }

    function getPatientCaseData(){
        $patientCaseData = '[';
        foreach ($this->getSurgeries() as $surgery){
            $patientCaseData .= '{"TICKET_NUMBER":"' . $surgery->getTicketNumber() . '","0":"' . $surgery->getTicketNumber() . '"},';
        }
        $patientCaseData .= ']';
       // $patientCaseData = (array) $this->getSurgeries();
        return $patientCaseData;
    }

    function getJSONResponse()
    {
        //$json_response = json_encode($this->utf8ize(print_r($this->getPatientCaseData())));
        $json_response = json_encode($this->utf8ize($this->createSurgeries()));
        echo $json_response;
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