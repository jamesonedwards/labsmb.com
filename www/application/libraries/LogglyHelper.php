<?php

class LogglyHelper
{
	function LogglyHelper()
	{
		// All methods are static.
	}

	protected static function getLogDate()
	{
		date_default_timezone_set('EST');
		return date('Y-m-d : H:i:s T');
	}

	public static function logError($msg)
	{
		$msg = "[" . LogglyHelper::getLogDate() . "] - ERROR - " . $msg;
		LogglyHelper::writeToLog(LOG_ERR, $msg);
	}

	public static function logMessage($msg)
	{
		$msg = "[" . LogglyHelper::getLogDate() . "] - MESSAGE - " . $msg;
		LogglyHelper::writeToLog(LOG_INFO, $msg);
	}

	protected static function writeToLog($priority, $msg)
	{
		if (LOGGING_ECHO_MESSAGES == true)
			echo $msg ."\n";
		
		if (LOGGING_USE_SYSLOG == true)
		{
			// Submit to syslog, which forwards to Loggly.
			// LOG_ERR or LOG_INFO;
			if (!syslog($priority, $msg))
				throw new Exception('Unable to write to syslog.');
		}
		else
		{
			// Submit to Loggly directly.
			// Note: uses JSON endpoint.
			$json = json_encode(array('message' => $msg));
			$ch = curl_init(LOGGING_LOGGLY_INPUT_URL);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_HTTPHEADER, array('content-type: application/json'));
			curl_setopt ($ch, CURLOPT_POST, true);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $json);
			$response = json_decode(curl_exec($ch));
			curl_close($ch);

			if ($response->response != 'ok') // TODO: should probably notify someone if this happens.
				throw new Exception('Unable to post data to Loggly.');
		}
	}

    public static function logException(Exception $exception)
    {
        LogglyHelper::logError(parseException($exception));
    }

	public static function logExceptionArray($arExceptions)
	{
		foreach($arExceptions as $ex)
		{
			LogglyHelper::logException($ex);
		}
	}

    public static function logValidationErrorArray($errors)
    {
        $errorString = '';

        foreach(array_keys($errors) as $key)
        {
            $errorString .= "Key: \"" . $key . "\" || Message: \"" . $errors[$key] . "\"\n";
        }

        LogglyHelper::logError($errorString);
    }
}
?>