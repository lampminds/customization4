<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Logs activity only if:
 *  - its type is "error" or higher
 *  - log is set to ON for this specific action
 * @param string $type one of the standard Monolog types
 *          (debug, info, notice, warning, error, critical, alert, emergency)
 * @param string $code the code inside the Shipsecure system
 * @param string $message the message to log
 */
function fnLog(string $type, string $code, string $message)
{
    static $flags = [];
    static $channels = [];

    $type = (strtolower($type) ?: 'undefined');
    $code = strtolower($code);

    if (!array_key_exists($code, $flags)) {
        // we store it statically for better performance
        $param = getParameter('log_'.$code.'_activity');
        if ($param) {
            $flags[$code] = ($param->value != 'N' ? true:false);
            $channels[$code] = Str::lower($param->auxiliary) ?? 'general';
        } else {
            // cannot find what to do? Then we log it
            $flags[$code] = true;
            $channels[$code] = 'general';
        }

    }
    $user = auth()->check() ? auth()->user()->name : 'job';

    if (strpos('|error|critical|alert|emergency', $type) || $flags[$code]) {
        Log::channel('daily-'.$channels[$code])->$type('['.$user.' - '.strtoupper($code).']: '.$message);
    }
}

function fnLogDebug(string $code, string $message)
{
    fnLog('debug', $code, $message);
}
function fnLogNotice(string $code, string $message)
{
    fnLog('notice', $code, $message);
}
function fnLogWarning(string $code, string $message)
{
    fnLog('warning', $code, $message);
}
function fnLogAlert(string $code, string $message)
{
    fnLog('alert', $code, $message);
}
function fnLogError(string $code, string $message)
{
    fnLog('error', $code, $message);
}
function fnLogCritical(string $code, string $message)
{
    fnLog('critical', $code, $message);
}
