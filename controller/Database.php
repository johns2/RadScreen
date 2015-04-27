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

/*    public function getPatients()
    {
        $sql = "SELECT * FROM a_patient_basis";

        $result = $this->connection->execute_statement($sql);

        $patients = array();

        foreach ($result as $row) {
            $patient = new Patient($row['idA_PATIENT_BASIS'], $row['PAT_NAME']);
            $patients[] = $patient;
        }

        return $patients;

    }*/

    public function getData()
    {
        $sql = "SELECT p.idA_PATIENT_BASIS FROM a_patient_basis p";

        $result = $this->connection->execute_statement($sql);

        $patients = array();

        foreach ($result as $row) {
            $patients[] = $row;
        }

        return $patients;
    }
}
