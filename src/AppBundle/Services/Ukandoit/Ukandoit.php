<?php
namespace AppBundle\Services\Ukandoit;

class Ukandoit
{
    private $run;
    private $walk;
    private $bike;
    private $car;

    function __construct()
    {
        $this->walk = array("min" => 0, "max" => 6.5);
        $this->run = array("min" => 6.6, "max" => 13);
        $this->bike = array("min" => 13.1, "max" => 27);
        $this->car = array("min" => 27.1, "max" => 150);
    }

    public function getDistance($json)
    {
        return $json['distance'];
    }

    public function getSteps($json)
    {
        return $json['steps'];

    }

    public function getActiveTime($json)
    {
        return $json['active_time'];

    }

    public function getDetails($json)
    {
        return $json['details'];

    }

    public function getDataFromAPI($devise, $challenge_start, $challenge_end, $challenge_distance, $challenge_steps, $challenge_active_time, $challenge_delais){

        if ($devise->getDeviceType()->getName() == "Withings Activité Pop"){

        }
        foreach (){

        }
    }

}