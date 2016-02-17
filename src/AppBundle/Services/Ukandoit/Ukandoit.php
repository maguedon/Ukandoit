<?php
namespace AppBundle\Services\Ukandoit;

class Ukandoit
{
    private $dailyPoints; // 1 pts pour 1000 pas [Activité hors-challenge]
    private $metersPoints; // 1 pts pour 500 pas [Activité challenge]
    private $stepsPoints; // 1 pts pour 278 pas [Activité challenge]
    private $coeff; // Coefficient Points/Niveau

    function __construct()
    {
        $this->dailyPoints = 1000;
        $this->metersPoints = 278;
        $this->stepsPoints = 500;
        $this->coeff = 1.2;
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

    public function getDataFromAPI($challenge, $json){
        $challenge_delais = $challenge->getTime();
        $i = 0;
        $max = 0;

        // Si l'utilisateur a synchronisé ses données
        if($json != null){
            foreach($json['global']['days'] as $key => $current_day){
                $next_day = new \DateTime($key);
                $next_day->modify('+1 day');

                if ( $challenge->getKilometres() !== null &&  $challenge->getKilometres() !== 0 )
                    $param_value = $this->getDistance($json['global']['days'][$key]);
                else
                    $param_value = $this->getSteps($json['global']['days'][$key]);

                if ($i <= sizeof($json['global']['days']) - $challenge_delais){
                    for($j=$i; $j<$i+$challenge_delais-1; $j++){
                        if ( $challenge->getKilometres() !== null )
                            $param_value += $this->getDistance($json['global']['days'][$next_day->format("Y-m-d")]);
                        else
                            $param_value += $this->getSteps($json['global']['days'][$next_day->format("Y-m-d")]);
                        
                        $next_day->modify('+1 day');
                    }
                }

                if($param_value > $max){
                    $max = $param_value;
                    $date_start = $key ;
                    $date_end = $next_day->modify('-1 day')->format("Y-m-d");
                }

                $i ++;
            }

            $result = array(
                "start" => $date_start,
                "end" => $date_end,
                "value" => $max
                );
        }
        else{
            $result = array(
                "start" => $challenge->getCreationDate(),
                "end" => $challenge->getEndDate(),
                "value" => 0
                );
        }
        return $result;
    }

    public function getDailyPoints($nbpas){
        return ($nbpas/$this->dailyPoints);
    }

    public function getGoalPoints($performance, $mesure){
        if ($mesure == "pas"){
            return ($performance/$this->stepsPoints);
        }
        elseif( $mesure == "km"){
            return (($performance*1000)/$this->metersPoints);
        }
    }

    public function getPointsFromRanking($position, $nbPlayers, $goalPoints, $winners){ //winner est un booléen indiquant si traite le classement des gagnants ou des perdants
        if ($winners)
            return ($goalPoints + $goalPoints*(($nbPlayers - $position)/$nbPlayers));
        else
            return ($goalPoints*(($nbPlayers - $position)/$nbPlayers));
    }



}