<?php
/**
 * Created by PhpStorm.
 * User: Stefan Johner
 * Date: 22.04.2015
 * Time: 07:08
 */

include("../datamodell/PatientCase.php");
include("./Database.php");
error_reporting(-1);

class DataController
{
    private $surgeries = array();
    private $umri1;
    private $uct1;

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
        $result = $database->getListData();
        while ($row = $result->fetch()) {
            $arr[] = $row;
        }

        foreach ($arr as $row) {
            $surgery = new PatientCase($row['TICKET_NUMBER'], $row['UNTART_NAME'], $row['ARBEITSPLATZ'], $row['TERMIN_DATUM'], $row['UNTERS_BEGINN'], $row['ANMELDUNG_ANKUNFT'], $row['WARTEZEIT']);
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
        $surgeries = $this->getSurgeries();
        $lastPatientCase = end($surgeries);
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
        $umri1 = $this->umri1->fetch();
        $uct1 = $this->uct1->fetch();

        $wartezeitData = '{ "records":[ {"UMRI1":"' . $umri1[0] . '", "UCT1":"' . $uct1[0] .'"} ]} ';
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
        $this->uct1 = $database->getWartezeit('UCT1');
    }
}
?>