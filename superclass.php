<?php

require_once("collection.php");
require_once(__DIR__ . "/baselibconstants.php");

abstract class Severity {
    const INFO = 0;
    const WARNING = 1;
    const ERROR = 2;
    const MESSAGE = 3;
}

class Superclass
{
    protected Collection $stacktrace;

    //Properties
    public $user_id = 0;

    public function __construct()
    {
        $this->stacktrace = new Collection();
        $this->logs = new Collection();
    }

    public function __destruct()
    {
        if (__BASELIB__DEBUG) {
            $this->save_debug();
        }

        if (__BASELIB__STACKTRACE) {
            $this->save_stacktrace();
        }

        if (__BASELIB__LOGGING) {
            $this->save_logs();
        }
    }

    /**
     * Handles Exception logging to either database or remote endpoint
     * @param int servity 0 = info, 1 = warning, 2 = error
     * @param Exception the exception object
     */
    public function log($severity, Exception $e)
    {
        if (__BASELIB__LOGGING) {
            if (__BASELIB__REMOTE__LOGGING) {
                $this->remote_addlog($severity, $e->getCode(), $e->getMessage(), $e->getTraceAsString(), $this->user_id);
            } else {
                $this->addlog($severity, $e->getCode(), $e->getMessage(), $e->getTraceAsString(), $this->user_id);
            }
        }
    }

    public function message(string $message)
    {
        // Logs message to database
        // __BASELIB__REMOTE__LOGGING
        var_dump($message);
    }

    private function save_debug()
    {
        // __BASELIB__REMOTE__LOGGING
        print_r(serialize($this));
    }

    private function save_stacktrace()
    {
        // __BASELIB__REMOTE__LOGGING
        print_r(serialize($this->stacktrace));
    }

    private function save_logs()
    {
        // __BASELIB__REMOTE__LOGGING
        print_r(serialize($this->logs));
    }

    //TODO: Udvid med en type så man ved om det er en exception eller en message og hvis andet mangler så også det.
    private function remote_addlog($severity, $errorcode, $error, $stack_trace, $user)
    {
        switch ($severity) {
            case Severity::INFO: $severity = 'Info';break;
            case Severity::WARNING: $severity = 'Warning';break;
            case Severity::ERROR: $severity = 'Error';break;
            case Severity::MESSAGE: $severity = 'Message';break;
        }
        $data = array('key' => __BASELIB__REMOTE__KEY, 'app' => __BASELIB__NAME, 'severity' => $severity, 'errorcode' => $errorcode, 'error' => $error, 'stack_trace' => $stack_trace, 'user' => $user);
    
        $options = array(
            'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        )
        );
    
        $context  = stream_context_create($options);
        $result = file_get_contents(__BASELIB__REMOTE__ENDPOINT, false, $context);
    }

    private function addlog($severity, $errorcode, $error, $stack_trace, $user)
    {
        switch ($severity) {
            case 0: $severity = 'Info';break;
            case 1: $severity = 'Warning';break;
            case 2: $severity = 'Error';break;
        }

        // Add to local database
    }
}
?>