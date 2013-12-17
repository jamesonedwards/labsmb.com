<?php

class DateHelper
{
	function DateHelper()
	{
		// All methods are static.
	}

	public static function getDayFromString($date)
	{
		return date("d", strtotime($date));
	}

	public static function getMonthFromString($date)
	{
		return date("m", strtotime($date));
	}

	public static function getYearFromString($date)
	{
		return date("Y", strtotime($date));
	}

	public static function getMonthNameFromString($date)
	{
		return date("F", strtotime($date));
	}

	public static function formatDateForPublicView($date)
	{
		return date(DATEFMT_LONGDATE, strtotime($date));
	}

    public static function formatDateForDB($date = null)
    {
        if ($date == null)
        {
            return date(DATEFMT_DBFORMAT);
        }
        elseif (!strtotime($date))
        {
            throw new Exception('Invalid or malformed date: ' . $date);
        }
        else
        {
            return date(DATEFMT_DBFORMAT, strtotime($date));
        }
    }
    
    public static function formatDateForDBNoTime($date = null)
    {
        if ($date == null)
        {
            return date(DATEFMT_DBFORMAT_DATE_ONLY);
        }
        elseif (!strtotime($date))
        {
            throw new Exception('Invalid or malformed date: ' . $date);
        }
        else
        {
            return date(DATEFMT_DBFORMAT_DATE_ONLY, strtotime($date));
        }
    }
    
    public static function nowDateOnly()
    {
        return date(DATEFMT_DBFORMAT_DATE_ONLY); 
    }
        
    public static function nowDB()
    {
        return date(DATEFMT_DBFORMAT); 
    }
    
    public static function getUtcDateForDB()
    {
        return gmdate(DATEFMT_DBFORMAT);
    }

    public static function isDateValidForDB($date)
    {
        $date = trim($date);
        
        if (!$date) return false;

        try
        {
            // Return true if format is successful.
            DateHelper::formatDateForDB($date);
            return true;
        }
        catch(Exception $ex)
        {
            return false;
        }
    }
}

?>