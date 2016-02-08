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

    /*
     * Retourne le tableau suivant : result = array(
     *                                  0 => array[
     *                                          "start" => "2016-02-07",
     *                                          "end" => "2016-02-08",
     *                                          "value" => "23500"
     *                                  ]
     *
     *                                 1 => array[
     *                                          "start" => "2016-02-07",
     *                                          "end" => "2016-02-08",
     *                                          "value" => "23500"
     *                                  ]
     */

    public function getDataFromAPI($devise, $challenge){
        $challenge_start = $challenge->getCreationDate();
        $challenge_end = $challenge->getEndDate();
        //$challenge_active_time = ;
        $challenge_delais = $challenge->getTime();


        if ($devise->getDeviceType()->getName() == "Withings Activité Pop"){
            $withings = $this->get("app.withings");
            $withings->authenticate($devise);
            $json = $withings->getActivities($withings->getUserID() , $challenge_start, $challenge_end);
            $json = $withings->standardizeJSON($json);
        }

        if ($devise->getDeviceType()->getName() == "Jawbone UP 24"){
            $jawbone = $this->get("app.jawbone");
            $json = $jawbone->getMoves($devise->getAccessTokenJawbone(), $challenge_start, $challenge_end);
            $json = $jawbone->standardizeJSON($json);
        }

        $content = array();
        for ( $i = 0; $i < (sizeof($json['days'])); $i ++){
            $current_day = $json['days'][$i];
            if ( $challenge->getKilometres() !== null )
                $param_value = $this->getDistance($current_day['distance']);
            else
                $param_value = $this->getDistance($current_day['steps']);

            if ($i <= (sizeof($json['days']) - $challenge_delais)){
                $content[$i] = array(
                    "start" => key($current_day),
                    "value" => $param_value
                );
            }

            if ($i >= $challenge_delais - 1){
                for($j = 0; $j < $challenge_delais - 1; $j++){
                    $content[$i-$j]['value'] += $param_value;
                }
                $content[$i-($challenge_delais - 1)]['end'] = key($current_day);
            }
        }
        return $content;
    }

}