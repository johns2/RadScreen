<?php
/**
 * Created by PhpStorm.
 * User: Stefan Johner
 * Date: 29.04.2015
 * Time: 06:40
 */
include ("./DataController.php");

$construct = $_GET['construct'];
    $data = new DataController($construct);
?>