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
        $sql = "SELECT b.UNTERS_NAME, b.ARBEITSPLATZ, b.UNTERS_BEGINN, p.PAT_VORNAME, p.PAT_NAME, p.PAT_GEBURTSDATUM FROM a_untbeh_ueb b LEFT OUTER JOIN a_patient_basis p ON b.PATIENT_SCHLUESSEL=p.PATIENT_SCHLUESSEL WHERE b.UNTERS_STATUS = 't'";
        $result = $this->connection->execute_statement($sql);

        $patients = array();

        foreach ($result as $row) {
            $patients[] = $row;
        }

        return $patients;
    }
}
