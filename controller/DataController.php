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
    private $umri1;
    private $umct1;

    function __construct($construct)
    {
        if ($construct === 'patients') {
            $this->createSurgeries();
            $this->getJSONResponse(($this->getPatientCaseData()));
        }
        if ($construct === 'devices'){
            $this->createWartezeit();
            $this->getJSONResponse($this->getWartezeitData());
        }
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
            $surgery = new PatientCase($row['TICKET_NUMBER'], $row['UNTART_NAME'], $row['ARBEITSPLATZ'], $row['TERMIN_DATUM'], $row['ANMELDUNG_ANKUNFT'], $row['UNTERS_BEGINN'], $row['WARTEZEIT']);
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
                $patientCaseData .= '{"TICKET_NUMBER":"' . $surgery->getTicketNumber() . '", "UNTART_NAME":"' . $surgery->getSurgeryType() . '", "ARBEITSPLATZ":"' . $surgery->getWorkstation() . '", "TERMIN_DATUM":"' . $surgery->getSurgeryDate() . '", "UNTERS_BEGINN":"' . $surgery->getSurgeryStart() . '", "ANMELDUNG_ANKUNFT":"' . $surgery->getSurgeryRegistration() . '", "WARTEZEIT":"' . $surgery->getWaitingTime() .'"}';
            }
            else{
                $patientCaseData .= '{"TICKET_NUMBER":"' . $surgery->getTicketNumber() . '", "UNTART_NAME":"' . $surgery->getSurgeryType() . '", "ARBEITSPLATZ":"' . $surgery->getWorkstation() . '", "TERMIN_DATUM":"' . $surgery->getSurgeryDate() . '", "UNTERS_BEGINN":"' . $surgery->getSurgeryStart() . '", "ANMELDUNG_ANKUNFT":"' . $surgery->getSurgeryRegistration() . '", "WARTEZEIT":"' . $surgery->getWaitingTime() .'"}, ';
            }

        }
        $patientCaseData .= ' ]} ';
         return $patientCaseData;
    }

    function getWartezeitData()
    {
        $wartezeitData = '{ "records":[ {"UMRI1":"' . $this->umri1 . '", "UMCT1":"' . $this->umct1 .'"} ]} ';
        return $wartezeitData;
    }

    function getJSONResponse($dataForm)
    {
        $json_response = $this->utf8ize($dataForm);
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

    function createWartezeit(){
        $database = new Database();
        $this->umri1 = $database->getWartezeit('UMRI1');
        echo $this->umri1;
        $this->umct1 = $database->getWartezeit('UMCT1');
    }
}
?>