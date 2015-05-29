<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 20.04.2015
 * Time: 08:45
 */

class PatientCase {

    private $ticketNumber;
    private $surgeryType;
    private $workStation;
    private $surgeryStart;
    private $surgeryRegistration;
    private $waitingTime;

    function __construct($ticketNumber, $surgeryType, $workStation, $surgeryStart)
    {
        $this->ticketNumber = $ticketNumber;
        $this->surgeryType = $surgeryType;
        $this->workStation = $workStation;
        $this->surgeryStart = $surgeryStart;
    }

    public function getWorkStation()
    {
        return $this->workStation;
    }

    public function setWorkStation($workStation)
    {
        $this->workStation = $workStation;
    }

    public function getSurgeryStart()
    {
        return $this->surgeryStart;
    }

    public function setSurgeryStart($surgeryStart)
    {
        $this->surgeryStart = $surgeryStart;
    }

    public function getSurgeryType()
    {
        return $this->surgeryType;
    }

    public function setSurgeryType($surgeryType)
    {
        $this->$surgeryType = $surgeryType;
    }

    public function getTicketNumber(){
        return $this->ticketNumber;
    }

    public function setTicketNumber($ticketNumber){
        $this->ticketNumber = $ticketNumber;
    }

    public function getSurgeryRegistration()
    {
        return $this->surgeryRegistration;
    }

    public function setSurgeryRegistration($surgeryRegistration)
    {
        $this->surgeryRegistration = $surgeryRegistration;
    }

    public function getWaitingTime()
    {
        return $this->waitingTime;
    }

    public function setWaitingTime($waitingTime)
    {
        $this->waitingTime = $waitingTime;
    }
}