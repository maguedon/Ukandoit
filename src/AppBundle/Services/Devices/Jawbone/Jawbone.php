<?php

namespace AppBundle\Services\Devices\Jawbone;

use Symfony\Component\Validator\Constraints\DateTime;

class Jawbone{

	private $client_id;
	private $client_secret;
	private $redirect_uri;
	private $scopes;
	private $scope;

	function __construct($client_id, $client_secret, $scopes, $router){
		$this->client_id      = $client_id;
		$this->client_secret  = $client_secret;
		$this->redirect_uri   = (empty($_SERVER['HTTPS'])?'http':'https') . "://" . $_SERVER['HTTP_HOST'] . $router->generate('jawbone_token');

		$this->scopes = $scopes;

		$this->scope = implode(' ', $this->scopes);
	}

	/** 
	 * Connect to the Jawbone account of the user 
	 * and ask permissions to get his information
	 */
	function connection(){
		$param = array(
			'response_type' => 'code',
			'client_id' => $this->client_id,
			'scope' => $this->scope,
			'redirect_uri' => $this->redirect_uri // rediriger vers l'action qui appellera getToken
			);

		$url = "https://jawbone.com/auth/oauth2/auth?" . http_build_query($param);

		return $url;
	}

	/**
	 * Get and save user token
	 */
	function getToken(){
		$error = false;
		if(isset($_GET['error']) || !isset($_GET['code']) || empty($_GET['code'])){
			$error = true;
		}else{

			$param = array(
				'grant_type' => 'authorization_code',
				'client_id' => $this->client_id,
				'client_secret' => $this->client_secret,
				'code' => $_GET['code']
				);

			$url = "https://jawbone.com/auth/oauth2/token?" . http_build_query($param);
			$body = file_get_contents($url);
			$json = json_decode($body, true);

			return $json;
		}
	}

	/**
	 * Get basic information about the user
	 * @see https://jawbone.com/up/developer/endpoints/user
	 */
	function getUser($access_token){
		$url = "https://jawbone.com/nudge/api/v.1.0/users/@me";

		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: Bearer {$access_token}\r\n"
				)
			);

		$context = stream_context_create($opts);

		$response = file_get_contents($url, false, $context);
		$user = json_decode($response, true);
		return $user['data'];
	}

	/**
	* Get activities
	*/
	function getSpecialActivities($access_token){
		$url = "https://jawbone.com/nudge/api/v.1.0/users/@me/workouts?";

		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: Bearer {$access_token}\r\n"
				)
			);

		$start_time = new \DateTime("2016-01-23 13:00:00");
		$end_time = new \DateTime("2016-01-23 17:00:00");

		$param = array(
			'start_time' => $start_time->getTimestamp(),
			'end_time' => $end_time->getTimestamp()
			);

		$url = $url . http_build_query($param);

		$context = stream_context_create($opts);

		$response = file_get_contents($url, false, $context);
		$activities = json_decode($response, true);
		return $activities['data'];
	}

	/**
	 * standardize Jawbone JSON to be used by Ukandoit service
	 */
	function standardizeJSON($Jawbone_array){
		$json = $Jawbone_array['items'];
		$days = array();
		$totalDistance = 0;
		$totalSteps = 0;
		$totalActiveTime = 0;

		foreach ($json as $day){
			$stringDay = str_split($day['date']);
			//substr_replace($stringDay, '-', 4);
			//substr_replace($stringDay, '-', 7);
			$stringDay = $stringDay[0].$stringDay[1].$stringDay[2].$stringDay[3]."-".$stringDay[4].$stringDay[5]."-".$stringDay[6].$stringDay[7];
			$data = $day['details'];
			// date("Y-m-d", strtotime(date("Y-m-d", $data['time_updated'])))
			$totalDistance += $data['distance'];
			$totalSteps += $data['steps'];
			$totalActiveTime += $data['active_time'];

			$hours = array();
			foreach($data['hourly_totals'] as $key => $value){
				$title = str_split($key, 8);
				$newtitle = $title[1];
				$hours[$newtitle] = $value;
			}
			ksort($hours);

			$days[$stringDay] = array(
				"distance" => $data['distance'],
				"steps" => $data['steps'],
				"active_time" => $data['active_time'],
				"details" => $hours
			);
		}

		$result = array(
			"global" => array(
				"distance" => $totalDistance,
				"steps" => $totalSteps,
				"active_time" => $totalActiveTime,
				"days" => $days
				)
			);
		return $result;
	}

	/**
	* Get moves
	*/
	function getMoves($access_token, $start_time, $end_time = null){
		$url = "https://jawbone.com/nudge/api/v.1.0/users/@me/moves?";

		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>"Authorization: Bearer {$access_token}\r\n"
				)
			);

		$start_time = new \DateTime($start_time);

		if ($end_time == null){
			$var = $start_time->format("Y-m-d H:i:s");
			$end_time = new \DateTime($var);
			$end_time->modify('+1 day');
			$end_time = $end_time->format("Y-m-d H:i:s");
			$end_time = new \DateTime($end_time);
		}
		else{
			$end_time = new \DateTime($end_time);
			$end_time->modify('+1 day');
		}

		$param = array(
			'start_time' => $start_time->getTimestamp(),
			'end_time' => $end_time->getTimestamp()
			);

		$url = $url . http_build_query($param);
		$context = stream_context_create($opts);

		$response = file_get_contents($url, false, $context);

		$moves = json_decode($response, true);
		return $moves['data'];
	}
}
