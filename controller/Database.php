<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 20.04.2015
 * Time: 16:22
 */
include("../datamodell/DBConnection.php");
include("../interfaces/DataSource.php");


class Database implements DataSource
{

    private $connection;

    function __construct()
    {
        $this->connection = new DBConnection("mysql");
    }

    public function getData()
    {
        $sql = "SELECT CONCAT(LEFT(PAT_VORNAME, 1), EXTRACT(DAY FROM PAT_GEBURTSDATUM), LEFT(PAT_NAME, 1), LEFT(idA_UNTBEH_UEB, 3)) as TICKET_NUMBER, b.UNTERS_NAME, b.ARBEITSPLATZ, DATE_FORMAT(b.UNTERS_BEGINN, '%d.%m.%y %h:%i') as UNTERS_BEGINN, DATE_FORMAT(IFNULL(b.UNTERS_BEGINN, a.ANMELDUNG_ANKUNFT), '%d.%m.%y %h:%i') as ANMELDUNG_ANKUNFT, TIMEDIFF(NOW(), b.UNTERS_BEGINN) as WAITING_TIME FROM a_untbeh_ueb b LEFT OUTER JOIN a_patient_basis p ON b.PATIENT_SCHLUESSEL=p.PATIENT_SCHLUESSEL INNER JOIN a_untbeh_ueb_alle a ON b.idA_UNTBEH_UEB=a.idA_UNTBEH_UEB_ALLE WHERE b.UNTERS_STATUS = 't'";
        $result = $this->connection->execute_statement($sql);
        return $result;
    }

/*    public function checkStatus()
    {

    }*/
}
