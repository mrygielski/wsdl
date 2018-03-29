<?php
////
// Error handling
////

    error_reporting( E_ERROR );
    function handleError($errno, $errstr,$error_file,$error_line) { throw new Exception("Error [$errno]: $errstr - $error_file:$error_line"); }
    set_error_handler("handleError");
    
    error_reporting(0);
    set_error_handler('myErrorHandler');
    register_shutdown_function('fatalErrorShutdownHandler');
    function myErrorHandler($code, $message, $file, $line) 
    {
        die("Fatal Error: [$code] $message - $file:$line");
    }

    function fatalErrorShutdownHandler()
    {
        $last_error = error_get_last();
        if ($last_error['type'] === E_ERROR) 
        {
            // fatal error
            myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
        }
    }
?>