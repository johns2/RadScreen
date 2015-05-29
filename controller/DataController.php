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

    function __construct()
    {
        $this->createSurgeries();
        $this->getJSONResponse();
    }

    function createSurgeries()
    {
        $database = new Database();
        $result = $database->getData();
        while ($row = $result->fetch()) {
            $arr[] = $row;
        }
        //return $arr;

        foreach ($arr as $row) {
            $surgery = new PatientCase($row['TICKET_NUMBER'], $row['UNTART_NAME'], $row['ARBEITSPLATZ'], $row['UNTERS_BEGINN']);
            $this->setSurgeries($surgery);
        }
    }

    function setSurgeries($surgery)
    {
        $this->surgeries[] = $surgery;
    }

    function getSurgeries()
    {
        return $this->surgeries;
    }

    function getPatientCaseData()
    {
        $lastPatientCase = end($this->getSurgeries());
        $patientCaseData = '{ "records":[ ';
        foreach ($this->getSurgeries() as $surgery){
            if ($surgery == $lastPatientCase){
                $patientCaseData .= '{"TICKET_NUMBER":"' . $surgery->getTicketNumber() . '", "UNTART_NAME":"' . $surgery->getSurgeryType() . '", "ARBEITSPLATZ":"' . $surgery->getWorkstation() . '", "UNTERS_BEGINN":"' . $surgery->getSurgeryStart() . '", "ANMELDUNG_ANKUNFT":"' . $surgery->getSurgeryRegistration() . '", "WARTEZEIT":"' . $surgery->getWaitingTime() .'"}';
            }
            else{
                $patientCaseData .= '{"TICKET_NUMBER":"' . $surgery->getTicketNumber() . '", "UNTART_NAME":"' . $surgery->getSurgeryType() . '", "ARBEITSPLATZ":"' . $surgery->getWorkstation() . '", "UNTERS_BEGINN":"' . $surgery->getSurgeryStart() . '", "ANMELDUNG_ANKUNFT":"' . $surgery->getSurgeryRegistration() . '", "WARTEZEIT":"' . $surgery->getWaitingTime() .'"}, ';
            }

        }
        $patientCaseData .= ' ]} ';
//        foreach ($this->getSurgeries() as $surgery) {
//            $patientCase1 = array("TICKET_NUMBER" => $surgery->getTicketNumber(), "UNTART_NAME" => $surgery->getSurgeryType(), "ARBEITSPLATZ" => $surgery->getWorkstation(), "UNTERS_BEGINN" => $surgery->getSurgeryStart(), "ANMELDUNG_ANKUNFT" => $surgery->getSurgeryRegistration(), "WARTEZEIT" => $surgery->getWaitingTime());
//        }
        //$patientCaseData = (array) $this->getSurgeries();
         return $patientCaseData;
//        return $patientCase1;
    }

    function getJSONResponse()
    {
        //$json_response = json_encode($this->utf8ize(print_r($this->getSurgeries())));
        $json_response = $this->utf8ize(($this->getPatientCaseData()));
        // $json_response = json_encode($this->utf8ize(($arr)));
        echo stripslashes($json_response);
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