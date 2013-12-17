<?php

class DatabaseHelper
{
    public function DatabaseHelper()
    {
        
    }
    
    public static function convertDbBooleanToPhp($val)
    {
        if (($val == 'true') || ($val == 't') || ($val == 1))
        {
            return true;
        }
        elseif (($val == 'false') || ($val == 'f') || ($val == 0))
        {
            return false;
        }
        else
        {
            return null;
        }
    }

    public static function convertPhpBooleanToDb($val)
    {
        if (($val == 'true') || ($val == 't') || ($val === 1))
        {
            return true;
        }
        elseif (($val == 'false') || ($val == 'f') || ($val === 0))
        {
            return false;
        }
        else
        {
            return null;
        }
    }
    
    public static function convertPhpBooleanToDbString($val)
    {
        if (($val == 'true') || ($val == 't') || ($val === 1))
        {
            return 'TRUE';
        }
        elseif (($val == 'false') || ($val == 'f') || ($val === 0))
        {
            return 'FALSE';
        }
        else
        {
            return null;
        }
    }
    
    /**
    * Builds a MySQL LIMIT statement.
    * 
    * @param mixed $page
    * @param mixed $pageSize
    */
    public function buildMySqlLimitClause($page = -1, $pageSize = -1)
    {
        // Check for erroneous paging.
        if (($page && !is_numeric($page)) || ($pageSize && !is_numeric($pageSize)))
            throw new Exception('Invalid page or page size parameter: ' . $page . ', ' . $pageSize);
        
        if ($page < 1 || $pageSize < 1)
            return '';  // No paging.
        else
            return " LIMIT " . (($page - 1) * $pageSize) . ", " . $pageSize; // Paging.
    }
}

?>