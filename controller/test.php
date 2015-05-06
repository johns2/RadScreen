<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 20.04.2015
 * Time: 15:40
 */
include("../datamodell/PatientCase.php");
include("Database.php");
//$new_patient = new Patient(12, hallo);
//$new_patient->setId(10);
//echo $new_patient->getId();
$database = new Database();
$result = $database->getData();

echo '<table><tr><td>Id</td><td>Name</td></tr>';
foreach($result as $value){
    echo '<tr><td>'.$value[0].'</td></tr>';
}
echo '</table>';



?>