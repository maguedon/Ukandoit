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

    public function getRecord($value){
        $moves = $this->get('app.jawbone')->getMoves();

        $date_debut = new DateTime("2016-01-20");
        $date_fin = new DateTime("2016-01-25");
        $max = 0;
        $dates = "";
        $time = 2;

        $nbDaysChallenge = $date_fin - $date_debut;

        for($i=0; $i<$nbDaysChallenge-$time-1; $i++){
            if($value = "distance")
                $total = $date_debut->modify('+' . $i . 'day')->getKilometres();
            else
                $total = $date_debut->modify('+' . $i . 'day')->getSteps();

            for($j=$i+1; $j<$time-1; $j++){
                if($value = "distance")
                    $total += $date_debut->modify('+' . $j . 'day')->getKilometres();
                else
                    $total = $date_debut->modify('+' . $j . 'day')->getSteps();
            }
            if($total > $max){
                $max = $total;
                $dates = $date_debut->modify('+' . $i . 'day')->format("d-m-Y") . " - " . $date_debut->modify('+' . $i + ($time-1) . 'day')->format("d-m-Y");
            }
        }

        return array($max, $dates);
    }

}