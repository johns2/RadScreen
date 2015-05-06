<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 20.04.2015
 * Time: 08:45
 */

class PatientCase {

    private $ticketNumber;
    private $caseName;
    private $workStation;
    private $name;
    private $firstName;
    private $birthDate;

    function __construct($ticketNumber, $caseName, $workStation, $name, $firstName, $birthDate)
    {
        $this->ticketNumber = $ticketNumber;
        $this->caseName = $caseName;
        $this->workStation = $workStation;
        $this->name = $name;
        $this->firstName = $firstName;
        $this->birthDate = $birthDate;
    }

    public function getWorkStation()
    {
        return $this->workStation;
    }

    public function setWorkStation($workStation)
    {
        $this->workStation = $workStation;
    }
    private $surgeryStart;

    public function getSurgeryStart()
    {
        return $this->surgeryStart;
    }

    public function setSurgeryStart($surgeryStart)
    {
        $this->surgeryStart = $surgeryStart;
    }

    public function getCaseName()
    {
        return $this->caseName;
    }

    public function setCaseName($caseName)
    {
        $this->caseName = $caseName;
    }

    public function getTicketNumber(){
        return $this->ticketNumber;
    }

    public function setTicketNumber($ticketNumber){
        $this->ticketNumber = $ticketNumber;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
       $this->name = $name;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function setFirstName($firstName){
        $this->firstName = $firstName;
    }

    public function getBirthDate(){
        return $this->birthDate;
    }

    public function setBirthDate($birthDate){
        $this->birthDate = $birthDate;
    }
}