<?php
namespace ukandoit\AppBundle\Services\Withings;

define("LOG_LEVEL_DEBUG", -1);
define("LOG_LEVEL_INFO", 0);
define("LOG_LEVEL_WARNING", 1);
define("LOG_LEVEL_ERROR", 2);

define("LOG_STDOUT", 1);
define("LOG_FILE", 2);

class Log
{
    private $class;
    private $name;
    private $log_dest;
    private $file_name;
    private $session;
    private $script;
    private $log_version;
    private $log_level;

    function __construct($class, $name, $log_dest = LOG_FILE, $log_level = LOG_LEVEL_INFO)
    {
        $this->log_version = 1;
        $this->log_level = $log_level;
        $this->class = $class;
        $this->name = $name;
        $this->log_dest = $log_dest;
        $this->session = rand(1, 20000);
        $this->script = $GLOBALS['argv'][0] != "" ? basename($GLOBALS['argv'][0]) : $_SERVER["REQUEST_URI"];
        $this->file_name = LOG_BASE_PATH . DIRECTORY_SEPARATOR . $this->class . "-" . $this->name . ".log";
    }

    function debug($msg)
    {
        $this->log(LOG_LEVEL_DEBUG, $msg);
    }

    function info($msg)
    {
        $this->log(LOG_LEVEL_INFO, $msg);
    }

    function warning($msg)
    {
        $this->log(LOG_LEVEL_WARNING, $msg);
    }

    function error($msg)
    {
        $this->log(LOG_LEVEL_ERROR, $msg);
    }

    function log($level, $msg)
    {
        if ($level < $this->log_level) return;
        $log_msg = date("c") . " LOGV" . $this->log_version . " [" . $this->script . "] [" . $this->session . "] [" . $level . "] " . $msg . "\n";
        if ($this->log_dest == LOG_FILE) {
            file_put_contents($this->file_name, $log_msg, FILE_APPEND);
        } else {
            echo $log_msg;
        }
    }
}