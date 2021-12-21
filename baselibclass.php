<?php 

namespace XcaleGroup;

require_once("collection.php");
require_once("database.php");

abstract class Severity
{
    const INFO = 0;
    const WARNING = 1;
    const ERROR = 2;
    const MESSAGE = 3;
    const TRACE = 4;
    const LOG = 5;
    const DEBUG = 6;
}

class BaselibClass
{
    protected BaselibCollection $trace;
    protected BaselibCollection $logs;

    //Properties
    public $user_id = 0;

    public function __construct()
    {
        $this->trace = new BaselibCollection();
        $this->logs = new BaselibCollection();
        $this->init_database();
    }

    public function __destruct()
    {
        if (__BASELIB__DEBUG) {
            $this->save_debug();
        }

        if (__BASELIB__TRACE) {
            $this->save_trace();
        }

        if (__BASELIB__LOGGING) {
            $this->save_logs();
        }
    }

    private function init_database()
    {
        if (__BASELIB__CREATE_DB) {
            $db = new BaselibDatabase();

            $create_sql = "CREATE TABLE IF NOT EXISTS  `baselib_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `app` varchar(255) NOT NULL,
            `severity` varchar(255) NOT NULL,
            `errorcode` int(11) NOT NULL,
            `error` text NOT NULL,
            `stack_trace` text NOT NULL,
            `user` varchar(255) NOT NULL,
            `created` datetime NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
           ) ENGINE=InnoDB AUTO_INCREMENT=615 DEFAULT CHARSET=utf8";
            // Add to local database
            $stmt = $db->prepare($create_sql);
            $stmt->execute();
        }
    }

    /**
     * Handles Exception logging to either database or to remote endpoint. This will store data to the log for each call.
     * If you use the logs->add method the logs will be saved upon object destruction
     * @param int Severity::INFO, Severity::WARNING, Severity::ERROR, Severity::MESSAGE
     * @param Exception the exception object
     */
    public function log($severity, Exception $e)
    {
        if (__BASELIB__LOGGING) {
            if (__BASELIB__REMOTE__DATA) {
                $this->remote_addlog($severity, $e->getCode(), $e->getMessage(), $e->getTraceAsString(), $this->user_id);
            } else {
                $this->addlog($severity, $e->getCode(), $e->getMessage(), $e->getTraceAsString(), $this->user_id);
            }
        }
    }

    
    public function message(string $title, string $message)
    {
        if (__BASELIB__REMOTE__DATA) {
            $this->remote_addlog(Severity::MESSAGE, 0, $title, $message, $this->user_id);
        } else {
            $this->addlog(Severity::MESSAGE, 0, $title, $message, $this->user_id);
        }
    }

    private function save_debug()
    {
        if (__BASELIB__DEBUG) {
            if (__BASELIB__REMOTE__DATA) {
                $this->remote_addlog(Severity::DEBUG, 0, "", serialize($this), $this->user_id);
            } else {
                $this->addlog(Severity::DEBUG, 0, "", serialize($this), $this->user_id);
            }
        }
    }

    private function save_trace()
    {
        if ($this->trace->count() > 0) {
            if (__BASELIB__TRACE) {
                if (__BASELIB__REMOTE__DATA) {
                    $this->remote_addlog(Severity::TRACE, 0, "", serialize($this->trace), $this->user_id);
                } else {
                    $this->addlog(Severity::TRACE, 0, "", serialize($this->trace), $this->user_id);
                }
            }
        }
    }

    /** Save all logs as a serialized string */
    private function save_logs()
    {
        if ($this->logs->count() > 0) {
            if (__BASELIB__TRACE) {
                if (__BASELIB__REMOTE__DATA) {
                    $this->remote_addlog(Severity::LOG, 0, "", serialize($this->logs), $this->user_id);
                } else {
                    $this->addlog(Severity::LOG, 0, "", serialize($this->logs), $this->user_id);
                }
            }
        }
    }

    private function remote_addlog($severity, $errorcode, $error, $stack_trace, $user)
    {
        switch ($severity) {
            case Severity::INFO: $severity = 'Info';break;
            case Severity::WARNING: $severity = 'Warning';break;
            case Severity::ERROR: $severity = 'Error';break;
            case Severity::MESSAGE: $severity = 'Message';break;
            case Severity::TRACE: $severity = 'Trace';break;
            case Severity::LOG: $severity = 'Log';break;
            case Severity::DEBUG: $severity = 'Debug';break;
        }
        $data = array('key' => __BASELIB__REMOTE__KEY, 'app' => __BASELIB__NAME, 'severity' => $severity, 'errorcode' => $errorcode, 'error' => $error, 'stack_trace' => $stack_trace, 'user' => $user);
    
        $options = array(
            'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        )
        );
    
        $context = stream_context_create($options);
        $result = file_get_contents(__BASELIB__REMOTE__ENDPOINT, false, $context);
    }

    private function addlog($severity, $errorcode, $error, $stack_trace, $user)
    {
        switch ($severity) {
            case Severity::INFO: $severity = 'Info';break;
            case Severity::WARNING: $severity = 'Warning';break;
            case Severity::ERROR: $severity = 'Error';break;
            case Severity::MESSAGE: $severity = 'Message';break;
            case Severity::TRACE: $severity = 'Trace';break;
            case Severity::LOG: $severity = 'Log';break;
            case Severity::DEBUG: $severity = 'Debug';break;
        }

        $db = new BaselibDatabase();
        $query = "INSERT INTO baselib_log (app, severity, errorcode, error, stack_trace, user) VALUES (:app, :severity, :errorcode, :error, :stack_trace, :user)";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':app' => __BASELIB__NAME, ':severity' => $severity, ':errorcode' => $errorcode, ':error' => $error, ':stack_trace' => $stack_trace, ':user' => $user));
        unset($db);
    }
}
