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

    public function getDistanceForADay($day)
    {
        return $day['distance'];
    }

    public function getStepsForADay($day)
    {
        return $day['steps'];

    }

    public function getActiveTimeForADay($day)
    {
        return $day['active_time'];

    }

    public function getDetailsForADay($day)
    {
        return $day['details'];

    }


    public function getDayForMaxMeters($days)
    {
        $MaxMeters = 0;
        $Meters = 0;
        $BestDay = null;
        foreach ($days as $day) {
            $Meters = getDistanceForADay($day);
            if ($MaxMeters < $Meters) {
                $MaxMeters = $Meters;
                $BestDay = $day;
            }
        }

        return $BestDay;
    }
}